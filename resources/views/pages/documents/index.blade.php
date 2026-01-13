@extends('layouts.app')
@push('css')
    <link href="{{ asset('admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <!-- Responsive datatable examples -->
    <link href="{{ asset('admin/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <link href="{{ asset('/admin/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        table.dataTable td {
            white-space: normal !important;
            word-break: break-word;
        }
    </style>
@endpush
@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">ატვირთული დოკუმენტები</h4>


                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>{{ $message }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row mb-3">

                                <div class="form-group col-md-6">
                                    <strong>კომპანია</strong>
                                    <select class="form-control js-example-basic-multiple" multiple="multiple" id="company_ids">
                                        <option value="">აირჩიეთ</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <strong>ხელშეკრულების ტიპი</strong>
                                    <select class="form-control js-example-basic-simple" id="contract_type">
                                        <option value="">აირჩიეთ</option>
                                        @foreach($contractTypes as $contractType)
                                            <option value="{{ $contractType->id }}">{{ $contractType->contract_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <strong>წელი</strong>
                                    <select class="form-control js-example-basic-simple" id="year">
                                        <option value="">აირჩიეთ</option>
                                        @for($i = date('Y'); $i >= 2010; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <strong>ხელშეკრულების თარიღი</strong>
                                    <input type="date" class="form-control" id="contract_date" >
                                </div>

                            </div>
                            <table id="users" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">დოკუმენტის ნომერი</th>
                                    <th scope="col">კომპანიები</th>
                                    <th scope="col">ხელშეკრულების თარიღი</th>
                                    <th scope="col">კომენტარი</th>
                                    <th scope="col">მოქმედება</th>
                                </tr>
                                </thead>


                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    <div id="modal_form" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_label"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="htmlDisplay"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">დახურვა</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="pdfPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">PDF Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">
                    <iframe id="pdfViewer" src="" style="width:100%;height:80vh;border:0;"></iframe>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Required datatable js -->
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

    <!-- Responsive examples -->
    <script src="{{ asset('admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ asset('admin/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('admin/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('admin/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-simple').select2();
            $('.js-example-basic-multiple').select2();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ka.js"></script>

    <script>
        $(document).ready(function () {

            flatpickr("#contract_date", {
                "locale": "ka",
                altInput: true,
                altFormat: "d.m.Y",
                dateFormat: "Y-m-d",
            });
            flatpickr("#end_date", {
                "locale": "ka",
                altInput: true,
                altFormat: "d.m.Y",
                dateFormat: "Y-m-d",
            });
        });

        function previewPDF(url) {
            document.getElementById('pdfViewer').src = url;
            let modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
            modal.show();
        }

    </script>

    <script>
        let table;
        let save_method;
        $(document).ready(function () {
            var companies = [];
            table = $('#users').DataTable({
                processing: true,
                order: [[0, 'desc']],
                serverSide: true,
                language: {
                    url: "{{ __('table-language') }}"
                },
                ajax: {
                    url: "{{ route('documents.ajax') }}",
                    type: 'POST',
                    data: function (d) {
                        d._token = '{{ csrf_token() }}'
                        d.company_ids = companies
                        d.contract_type = $('#contract_type').val()
                        d.contract_date = $('#contract_date').val()
                        d.year = $('#year').val()
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'company_names', name: 'title'},
                    {data: 'formatted_contract_date', name: 'contract_date'},
                    {data: 'comment', name: 'comment'},
                    {data: 'action', name: 'action'},
                ]
            });

            $('#company_ids').on('change', function () {
                var selectedValues = $(this).val();
                companies = [];
                selectedValues.forEach(function(value) {
                    if (!companies.includes(value)) { // Avoid duplicates
                        companies.push(value);
                    }
                });
                table.draw();
            });

            $('#contract_type').on('change', function () {
                table.draw();
            });

            $('#year').on('change', function () {
                table.draw();
            });

            $('#contract_date').on('change', function () {
                table.draw();
            });
        });

        function reload() {
            table.ajax.reload();
        }


    </script>
@endpush
