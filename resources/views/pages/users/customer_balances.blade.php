@extends('layouts.admin')
@push('css')
    <link href="{{ asset('admin_assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('admin_assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <!-- Responsive datatable examples -->
    <link href="{{ asset('admin_assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>
@endpush
@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">ხელით ჩარიცხვები</h4>


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
                            {{--                            <h4 class="card-title">Default Datatable</h4>--}}
                            {{--                            <p class="card-title-desc">DataTables has most features enabled by--}}
                            {{--                                default, so all you need to do to use it with your own tables is to call--}}
                            {{--                                the construction function: <code>$().DataTable();</code>.--}}
                            {{--                            </p>--}}

                            <table id="users" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ჩარიცხა</th>
                                    <th scope="col">მომხმარებელი</th>
                                    <th scope="col">თანხა</th>
                                    <th scope="col">კომენტარი</th>
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
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog"
                 aria-labelledby="exampleStandardModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="javascript:void(0)" id="balance_form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="balance_user_id" name="balance_user_id">
                                <input type="hidden" id="action" name="action">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>თანხა</label>
                                        <input type="text" class="form-control" id="amount" name="amount"
                                               placeholder="თანხა" required>
                                        <span class="text-danger error-text errors amount_err"></span>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>მიზეზი</label>
                                        <input type="text" class="form-control" id="comment" name="comment"
                                               placeholder="მიზეზი" required>
                                        <span class="text-danger error-text errors comment_err"></span>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btnSave" onclick="saveBalance()"><i
                                        class="fa fa-floppy-o mr-2" aria-hidden="true"></i>შენახვა
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"
                                        aria-hidden="true"></i>დახურვა
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
@endsection

@push('js')
    <!-- Required datatable js -->
    <script src="{{ adminAssets('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ adminAssets('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ adminAssets('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ adminAssets('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ adminAssets('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ adminAssets('assets/js/pages/datatables.init.js') }}"></script>

    <script>
        let table;
        let save_method;
        $(document).ready(function () {

            table = $('#users').DataTable({
                processing: true,
                order: [[0, 'desc']],
                serverSide: true,
                language: {
                    url: "{{ __('table-language') }}"
                },
                ajax: {
                    url: "{{ route('admin.users.customer.balances.ajax') }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'create_user.full_name', name: 'create_user.name'},
                    {data: 'user.full_name', name: 'user.name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'comment', name: 'comment'},
                ]
            });
        });

        function reload() {
            table.ajax.reload();
        }
        
        function balanceAction(id) {
            let action = $('.btn-blnc').data('status');
            console.log(action)
            if (action == 'balance-plus'){
                $('#balance_form')[0].reset(); // reset form on modals
                $('.form-group').removeClass('has-error'); // clear error class
                $('.help-block').empty(); // clear error string
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('#modal_title').text(`ბალანსის შევსება`);
                $('#balance_user_id').val(id)
                $('#action').val(action)
            }else if(action == 'balance-minus'){
                $('#balance_form')[0].reset(); // reset form on modals
                $('.form-group').removeClass('has-error'); // clear error class
                $('.help-block').empty(); // clear error string
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('#modal_title').text(`ბალანსის ჩამოჭრა`);
                $('#balance_user_id').val(id)
                $('#action').val(action)
            }else{
                Swal.fire('შეცდომა','მოქმედება შეუძლებელია!','error')
            }
        }
            
            function saveBalance() {
                $('#btnSave').html('დაელოდეთ...');
                $('#btnSave').attr('disabled', true);
                let url;
                var data_save = $('#balance_form').serializeArray()
                url = "{{ url('admin/users/balance_action') }}";
                $.ajax({
                    url: url,
                    method: "POST",
                    data: data_save,
                    success: function (data) {
                        if (data.status == 1) {
                            Swal.fire('წარმატებული!', data.msg, 'success');
                            $('#modal_form').modal('hide');
                            reload();
                        } else if(data.status == 2) {
                            Swal.fire('შეცდომა', data.msg, 'error');
                        }else{
                            Swal.fire('შეცდომა', 'სცადეთ მოგვიანებით', 'error');
                        }


                        $('#btnSave').html('შენახვა');
                        $('#btnSave').attr('disabled', false); //set button enable
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 422) { // when status code is 422, it's a validation issue
                            $('.errors').html('');
                            $.each(xhr.responseJSON.errors, function (i, error) {
                                $('.'+i+'_err').html(error);
                            });
                        }
                        $('#btnSave').html('შენახვა');
                        $('#btnSave').attr('disabled', false); //set button enable
                    }
                });
            }

        function deleteUser(id) {
            Swal.fire({
                title: 'ნამდვილად გსურთ მომხმარებლის წაშლა?',
                text: "",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'დიახ!',
                cancelButtonText: 'არა!',
                preConfirm: function () {
                    return new Promise(function (resolve) {
                        $('.swal2-confirm').html('<i class="fa fa-spinner fa-spin mr-1"></i>');
                        $.ajax({
                            url: "{{ url('/users/delete_user') }}",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'id': id
                            },
                        })
                            .done(function (response) {
                                if (response.status === 1) {
                                    Swal.fire({
                                        title: 'წარმატებული!',
                                        text: "მომხმარებელი წარმატებით წაიშალა",
                                        icon: 'success',
                                        showCancelButton: false
                                    }).then((result) => {
                                        if (result.value) {
                                            location.reload()
                                        }
                                    })
                                } else {
                                    Swal.fire('შეცდომა!', 'სცადეთ მოგვიანებით', 'error');
                                }
                            })
                            .fail(function () {
                                Swal.fire('შეცდომა!', 'სცადეთ მოგვიანებით', 'error');
                            });
                    });
                },
                allowOutsideClick: true
            });
        }
    </script>
@endpush
