@extends('layouts.app')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">პაროლის შეცვლა</h4>

                    </div>
                </div>
            </div>
            <!-- end page title -->

        </div>
        <!-- container-fluid -->

        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">პაროლის შეცვლა</h4>
            </div><!-- end card header -->
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('update.password') }}" method="POST">
                    @csrf
                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="basiInput" class="form-label">მიმდინარე პაროლი</label>
                            <input type="password" class="form-control" name="current_password" required id="basiInput">
                            @error('current_password')
                            <span class="" style="color: red" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="basiInput" class="form-label">ახალი პაროლი</label>
                            <input type="password" class="form-control" name="password" required id="basiInput">
                            @error('password')
                            <span class="" style="color: red" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-12 col-md-12">
                        <div>
                            <label for="basiInput" class="form-label">გაიმეორეთ ახალი პაროლი</label>
                            <input type="password" class="form-control" name="password_confirmation" required id="basiInput">
                        </div>
                    </div>
                    <button class="btn btn-outline-primary mt-3" type="submit">განახლება</button>
                </form>
            </div>
        </div>

    </div>


@endsection
