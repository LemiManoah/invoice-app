<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In — {{ $businessProfile?->name ?: config('app.name') }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #111111;
            font-family: system-ui, -apple-system, sans-serif;
            padding: 1rem;
        }

        .card {
            display: flex;
            width: 820px;
            max-width: 100%;
            min-height: 500px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        /* ── Left: form panel ── */
        .panel-form {
            flex: 1;
            background: #ffffff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-form h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #111111;
            margin-bottom: 0.25rem;
        }

        .business-name {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #F97316;
            margin-bottom: 2rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            color: #dc2626;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #f3f4f6;
            border: 2px solid transparent;
            border-radius: 10px;
            color: #111;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #F97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
            background: #fff;
        }

        .form-group input::placeholder { color: #aaa; }

        .input-error {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 0.35rem;
        }

        .form-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #777;
            font-size: 0.83rem;
            cursor: pointer;
        }

        .remember-label input[type="checkbox"] { accent-color: #F97316; }

        .forgot-link {
            font-size: 0.83rem;
            color: #F97316;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .forgot-link:hover { opacity: 0.75; }

        .login-btn {
            width: 100%;
            padding: 0.875rem;
            background: #F97316;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(249,115,22,0.35);
        }

        .login-btn:hover {
            background: #ea6b0e;
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(249,115,22,0.45);
        }

        .login-btn:active { transform: translateY(0); }

        /* ── Right: branding panel ── */
        .panel-brand {
            width: 310px;
            flex-shrink: 0;
            background: #111111;
            border-radius: 0 24px 24px 0;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .panel-brand::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249,115,22,0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .panel-brand::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand-icon {
            width: 80px; height: 80px;
            border-radius: 18px;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            border: 2px solid rgba(249,115,22,0.3);
        }

        .brand-icon img {
            width: 100%; height: 100%;
            object-fit: contain;
        }

        .brand-icon-fallback {
            width: 80px; height: 80px;
            border-radius: 18px;
            background: #F97316;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(249,115,22,0.4);
        }

        .brand-icon-fallback svg {
            width: 36px; height: 36px;
            fill: none;
            stroke: #fff;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .brand-tagline {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.6;
        }

        .brand-divider {
            width: 40px;
            height: 3px;
            background: #F97316;
            border-radius: 2px;
            margin: 1.25rem auto;
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .card { flex-direction: column; border-radius: 18px; }
            .panel-brand { width: 100%; border-radius: 0 0 18px 18px; padding: 2rem 1.5rem; }
            .panel-form { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Form panel -->
        <div class="panel-form">
            <h1>Sign In</h1>
            <p class="business-name">{{ $businessProfile?->name ?: config('app.name') }}</p>

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="your@email.com"
                        autocomplete="email" autofocus required />
                    @error('email')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                        placeholder="••••••••"
                        autocomplete="current-password" required />
                    @error('password')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-footer-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>
        </div>

        <!-- Branding panel -->
        <div class="panel-brand">
            @if($businessProfile?->logo_path)
                <div class="brand-icon">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->logo_path) }}"
                         alt="{{ $businessProfile->name }} logo" />
                </div>
            @else
                <div class="brand-icon-fallback">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="8" y1="13" x2="16" y2="13"/>
                        <line x1="8" y1="17" x2="12" y2="17"/>
                    </svg>
                </div>
            @endif
            <div class="brand-name">{{ $businessProfile?->name ?: config('app.name') }}</div>
            <div class="brand-divider"></div>
            <p class="brand-tagline">Professional invoicing,<br>simplified for your business.</p>
        </div>
    </div>
</body>
</html>
