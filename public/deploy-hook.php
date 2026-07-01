<?php

/**
 * TicketMinta — Deployment Hook
 *
 * Called automatically by GitHub Actions after each FTP deploy.
 * Runs migrations, clears stale caches, and rebuilds them on the server.
 *
 * Protected by DEPLOY_HOOK_TOKEN in .env — never accessible without the token.
 */

define('LARAVEL_START', microtime(true));
set_time_limit(300);   // 5 minutes max — migrations can be slow on shared hosts
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

$envPath = __DIR__ . '/../.env';

// ── 1. .env must exist ────────────────────────────────────────────────────────
if (!file_exists($envPath)) {
    http_response_code(503);
    exit(json_encode([
        'error' => '.env file not found. Create it on the server before pushing.',
    ]));
}

// ── 2. Parse .env without booting Laravel ────────────────────────────────────
$envValues = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(ltrim($line), '#') || !str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $envValues[trim($k)] = trim($v, " \t\"'");
}

// ── 3. Auto-generate APP_KEY if not set (first deploy) ───────────────────────
if (empty($envValues['APP_KEY']) || $envValues['APP_KEY'] === '') {
    $newKey      = 'base64:' . base64_encode(random_bytes(32));
    $envContent  = file_get_contents($envPath);

    // Replace blank APP_KEY= line, or append if missing entirely
    if (preg_match('/^APP_KEY=\s*$/m', $envContent)) {
        $envContent = preg_replace('/^APP_KEY=\s*$/m', 'APP_KEY=' . $newKey, $envContent);
    } elseif (!preg_match('/^APP_KEY=/m', $envContent)) {
        $envContent .= PHP_EOL . 'APP_KEY=' . $newKey . PHP_EOL;
    }

    file_put_contents($envPath, $envContent);
    $envValues['APP_KEY'] = $newKey;
}

// ── 5. Ensure SQLite database file exists (if using SQLite) ──────────────────
if (($envValues['DB_CONNECTION'] ?? '') === 'sqlite') {
    $dbValue = $envValues['DB_DATABASE'] ?? 'database.sqlite';

    // Mirror config/database.php: absolute paths used as-is, relative paths
    // are resolved under the database/ directory (same as database_path()).
    if ($dbValue === '' || $dbValue === ':memory:') {
        $dbFile = null; // in-memory or empty — nothing to create
    } elseif ($dbValue[0] === '/' || $dbValue[0] === '\\' ||
              strlen($dbValue) > 2 && ctype_alpha($dbValue[0]) && $dbValue[1] === ':') {
        $dbFile = $dbValue; // already absolute
    } else {
        $dbFile = __DIR__ . '/../database/' . $dbValue;
    }

    if ($dbFile !== null && !file_exists($dbFile)) {
        $dbDir = dirname($dbFile);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }
        touch($dbFile);
        chmod($dbFile, 0664);
    }
}

// ── 6. Bootstrap Laravel console kernel ──────────────────────────────────────
require __DIR__ . '/../vendor/autoload.php';

$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// ── 7. Run deployment commands in order ──────────────────────────────────────
//
//  ORDER MATTERS:
//   a) migrate first  — new tables/columns exist before cache is built
//   b) optimize:clear — wipe stale caches from previous deploy
//   c) rebuild caches — fresh, server-path-correct compiled files
//   d) storage:link   — safe to retry; fails gracefully if already linked
//
$commands = [
    'migrate'        => ['--force' => true],
    'optimize:clear' => [],
    'config:cache'   => [],
    'route:cache'    => [],
    'view:cache'     => [],
    'event:cache'    => [],
    'storage:link'   => [],
];

// Optional: pass ?seed=1 in the URL to also run seeders (first deploy only).
// Example: https://yourdomain.com/deploy-hook.php?token=TOKEN&seed=1
if (($_GET['seed'] ?? '') === '1') {
    $commands['db:seed'] = ['--force' => true];
}

$steps     = [];
$anyFailed = false;

foreach ($commands as $command => $params) {
    try {
        $exitCode = $kernel->call($command, $params);
        $output   = trim($kernel->output());

        $steps[] = [
            'command' => $command,
            'exit'    => $exitCode,
            'output'  => $output ?: '(no output)',
            'ok'      => $exitCode === 0,
        ];

        // storage:link returns 1 if symlink already exists — treat as OK
        if ($exitCode !== 0 && $command !== 'storage:link') {
            $anyFailed = true;
            break;   // stop on first real failure
        }
    } catch (Throwable $e) {
        $steps[] = [
            'command' => $command,
            'error'   => $e->getMessage(),
            'ok'      => false,
        ];
        $anyFailed = true;
        break;
    }
}

http_response_code($anyFailed ? 500 : 200);

echo json_encode([
    'success'          => !$anyFailed,
    'deployed_at'      => date('Y-m-d H:i:s T'),
    'duration_seconds' => round(microtime(true) - LARAVEL_START, 2),
    'steps'            => $steps,
], JSON_PRETTY_PRINT);