<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login – {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" />

    <style>
        body {
            background: #f0f2f5;
        }

        .login-card {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            border: none;
        }

        .login-header {
            background: linear-gradient(135deg, #355cdb, #2a3cad);
            padding: 40px;
            color: #fff;
        }

        .login-header h3 {
            font-weight: 600;
        }

        .login-logo {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .login-logo img {
            width: 42px;
            opacity: 0.95;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-primary {
            height: 48px;
            border-radius: 10px;
            font-size: 17px;
        }

        .input-group-text {
            cursor: pointer;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6 col-lg-5 col-xl-4">

        <div class="card login-card">

            <!-- HEADER -->
            <div class="login-header text-center">
                <div class="login-logo">
                    <img src="https://metroholding.ge/assets/images/logo-dark.png" alt="">
                </div>
                <h3>{{ config('app.name') }}</h3>
                <p class="mb-0" style="opacity: .85">ფაილების არქივის მართვის სისტემა</p>
            </div>

            <!-- BODY -->
            <div class="card-body p-4">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label">ელ. ფოსტა</label>
                        <input type="text"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               name="email"
                               placeholder="შეიყვანეთ ელ. ფოსტა"
                               required>
                        @error('email')
                        <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">პაროლი</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   id="passwordInput"
                                   placeholder="შეიყვანეთ პაროლი"
                                   required>

                            <span class="input-group-text" id="togglePassword">
                                <i class="mdi mdi-eye-outline"></i>
                            </span>
                        </div>

                        @error('password')
                        <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- BUTTON -->
                    <div class="mt-4 d-grid">
                        <button type="submit" class="btn btn-primary">
                            შესვლა
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <p class="text-center text-muted mt-3">
            © {{ date('Y') }} – შექმნეს <b>Gio Dev</b>
        </p>

    </div>
</div>

<script src="{{ asset('admin/libs/jquery/jquery.min.js') }}"></script>
<script>
    // Show / Hide Password
    $('#togglePassword').on('click', function () {
        let pass = $('#passwordInput');
        let type = pass.attr('type') === 'password' ? 'text' : 'password';
        pass.attr('type', type);

        $(this).find('i').toggleClass('mdi-eye-outline mdi-eye-off-outline');
    });
</script>

</body>
</html>
