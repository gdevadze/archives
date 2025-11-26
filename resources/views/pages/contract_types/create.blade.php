@extends('layouts.app')

@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">ხელშეკრულების ტიპის დამატება</h4>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

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
                                <form action="{{ route('contract_types.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <ul class="nav nav-tabs" role="tablist">
                                        @foreach($languages as $language)
                                            <li class="nav-item">
                                                <a class="nav-link @if($language->code == 'ka') active @endif" data-bs-toggle="tab" href="#page-{{ $language->code }}" role="tab">
                                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                    <span class="d-none d-sm-block">{{ strtoupper($language->code) }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content p-3 text-muted">
                                        @foreach($languages as $language)
                                            <div class="tab-pane @if($language->code == 'ka') active @endif" id="page-{{ $language->code }}" role="tabpanel">
                                                <div class="form-group col-md-12">
                                                    <strong>დასახელება ({{ strtoupper($language->code) }}):</strong>
                                                    <input type="text" class="form-control" name="translations[{{ $language->code }}][contract_type_name]">
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                    {{---
                                    <div class="form-group form-check">
                                        <input type="checkbox" name="show_on_sale" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">გამოჩნდეს ფასდაკლებაში</label>
                                    </div>
                                    ---}}

                                    <button type="submit" class="btn btn-outline-primary waves-effect waves-light">
                                        დამატება
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>

@endsection
@push('js')
    <script src="https://cdn.ckeditor.com/4.14.0/full/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('text', {
            language: 'en',
            toolbarGroups: [
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'forms', groups: ['forms']},
                '/',
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'links', groups: ['links']},
                {name: 'insert', groups: ['insert']},
                '/',
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
                {name: 'tools', groups: ['tools']},
                {name: 'others', groups: ['others']},
                {name: 'about', groups: ['about']}
            ],
            removeButtons: 'Save,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Image,Flash,Iframe,About',
        })
    </script>
@endpush
