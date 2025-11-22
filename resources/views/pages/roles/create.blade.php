@extends('layouts.app')



@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">მომხმარებლები</h4>


                    </div>
                </div>
            </div>



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



                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label><strong>Name:</strong></label>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label><strong>Permission:</strong></label><br>
                            @foreach($permission as $value)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $value->id }}">
                                    {{ $value->name }}
                                </label><br>
                            @endforeach
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
@endsection
