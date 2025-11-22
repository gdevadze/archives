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


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    <input type="text" name="name" value="{{ old('name', $role->name) }}" placeholder="Name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <strong>Permissions:</strong>
                                    <br/>

                                    @php
                                        $grouped = collect($permission)->groupBy(function($item) {
                                            return explode('-', $item->name)[0]; // group by prefix
                                        });
                                    @endphp

                                    @foreach($grouped as $group => $perms)
                                        <div class="border p-2 mb-3">
                                            <h5 class="text-capitalize d-flex justify-content-between align-items-center">
                                                {{ $group }} Permissions
                                                <label>
                                                    <input type="checkbox" class="check-all" data-group="{{ $group }}">
                                                    <small>Check All</small>
                                                </label>
                                            </h5>

                                            @foreach($perms as $value)
                                                <label class="d-block">
                                                    <input type="checkbox"
                                                           name="permission[]"
                                                           value="{{ $value->id }}"
                                                           class="perm-checkbox group-{{ $group }}"
                                                        {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                                    {{ str_replace('-',' ',ucfirst($value->name)) }}</label>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach

                                </div>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.querySelectorAll('.check-all').forEach(function(masterCheckbox) {
            masterCheckbox.addEventListener('change', function() {
                const group = this.getAttribute('data-group');
                const checkboxes = document.querySelectorAll('.group-' + group);
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = masterCheckbox.checked;
                });
            });
        });
    </script>
@endpush
