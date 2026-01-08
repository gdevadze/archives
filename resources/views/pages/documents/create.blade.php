@extends('layouts.app')
@push('css')
    <link href="{{ asset('/admin/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

    <style>
        .upload-box {
            border: 2px dashed #0d6efd;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
        }
        .upload-box:hover {
            background: #e9ecef;
        }
        embed {
            width: 100%;
            height: 550px;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
        <h3 class="mb-4">@lang('upload_document')</h3>

        <div class="card p-4 shadow-sm">
            <div class="row">
                <div class="mb-3">
                    <label>@lang('company')</label>
                    <select id="company_id" class="form-select js-example-basic-multiple" multiple required>
                        <option value="">@lang('choose_companies')</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}">{{ $c->company_name }} - {{ $c->identification_code }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger d-none" id="err-company_id"></small>
                </div>

                <div class="col-4 mb-3">
                    <label>@lang('contract_type')</label>
                    <select id="contract_type_id" class="form-select js-example-basic-simple">
                        <option value="">@lang('choose')</option>
                        @foreach($contractTypes as $t)
                            <option value="{{ $t->id }}">{{ $t->contract_type_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger d-none" id="err-contract_type_id"></small>
                </div>

                <div class="col-4 mb-3">
                    <label>@lang('comment')</label>
                    <input type="text" id="comment" value="" class="form-control">
{{--                    <small class="text-danger d-none" id="err-year"></small>--}}
                </div>

                <div class="col-4 mb-3">
                    <label>@lang('document_no')</label>
                    <input type="text" id="document_no" value="" class="form-control">
{{--                    <small class="text-danger d-none" id="err-year"></small>--}}
                </div>

                <div class="col-4 mb-3">
                    <label>@lang('year')</label>
                    <select class="form-control js-example-basic-simple" id="year">
                        @for($i = date('Y'); $i >= 2010; $i--)
                            <option value="{{ $i }}" @selected(date('Y') == $i)>{{ $i }}</option>
                        @endfor
                    </select>
                    <small class="text-danger d-none" id="err-year"></small>
                </div>

                <div class="col-4 mb-3">
                    <label>@lang('contract_date')</label>
                    <input type="date" id="contract_date" class="form-control">
                    <small class="text-danger d-none" id="err-contract_date"></small>
                </div>

                <div class="col-4 mb-3">
                    <label>@lang('amount')</label>
                    <input type="number" id="amount" value="0" class="form-control">
                    <small class="text-danger d-none" id="err-year"></small>
                </div>
            </div>

            <input type="hidden" id="file_original_name">
            <input type="hidden" id="temp_file">

            <div class="mb-3">
                <label>PDF ფაილი</label>

                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                    <p>დააჭირე PDF ასარჩევად</p>
                </div>

                <input type="file" id="fileUpload" accept="application/pdf" class="d-none">

                <small class="text-danger d-none" id="err-file"></small>
            </div>

            <button id="uploadBtn" class="btn btn-primary w-100 py-2">ფაილის არჩევა</button>

            <div id="success-message" class="alert alert-success mt-3 d-none"></div>

        </div>
    </div>
    </div>

    {{-- PDF Preview Modal --}}
    <div class="modal fade" id="pdfModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">PDF Preview</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <embed id="pdfPreview" src="" type="application/pdf" />
                </div>

                <div class="modal-footer">
                    <button id="confirmSave" class="btn btn-success">დადასტურება და შენახვა</button>
                </div>

            </div>
        </div>
    </div>

@endsection


@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(function () {

            $("#uploadBox, #uploadBtn").on("click", function () {
                $("#fileUpload").click();
            });

            $("#fileUpload").on("change", function () {

                let file = this.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    $("#err-file").text("ფაილის ზომა 5MB–ს არ უნდა აღემატებოდეს!").removeClass("d-none");
                    return;
                }

                $("#err-file").addClass("d-none");

                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("file", file);

                $.ajax({
                    url: "{{ route('documents.uploadTemp') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (res) {

                        $("#temp_file").val(res.temp_file);
                        $("#file_original_name").val(res.file_name);

                        $("#pdfPreview").attr("src", res.temp_url);

                        $("#pdfModal").modal("show");
                    },

                    error: function() {
                        alert("ფაილის დროებით ატვირთვის შეცდომა");
                    }
                });
            });

            $("#confirmSave").on("click", function () {

                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                let companies = $("#company_id").val() || [];

                companies.forEach(function (id) {
                    formData.append("company_ids[]", id);
                });

                formData.append("company_id", $("#company_id").val());
                formData.append("contract_type_id", $("#contract_type_id").val());
                formData.append("year", $("#year").val());
                formData.append("contract_date", $("#contract_date").val());
                formData.append("comment", $("#comment").val());
                formData.append("amount", $("#amount").val());
                formData.append("document_no", $("#document_no").val());
                formData.append("temp_file", $("#temp_file").val());
                formData.append("file_original_name", $("#file_original_name").val());

                $.ajax({
                    url: "{{ route('documents.store') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function (res) {
                        $("#pdfModal").modal("hide");
                        $("#success-message").removeClass("d-none").html(res.message);

                        setTimeout(function () {
                            location.reload();
                        }, 5000);

                    },

                    error: function() {
                        alert("შენახვის შეცდომა");
                    }
                });

            });

        });

        $(document).ready(function () {
            $('.js-example-basic-simple').select2();
            $('.js-example-basic-multiple').select2({
                placeholder: "აირჩიეთ კომპანიები"
            });

            $('.js-example-basic-multiple').on('select2:select', function(e) {
                let selected = $(this).val() || [];

                if (selected.length > 4) {
                    selected.pop(); // ბოლო დამატებული წაშალე
                    $(this).val(selected).trigger('change');

                    $("#err-company_id")
                        .text("მაქსიმუმ 4 კომპანიის არჩევა შეიძლება")
                        .removeClass("d-none");
                } else {
                    $("#err-company_id").addClass("d-none");
                }
            });

        });
    </script>
@endpush
