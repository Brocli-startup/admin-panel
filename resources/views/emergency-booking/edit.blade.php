<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle ?? __('messages.edit') . ' ' . __('messages.emergency_booking') }}</h5>
                            <a href="{{ route('emergency-booking.index') }}" class="float-end btn btn-sm btn-primary">
                                <i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ Form::model($bookingdata, ['method' => 'patch', 'route' => ['emergency-booking.update', $bookingdata->id], 'data-toggle' => "validator", 'id' => 'emergency-booking']) }}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    {{ Form::label('service_id', __('messages.select_name', ['select' => __('messages.service')]), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::select('service_id', [optional($bookingdata->service)->id => optional($bookingdata->service)->name], optional($bookingdata->service)->id, [
                                        'class' => 'form-control select2js',
                                        'required',
                                        'data-placeholder' => __('messages.select_name', ['select' => __('messages.service')]),
                                        'disabled'
                                    ]) }}
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    {{ Form::label('date', __('messages.date'), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::text('date', null, ['class' => 'form-control datetimepicker', 'required', 'placeholder' => __('messages.date')]) }}
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    {{ Form::label('status', __('messages.status'), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::select('status', [
                                        'pending' => __('messages.pending'),
                                        'accept' => __('messages.accepted'),
                                        'ongoing' => __('messages.ongoing'),
                                        'completed' => __('messages.completed'),
                                        'cancelled' => __('messages.cancelled'),
                                    ], null, ['class' => 'form-control select2js', 'required']) }}
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    {{ Form::label('provider_id', __('messages.select_name', ['select' => __('messages.provider')]), ['class' => 'form-label']) }}
                                    <span class="text-danger">*</span>
                                    {{ Form::select('provider_id', [optional($bookingdata->provider)->id => optional($bookingdata->provider)->display_name], optional($bookingdata->provider)->id, [
                                        'class' => 'form-control select2js',
                                        'required',
                                        'data-placeholder' => __('messages.select_name', ['select' => __('messages.provider')])
                                    ]) }}
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    {{ Form::label('description', __('messages.description'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('messages.description')]) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {{ Form::submit(__('messages.save'), ['class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('bottom_script')
    <script>
        $(document).ready(function() {
            $('.select2js').select2();
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                icons: {
                    up: 'fa fa-angle-up',
                    down: 'fa fa-angle-down',
                    previous: 'fa fa-angle-left',
                    next: 'fa fa-angle-right'
                }
            });
        });
    </script>
    @endsection
</x-master-layout>