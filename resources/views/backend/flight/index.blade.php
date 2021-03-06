@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Widgets')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title"><i class="icon voyager-logbook"></i> Flights</h1>
        <a href="{{ URL::route('flights.create') }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
        </a>
    </div>
    @stop

        @section('content')
            <div class="page-content browse container-fluid">
                @include('voyager::alerts')
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div id="dataTable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table id="dataTable" class="table table-hover dataTable no-footer"
                                                       role="grid" aria-describedby="dataTable_info">
                                                    <thead>
                                                    <tr>
                                                        <th>#ID</th>
                                                        <th>Tên chuyến bay</th>
                                                        <th>Tuyến đường</th>
                                                        <th>Hãng máy bay</th>
                                                        <th>Tên máy bay</th>
                                                        <th>Giờ khỏi hành</th>
                                                        <th>Giờ hạ cánh</th>
                                                        <th>Giá vé</th>
                                                        <th class="text-right">{{ __('voyager::generic.actions') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($flights  as $key => $flight)
                                                        <tr role="row">
                                                        <td>#{{ $flight->id }}</td>
                                                        <td>{{ $flight->name }}</td>
                                                        <td>{{ $flight->route->route_name }}</td>
                                                        <td>{{ $flight->airline->airline_name }}</td>
                                                        <td>{{ $flight->airplane->airplane_name }}</td>
                                                        <td>{{ $flight->arrive_date }}</td>
                                                        <td>{{ $flight->depart_date }}</td>
                                                        <td>
                                                            @foreach($flight['ticket'] as $tc)
                                                                <p><b>{{ $tc->ticket_type_name }}: </b>{{ format_currency($tc->price) }} đ</p>
                                                            @endforeach
                                                        </td>
                                                        <td class="text-center" id="bread-actions">
                                                            <a href="javascript:;" title="Xóa" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $flight->id }}"
                                                               id="delete-{{ $flight->id }}">
                                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Xóa</span>
                                                            </a>
                                                            <a href="{{ URL::route('flights.edit',$flight->id) }}" title="Chỉnh sửa" class="btn btn-sm btn-primary pull-right edit">
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
                                </div>
                            </div>
                        </div>
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

    @section('css')

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
