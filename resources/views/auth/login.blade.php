<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>ADMIN - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="AsiaNet Billing System" name="description" />
    <meta content="AsiaNet" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('admin/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">{{ config('app.name') }}</h5>
                                    {{--                                    <p>Sign in to continue to Skote.</p>--}}
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('admin/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        {{--                        <div class="auth-logo">--}}
                        {{--                            <a href="index-2.html" class="auth-logo-light">--}}
                        {{--                                <div class="avatar-md profile-user-wid mb-4">--}}
                        {{--                                            <span class="avatar-title rounded-circle bg-light">--}}
                        {{--                                                <img src="{{ asset('admin/images/logo-light.svg') }}" alt="" class="rounded-circle" height="34">--}}
                        {{--                                            </span>--}}
                        {{--                                </div>--}}
                        {{--                            </a>--}}

                        {{--                            <a href="index-2.html" class="auth-logo-dark">--}}
                        {{--                                <div class="avatar-md profile-user-wid mb-4">--}}
                        {{--                                            <span class="avatar-title rounded-circle bg-light">--}}
                        {{--                                                <img src="admin/images/logo.svg" alt="" class="rounded-circle" height="34">--}}
                        {{--                                            </span>--}}
                        {{--                                </div>--}}
                        {{--                            </a>--}}
                        {{--                        </div>--}}
                        <div class="p-2">
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">ელ. ფოსტა</label>
                                    <input type="text" class="form-control" id="email" value="{{ old('email') }}" required name="email" placeholder="ელ. ფოსტა">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert" style="display: block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>

                                <div class="mb-3">
                                    <label class="form-label">პაროლი</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" name="password" placeholder="პაროლი" required aria-label="Password" aria-describedby="password-addon">
                                        <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{--                                <div class="form-check">--}}
                                {{--                                    <input class="form-check-input" type="checkbox" id="remember-check">--}}
                                {{--                                    <label class="form-check-label" for="remember-check">--}}
                                {{--                                        Remember me--}}
                                {{--                                    </label>--}}
                                {{--                                </div>--}}

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">შესვლა</button>
                                </div>

                                {{--                                <div class="mt-4 text-center">--}}
                                {{--                                    <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>--}}
                                {{--                                </div>--}}
                            </form>
                        </div>

                    </div>
                </div>
                <div class="mt-5 text-center">

                    <div>
                        {{--                        <p>Don't have an account ? <a href="auth-register.html" class="fw-medium text-primary"> Signup now </a> </p>--}}
                        {{--                        <p>© <script>document.write(new Date().getFullYear())</script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>--}}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- end account-pages -->

<!-- JAVASCRIPT -->
<script src="{{ asset('admin/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('admin/js/app.js') }}"></script>
</body>
</html>
