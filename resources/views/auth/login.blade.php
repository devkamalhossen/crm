<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; }
        .card { max-width: 420px; margin: 6rem auto; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 20px 50px rgba(15, 23, 42, 0.1); }
        .button { width: 100%; padding: 0.9rem 1rem; background: #4f46e5; color: white; border: none; border-radius: 0.75rem; font-weight: 600; cursor: pointer; }
        .button:hover { background: #4338ca; }
        input { width: 100%; padding: 0.85rem 1rem; border: 1px solid #d1d5db; border-radius: 0.75rem; margin-top: 0.5rem; }
        label { display: block; font-size: 0.95rem; color: #475569; }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 0.9rem 1rem; border-radius: 0.75rem; margin-bottom: 1rem; }
        .footer { margin-top: 1rem; color: #64748b; font-size: 0.95rem; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <h1 style="font-size: 1.75rem; margin-bottom: 1rem; color: #0f172a;">Login</h1>

        @if ($errors->any())
            <div class="error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                <input id="remember" name="remember" type="checkbox" style="width: 1rem; height: 1rem;">
                <label for="remember" style="margin: 0;">Remember me</label>
            </div>

            <button type="submit" class="button">Sign in</button>
        </form>

        <p class="footer">Use your role-based email and password.</p>
    </div>
</body>
</html>
