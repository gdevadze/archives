@extends('layouts.app')


@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">მომხმარებლის დამატება</h4>


                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('users.store') }}" id="user_info" method="POST">
                                @csrf
                                <div class="row">

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="name" class="form-label">სახელი</label>
                                            <input type="text" class="form-control" name="name" value="" id="name">
                                            <span class="text-danger errors name_err"></span>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="surname" class="form-label">გვარი</label>
                                            <input type="text" class="form-control" name="surname" value="" id="surname">
                                            <span class="text-danger errors surname_err"></span>
                                        </div>
                                    </div>

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="email" class="form-label">ელ. ფოსტა</label>
                                            <input type="text" class="form-control" name="email" value="" id="email">
                                            <span class="text-danger errors surname_err"></span>
                                        </div>
                                    </div>

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="confirm-password" class="form-label">როლი</label>
                                            <select name="roles" id="role_id" class="form-control">
                                                @foreach ($roles as $role)
                                                    <option value="{{$role}}">{{$role}}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger errors surname_err"></span>
                                        </div>
                                    </div>

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="password" class="form-label">პაროლი</label>
                                            <input type="password" class="form-control" name="password" id="password">
                                            <span class="text-danger errors surname_err"></span>
                                        </div>
                                    </div>

                                    <div class="col-xxl-6 col-md-6">
                                        <div>
                                            <label for="confirm-password" class="form-label">პაროლის დადასტურება</label>
                                            <input type="password" class="form-control" name="confirm-password" id="confirm-password">
                                            <span class="text-danger errors surname_err"></span>
                                        </div>
                                    </div>

                                </div>


                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light save-btn" href="javascript:void(0)">შენახვა</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function(){
            $('#role_id').on('change', function() {
                if ( this.value == 'Influence')
                {
                    $(".hide-district").show();
                }
                else
                {
                    $(".hide-district").hide();
                }
            });
        });
    </script>
@endpush
