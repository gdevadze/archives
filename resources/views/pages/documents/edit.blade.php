@extends('layouts.app')

@push('css')
    <link href="{{ asset('/admin/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <style>
        .upload-box {
            border: 2px dashed #6c757d;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            <h3 class="mb-4">დოკუმენტის რედაქტირება</h3>

            <div class="card p-4 shadow-sm">

                <div class="row">
                    {{-- კომპანიები (read-only) --}}
                    <div class="mb-3">
                        <label>კომპანიები</label>
                        <select class="form-select js-example-basic-multiple" multiple disabled>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}"
                                    {{ $document->companies->contains($c->id) ? 'selected' : '' }}>
                                    {{ $c->company_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            კომპანიის შეცვლა შეუძლებელია — საჭიროა ახალი დოკუმენტის დამატება
                        </small>
                    </div>

                    <div class="col-4 mb-3">
                        <label>ხელშეკრულების ტიპი</label>
                        <select id="contract_type_id" class="form-select js-example-basic-simple">
                            @foreach($contractTypes as $t)
                                <option value="{{ $t->id }}"
                                    {{ $document->contract_type_id == $t->id ? 'selected' : '' }}>
                                    {{ $t->contract_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-4 mb-3">
                        <label>კომენტარი</label>
                        <input type="text" id="comment" class="form-control"
                               value="{{ $document->comment }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label>დოკუმენტის ნომერი</label>
                        <input type="text" id="document_no" class="form-control"
                               value="{{ $document->document_no }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label>წელი</label>
                        <input type="number" id="year" class="form-control"
                               value="{{ $document->year }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label>ხელშეკრულების თარიღი</label>
                        <input type="date" id="contract_date" class="form-control"
                               value="{{ $document->contract_date->format('Y-m-d') }}">
                    </div>

                    <div class="col-4 mb-3">
                        <label>თანხა</label>
                        <input type="number" id="amount" class="form-control"
                               value="{{ $document->amount }}">
                    </div>
                </div>

                {{-- ფაილი არ იცვლება --}}
                <div class="mb-3">
                    <label>PDF ფაილი</label>
                    <div class="upload-box">
                        <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                        <p class="mb-0">ფაილის შეცვლა რედაქტირებით შეუძლებელია</p>
                    </div>
                </div>

                <button id="saveChanges" class="btn btn-warning w-100 py-2">
                    ცვლილებების გაგზავნა დასადასტურებლად
                </button>

                <div id="info-message" class="alert alert-info mt-3 d-none"></div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(function () {

            $('.js-example-basic-simple').select2();
            $('.js-example-basic-multiple').select2();

            $("#saveChanges").on("click", function () {

                let data = {
                    _token: "{{ csrf_token() }}",
                    contract_type_id: $("#contract_type_id").val(),
                    year: $("#year").val(),
                    contract_date: $("#contract_date").val(),
                    comment: $("#comment").val(),
                    document_no: $("#document_no").val(),
                    amount: $("#amount").val(),
                };

                $.post("{{ route('documents.update.requestChange', $document->id) }}", data)
                    .done(function (res) {
                        $("#info-message")
                            .removeClass("d-none")
                            .text(res.message);
                    })
                    .fail(function () {
                        alert("ცვლილების გაგზავნა ვერ მოხერხდა");
                    });
            });
        });
    </script>
@endpush
