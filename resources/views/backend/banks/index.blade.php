@extends('voyager::master')


@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="icon voyager-logbook"></i>  Banks</h1>
        <a href="{{ URL::route('banks.create') }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Account Number</td>
                        <td>Account Name</td>
                        <td>Branch</td>
                        <td>Logo</td>
                        <td>Actions</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banks as $bank)
                        <tr>
                            <td>{{ $bank->id }}</td>
                            <td>{{ $bank->bank_name }}</td>
                            <td>{{ $bank->account_number }}</td>
                            <td>{{ $bank->account_name }}</td>
                            <td>{{ $bank->branch }}</td>
                            <td>
                                <img style="max-width: 100px" src="{{ $bank->bank_img }}" alt="">
                            </td>
                            <td class="text-center" id="bread-actions">
                                <a href="javascript:;" title="Xóa" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $bank->id }}"
                                   id="delete-{{ $bank->id }}">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Xóa</span>
                                </a>
                                <a href="{{ URL::route('banks.edit',$bank->id) }}" title="Chỉnh sửa" class="btn btn-sm btn-primary pull-right edit">
                                    <i class="voyager-edit"></i> <span
                                        class="hidden-xs hidden-sm">
                                                                    {{ __('voyager::generic.edit') }}
                                                                </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }}</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            var table = $('#dataTable').DataTable({!! json_encode(
                array_merge([
                    "order" => [],
                    "language" => __('voyager::datatable'),
                    "columnDefs" => [['targets' => -1, 'searchable' =>  false, 'orderable' => false]],
                ],
                config('voyager.dashboard.data_tables', []))
            , true) !!});

            $('#search-input select').select2({
                minimumResultsForSearch: Infinity
            });

            $('.side-body').multilingual();
            //Reinitialise the multilingual features when they change tab
            $('#dataTable').on('draw.dt', function(){
                $('.side-body').data('multilingual').init();
            });

            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
            });
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('flights.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });
    </script>
@stop
