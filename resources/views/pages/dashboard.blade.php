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
        .company-card {
            transition: 0.25s;
            border-radius: 12px;
        }
        .company-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.12);
        }
    </style>
@endpush
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <h2 class="fw-bold mb-4">კომპანიები</h2>

                <div class="row">
                    @foreach($companies as $company)
                        <div class="col-md-4 col-xl-3 p-3">
                            <a href="{{ route('company', $company->id) }}" class="text-decoration-none">
                                <div class="card shadow-sm border-0 company-card h-100">
                                    <div class="card-body text-center p-4">

                                        <div class="icon bg-primary bg-opacity-25 rounded-circle mb-3 d-flex
        justify-content-center align-items-center text-primary fw-bold shadow-sm"
                                             style="width:70px;height:70px;margin:auto; font-size:20px; letter-spacing:1px;">
                                            {{ strtoupper($company->code ?? substr($company->name, 0, 2)) }}
                                        </div>


                                        <h5 class="fw-bold text-dark">{{ $company->company_name }}</h5>

                                        @if($company->identification_code)
                                            <p class="text-muted small mt-1">ს/ნ: {{ $company->identification_code }}</p>
                                        @endif

                                        <div class="mt-2 small text-secondary">
                                            <i class="bi bi-folder"></i>
                                            {{ $company->documents_count }} დოკუმენტი
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
