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
                        <h4 class="mb-sm-0 font-size-18">მომხმარებლის ({{ $user->full_name }}) რეპორტის პარამეტრები</h4>


                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{--                            <h4 class="card-title">Default Datatable</h4>--}}
                            {{--                            <p class="card-title-desc">DataTables has most features enabled by--}}
                            {{--                                default, so all you need to do to use it with your own tables is to call--}}
                            {{--                                the construction function: <code>$().DataTable();</code>.--}}
                            {{--                            </p>--}}
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
                            <form action="{{ route('users.update.report.settings',$user->id) }}" id="user_info" method="POST">
                                @method('PUT')
                                @csrf

                                <div class="mb-5">
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
                                            @php
                                                $pivot = $user->companies->find($company->id)?->pivot;
                                            @endphp

                                            <div class="col-md-4 col-lg-3">
                                                <div class="select-card clickable-card">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="select-title">
                                                            {{ $company->company_name }}
                                                        </div>

                                                        <input type="checkbox"
                                                               class="form-check-input toggle company-checkbox"
                                                               onclick="event.stopPropagation()"
                                                               name="companies[{{ $company->id }}][receive_report]"
                                                            {{ $pivot && $pivot->receive_report ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- =========================
                                    CONTRACT TYPES
                                ========================= --}}
                                <div class="mb-5">
                                    <div class="select-header">
                                        <div class="section-title">📑 ხელშეკრულების ტიპები</div>

                                        <div class="select-actions">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="toggleAll('type-checkbox', true)">
                                                ყველას მონიშვნა
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="toggleAll('type-checkbox', false)">
                                                ყველას მოხსნა
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        @foreach($contractTypes as $type)
                                            @php
                                                $pivot = $user->contractTypes->find($type->id)?->pivot;
                                            @endphp

                                            <div class="col-md-4 col-lg-3">

                                                <div class="select-card clickable-card">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="select-title">
                                                            {{ $type->contract_type_name }}
                                                        </div>

                                                        <input type="checkbox"
                                                               class="form-check-input toggle type-checkbox"
                                                               onclick="event.stopPropagation()"
                                                               name="contract_types[{{ $type->id }}][receive_report]"
                                                            {{ $pivot && $pivot->receive_report ? 'checked' : '' }}>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
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
