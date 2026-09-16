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
    {{--<div class="card">
        <h1 style="font-size: 1.75rem; margin-bottom: 1rem; color: #0f172a;text-align: center;">CRM</h1>

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

            <div style="margin-bottom: 1rem; margin-right: 30px;">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div style="margin-bottom: 1rem; margin-right: 30px;">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                <input id="remember" name="remember" type="checkbox" style="width: 1rem; height: 1rem;">
                <label for="remember" style="margin: 0;">Remember me</label>
            </div>

            <button type="submit" class="button">Sign in</button>
        </form>

    </div>--}}


        
    <div class="card">
        <h1 style="font-size: 1.75rem; margin-bottom: 1rem; color: #0f172a; text-align: center;">
            CRM
        </h1>

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

            <div style="margin-bottom: 1rem; margin-right: 30px;">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div style="margin-bottom: 1rem; margin-right: 30px;">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                >
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    style="width: 1rem; height: 1rem;"
                >
                <label for="remember" style="margin: 0;">
                    Remember me
                </label>
            </div>

            <button type="submit" class="button">
                Sign in
            </button>
        </form>

        {{-- Quick Login Accounts --}}
        <div style="margin-top: 1.5rem;">
            <div style="
                text-align: center;
                font-size: 14px;
                color: #64748b;
                margin-bottom: 10px;
            ">
                Quick Login
            </div>

            <div style="
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            ">

                <button type="button"
                        class="quick-login"
                        data-email="admin@gmail.com"
                        data-password="12345678">
                    👨‍💼 Admin
                </button>

                <button type="button"
                        class="quick-login"
                        data-email="account@gmail.com"
                        data-password="12345678">
                    💰 Account
                </button>

                <button type="button"
                        class="quick-login"
                        data-email="project@gmail.com"
                        data-password="12345678">
                    📋 Project Manager
                </button>

                <button type="button"
                        class="quick-login"
                        data-email="sales@gmail.com"
                        data-password="12345678">
                    📈 Sales
                </button>

                <button type="button"
                        class="quick-login"
                        data-email="client@gmail.com"
                        data-password="12345678">
                    👤 Client
                </button>

            </div>
        </div>
    </div>


    <style>
        .quick-login {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155;
            padding: 9px 8px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: 0.2s;
        }

        .quick-login:hover {
            background: #e2e8f0;
        }

        .quick-login:active {
            transform: scale(0.98);
        }
    </style>


    <script>
        document.querySelectorAll('.quick-login').forEach(function (button) {

            button.addEventListener('click', function () {

                const email = this.dataset.email;
                const password = this.dataset.password;

                document.getElementById('email').value = email;
                document.getElementById('password').value = password;

                // Optional: focus on login button
                document.querySelector('.button').focus();
            });

        });
    </script>


</body>
</html>
