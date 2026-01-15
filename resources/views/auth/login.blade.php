<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="utf-8" />
    <title>Login – {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" />

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #e8edff, transparent 60%),
                radial-gradient(circle at bottom right, #f0f4ff, transparent 60%),
                #f6f7fb;
            font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 22px;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(10px);
            box-shadow:
                0 30px 60px rgba(20, 30, 90, 0.15),
                inset 0 1px 0 rgba(255,255,255,0.6);
            border: none;
            overflow: hidden;
        }

        .login-header {
            padding: 42px 30px 32px;
            text-align: center;
            background: linear-gradient(135deg, #3a5bff, #2f3fbf);
            color: #fff;
        }

        .login-logo {
            width: 74px;
            height: 74px;
            border-radius: 18px;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .login-logo img {
            width: 46px;
        }

        .login-header h3 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.85;
            margin: 0;
        }

        .card-body {
            padding: 36px 32px;
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            border: 1px solid #e2e6f0;
            font-size: 15px;
            padding: 0 14px;
        }

        .form-control:focus {
            border-color: #3a5bff;
            box-shadow: 0 0 0 3px rgba(58, 91, 255, 0.15);
        }

        .input-group-text {
            border-radius: 0 12px 12px 0;
            background: #f4f6fb;
            border: 1px solid #e2e6f0;
            cursor: pointer;
        }

        .btn-login {
            height: 52px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            background: linear-gradient(135deg, #3a5bff, #2f3fbf);
            border: none;
            box-shadow: 0 12px 25px rgba(58, 91, 255, 0.35);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(58, 91, 255, 0.45);
        }

        .footer-text {
            text-align: center;
            font-size: 13px;
            color: #8a8fa3;
            margin-top: 18px;
        }
    </style>
</head>

<body>

<div class="login-wrapper">
    <div class="login-card">

        <!-- HEADER -->
        <div class="login-header">
            <div class="login-logo">
                <img src="https://metroholding.ge/assets/images/logo-dark.png" alt="">
            </div>
            <h3>{{ config('app.name') }}</h3>
            <p>დოკუმენტების არქივის სისტემააააა</p>
        </div>

        <!-- BODY -->
        <div class="card-body">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">ელ. ფოსტა</label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="name@company.ge"
                           required>
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">პაროლი</label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               id="passwordInput"
                               placeholder="••••••••"
                               required>
                        <span class="input-group-text" id="togglePassword">
                            <i class="mdi mdi-eye-outline"></i>
                        </span>
                    </div>
                    @error('password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-login text-white">
                        სისტემაში შესვლა
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<p class="footer-text">
    © {{ date('Y') }} {{ config('app.name') }} · Archive Management System
</p>

<script src="{{ asset('admin/libs/jquery/jquery.min.js') }}"></script>
<script>
    $('#togglePassword').on('click', function () {
        let pass = $('#passwordInput');
        let type = pass.attr('type') === 'password' ? 'text' : 'password';
        pass.attr('type', type);
        $(this).find('i').toggleClass('mdi-eye-outline mdi-eye-off-outline');
    });
</script>

</body>
</html>
