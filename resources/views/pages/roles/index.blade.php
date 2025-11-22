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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <div class="pull-right">
                            @can('role-create')
                                <a class="btn btn-success" href="{{ route('roles.create') }}">Create New Role</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th width="280px">Action</th>
                        </tr>

                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a>

                                    @can('role-edit')
                                        <a class="btn btn-primary" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                    @endcan

                                    @can('role-delete')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    {!! $roles->render() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
