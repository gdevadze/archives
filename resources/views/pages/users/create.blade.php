@extends('layouts.app')


@section('content')
    <style>
        .select-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px;
            transition: 0.25s;
            background: #fff;
            height: 100%;
        }

        .select-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transform: translateY(-3px);
            border-color: #d1d5db;
        }

        .select-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .select-title {
            font-weight: 600;
            font-size: 15px;
            color: #111827;
        }

        .select-actions {
            display: flex;
            gap: 10px;
        }

        .toggle {
            cursor: pointer;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .clickable-card {
            cursor: pointer;
        }

    </style>
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
                                            <label for="tel" class="form-label">მობილური</label>
                                            <input type="text" class="form-control" name="tel" value="" id="tel">
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

                                    <div class="mb-5 mt-3">
                                        <div class="select-header">
                                            <div class="section-title">🏢 კომპანიები</div>

                                            <div class="select-actions">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="toggleAll('company-checkbox', true)">
                                                    ყველას მონიშვნა
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="toggleAll('company-checkbox', false)">
                                                    ყველას მოხსნა
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            @foreach($companies as $company)


                                                <div class="col-md-4 col-lg-3">
                                                    <div class="select-card clickable-card">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="select-title">
                                                                {{ $company->company_name }}
                                                            </div>

                                                            <input type="checkbox"
                                                                   class="form-check-input toggle company-checkbox"
                                                                   onclick="event.stopPropagation()"
                                                                   name="companies[{{ $company->id }}][receive_report]">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
        document.querySelectorAll('.clickable-card').forEach(card => {
            card.addEventListener('click', function () {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });

        function toggleAll(className, checked) {
            document.querySelectorAll('.' + className).forEach(el => {
                el.checked = checked;
            });
        }
    </script>
@endpush
