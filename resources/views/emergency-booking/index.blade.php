<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle ?? trans('messages.emergency_bookings') }}</h5>
                            <a href="{{ route('booking.index') }}" class="float-end btn btn-sm btn-secondary">
                                <i class="fa fa-angle-double-left"></i> {{ __('messages.back_to_bookings') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">{{ __('messages.status') }}</span>
                                    <select class="form-control select2js" name="status" id="status">
                                        <option value="">{{ __('messages.all_status') }}</option>
                                        <option value="pending">{{ __('messages.pending') }}</option>
                                        <option value="accept">{{ __('messages.accepted') }}</option>
                                        <option value="ongoing">{{ __('messages.ongoing') }}</option>
                                        <option value="completed">{{ __('messages.completed') }}</option>
                                        <option value="cancelled">{{ __('messages.cancelled') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">{{ __('messages.date_range') }}</span>
                                    <input type="text" class="form-control daterange-picker" name="booking_date_range" id="booking_date_range">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">{{ __('messages.search') }}</span>
                                    <input type="text" class="form-control" name="search" id="search" placeholder="{{ __('messages.search') }}">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="emergency-bookings-table" class="table table-striped border">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('bottom_script')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#emergency-bookings-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('emergency.bookings_data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status = $('#status').val();
                        d.booking_date_range = $('#booking_date_range').val();
                        d.search = $('#search').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id', title: "{{ __('messages.id') }}"},
                    {data: 'service_id', name: 'service_id', title: "{{ __('messages.service') }}"},
                    {data: 'customer_id', name: 'customer_id', title: "{{ __('messages.customer') }}"},
                    {data: 'provider_id', name: 'provider_id', title: "{{ __('messages.provider') }}"},
                    {data: 'date', name: 'date', title: "{{ __('messages.date') }}"},
                    {data: 'status', name: 'status', title: "{{ __('messages.status') }}"},
                    {data: 'action', name: 'action', orderable: false, searchable: false, title: "{{ __('messages.action') }}"},
                ],
                order: [[0, 'desc']],
                drawCallback: function(settings) {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                }
            });

            $('#status, #booking_date_range').change(function() {
                table.draw();
            });

            $('#search').keyup(function() {
                table.draw();
            });
        });
    </script>
    @endsection
</x-master-layout>
