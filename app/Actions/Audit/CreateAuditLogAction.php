<?php

declare(strict_types=1);

namespace App\Actions\Audit;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

final readonly class CreateAuditLogAction
{
    public function handle(
        string $actionType,
        Model|string $entity,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $reason = null,
    ): AuditLog {
        $request = request();
        $entityType = $entity instanceof Model ? $entity::class : $entity;
        $entityId = $entity instanceof Model ? $entity->getKey() : null;

        return AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => $actionType,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'reason' => $reason,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
