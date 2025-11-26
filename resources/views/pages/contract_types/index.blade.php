@extends('layouts.app')
@push('css')
    <link href="{{ asset('admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>

    <!-- Responsive datatable examples -->
    <link href="{{ asset('admin/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
          rel="stylesheet" type="text/css"/>
@endpush
@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">ხელშეკრულების ტიპები</h4>


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
                            <a type="submit" href="{{ route('contract_types.create') }}" class="btn btn-outline-primary waves-effect waves-light mb-2">
                                დამატება
                            </a>
                            <table id="slides" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">დასახელება</th>
                                    <th scope="col">მოქმედება</th>
                                </tr>
                                </thead>


                                <tbody id="sortable">

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>

@endsection

@push('js')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
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


    {{---
    <script>
$(document).ready(function() {
    // Initialize jQuery UI sortable
    $('#sortable').sortable({
        update: function(event, ui) {
            var order = [];

            // Capture the new order of the rows
            $('#sortable tr').each(function(index) {
                var slideId = $(this).data('id');
                order.push({
                    id: slideId,
                    sort_order: index + 1 // Assuming you want the index to be the new sort_order
                });
            });

            // Send the updated order to the server via AJAX
            $.ajax({
                url: '#',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ order: order }),
                success: function(response) {
                    console.log('Sort order updated successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating sort order:', error);
                }
            });
        }
    });
});
</script>
---}}
    <script>
        let table;
        let save_method;
        $(document).ready(function () {

            table = $('#slides').DataTable({
                processing: true,
                order: [[0, 'desc']],
                serverSide: true,
                language: {
                    url: "{{ __('table-language') }}"
                },
                ajax: {
                    url: "{{ route('contract_types.ajax') }}",
                    type: 'POST',
                    data: function (d) {
                        d._token = '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'contract_type_name', name: 'contract_type_name'},
                    {data: 'action', name: 'action'}
                ]
            });
        });

        function reload() {
            table.ajax.reload();
        }

        function deleteSlide(id) {
            Swal.fire({
                title: 'ნამდვილად გსურთ სლაიდის წაშლა?',
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
                            url: "{{ route('companies.delete') }}",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                '_token': '{{ csrf_token() }}',
                                'id': id
                            },
                        })
                            .done(function (response) {
                                if (response.status === 1) {
                                    Swal.fire('წარმატებული','სლაიდი წარმატებით წაიშალა','success');
                                    reload();
                                }else if(response.status == 2){
                                    Swal.fire('შეცდომა',response.msg,'warning');
                                    reload();
                                }
                                else {
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
