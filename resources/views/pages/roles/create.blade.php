@extends('layouts.app')



@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">უფლების დამატება</h4>


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
                                <label><strong>დასახელება:</strong></label>
                                <input type="text" name="name" class="form-control" placeholder="@lang('Name')">
                            </div>
                            @php
                                $grouped = collect($permission)->groupBy(function($item) {
                                    return explode('-', $item->name)[0]; // group by prefix
                                });
                            @endphp
                            @foreach($grouped as $group => $perms)
                                <div class="border p-2 mb-3">
                                    <h5 class="text-capitalize d-flex justify-content-between align-items-center">
                                        {{ $group }}
                                        <label>
                                            <input type="checkbox" class="check-all" data-group="{{ $group }}">
                                            <small>ყველას მონიშვნა</small>
                                        </label>
                                    </h5>

                                    @foreach($perms as $value)
                                        <label class="d-block">
                                            <input type="checkbox"
                                                   name="permission[]"
                                                   value="{{ $value->id }}"
                                                   class="perm-checkbox group-{{ $group }}"
                                            >
                                            {{ __('permission.'.$value->name) }}</label>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">დამატება</button>
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
