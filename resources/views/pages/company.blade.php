@extends('layouts.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('/admin/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .doc-card {
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            transition: 0.25s;
            border: 1px solid #eef1f5;
            height: 100%;
        }

        .doc-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            border-color: #d8e2f3;
        }

        .doc-icon {
            width: 70px;
            height: 70px;
            background: #f0f4ff;
            border-radius: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            transition: 0.25s;
        }

        .doc-card:hover .doc-icon {
            background: #e1e9ff;
            transform: scale(1.05);
        }

        .doc-title {
            font-weight: 600;
            font-size: 16px;
            margin-top: 12px;
            text-align: center;
        }

        .doc-meta {
            font-size: 13px;
            text-align: center;
            color: #7b8090;
            margin-bottom: 12px;
        }

        .file-size {
            font-size: 12px;
            text-align: center;
            color: #8891a4;
            margin-bottom: 12px;
        }
    </style>
@endpush
@section('content')



    <div class="page-content">
        <div class="container-fluid">
            <h3 class="fw-bold mb-3">{{ $company->company_name }}</h3>



            {{-- FILTERS --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <form method="GET" class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label small">@lang('contract_type')</label>
                            <select name="contract_type_id" class="form-select js-example-basic-simple">
                                <option value="">ყველა</option>
                                @foreach($contractTypes as $type)
                                    <option value="{{ $type->id }}"
                                        @selected(request('contract_type_id') == $type->id)>
                                        {{ $type->contract_type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">@lang('year')</label>
                            <select name="year" class="form-select">
                                <option value="">ყველა</option>
                                @for($y = date('Y'); $y >= 1990; $y--)
                                    <option value="{{ $y }}" @selected(request('year') == $y)>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small">@lang('file_name')</label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                   class="form-control" placeholder="მოძებნე დოკუმენტი...">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> @lang('filter')
                            </button>
                        </div>

                    </form>

                </div>
            </div>


            {{-- GRID VIEW --}}
            <div class="row g-4">
                @foreach($documents as $doc)

                    @php
                        $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                        $icon = 'bi-file-earmark';
                        $color = '#6c757d';

                        if ($ext === 'pdf') { $icon = 'bi-filetype-pdf'; $color = '#d9534f'; }
                        elseif (in_array($ext,['doc','docx'])) { $icon = 'bi-filetype-docx'; $color = '#1a73e8'; }
                        elseif (in_array($ext,['xls','xlsx'])) { $icon = 'bi-filetype-xlsx'; $color = '#28a745'; }
                        elseif (in_array($ext,['png','jpg','jpeg'])) { $icon = 'bi-file-earmark-image'; $color = '#f0ad4e'; }
                    @endphp

                    <div class="col-md-3 col-sm-6">
                        <div class="doc-card">

                            <div class="doc-icon">
                                <i class="bi {{ $icon }}" style="font-size:42px; color:{{ $color }}"></i>
                            </div>

                            <div class="doc-title text-truncate">
                                {{ $doc->title }}
                            </div>

                            <div class="doc-meta">
                                {{ $doc->contractType?->contract_type_name }} • {{ $doc->year }}
                            </div>

                            {{-- SIZE --}}
                            <div class="file-size">
                                ფაილის ზომა: {{ number_format($doc->size / 1024 / 1024, 2) }} MB
                            </div>

                            <div class="d-flex justify-content-center gap-2">

                                {{-- PDF PREVIEW --}}
                                @if($ext === 'pdf')
                                    <button class="btn btn-outline-primary btn-sm"
                                            onclick="previewPDF('{{ Storage::url($doc->file_path) }}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                @endif

                                {{-- DOWNLOAD --}}
                                <a href="{{ route('documents.download', $doc->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-download"></i>
                                </a>

                                {{-- DOWNLOAD --}}
                                <a href="{{ route('documents.requestChange', $doc->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('documents.destroy', $doc->id) }}"
                                      onsubmit="return confirm('დოკუმენტის წაშლა გსურთ?')"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        @lang('delete')
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>

                @endforeach
            </div>

            <div class="mt-4">{{ $documents->links() }}</div>
        </div>
    </div>

    {{-- PDF MODAL --}}
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
    <script>
        function previewPDF(url) {
            document.getElementById('pdfViewer').src = url;
            let modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
            modal.show();
        }
    </script>

    <script src="{{ asset('admin/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-simple').select2();
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush
