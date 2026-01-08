@extends('layouts.app')
@push('css')
    <link href="{{ asset('admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <!-- Responsive datatable examples -->
    <link href="{{ asset('admin/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <style>
        .company-row {
            transition: 0.25s;
            border-radius: 10px;
        }

        .company-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }

        .company-logo {
            width: 48px;
            height: 48px;
            font-size: 14px;
            letter-spacing: 1px;
        }
    </style>
@endpush
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <h2 class="fw-bold mb-4">@lang('companies')</h2>

                <div class="row">
                    @foreach($companies as $company)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <a href="{{ route('company', $company->id) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm company-row h-100">
                                    <div class="card-body d-flex align-items-center justify-content-between p-3">

                                        <!-- LEFT -->
                                        <div class="d-flex align-items-center gap-3">

                                            <!-- Logo / Initial -->
                                            <div class="company-logo bg-primary bg-opacity-10 text-primary fw-bold
                                        d-flex align-items-center justify-content-center rounded-circle">
                                                {{ strtoupper($company->code ?? mb_substr($company->company_name, 0, 2)) }}
                                            </div>

                                            <!-- Info -->
                                            <div>
                                                <div class="fw-semibold text-dark">
                                                    {{ $company->company_name }}
                                                </div>

                                                @if($company->identification_code)
                                                    <div class="text-muted small">
                                                        ს/ნ: {{ $company->identification_code }}
                                                    </div>
                                                @endif
                                            </div>

                                        </div>

                                        <!-- RIGHT: Documents count -->
                                        <div class="text-end">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-folder2-open me-1"></i>
                                {{ $company->documents_count }}
                            </span>
                                        </div>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>


                <!-- end col -->


            </div>
            <!-- end row -->


        </div> <!-- container-fluid -->
    </div>
@endsection
@push('js')
    <script src="{{ asset('admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(document).ready( function () {

        });
    </script>
@endpush
