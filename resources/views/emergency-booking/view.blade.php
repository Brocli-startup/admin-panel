<x-master-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0 p-4">{{__('messages.emergency_booking_info')}}</h3>
                <ul class="nav nav-tabs pay-tabs tabslink payment-view-tabs mb-0" id="tab-text" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('emergency-booking.index') }}" 
                           class="nav-link active btn btn-sm btn-primary" 
                           style="min-width: 150px; text-align: center;">
                            <i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">{{ __('messages.booking_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.id') }}</div>
                                            <div class="fw-bold">#{{ $bookingdata->id }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.status') }}</div>
                                            <div class="fw-bold">{{ ucfirst($bookingdata->status) }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.date') }}</div>
                                            <div class="fw-bold">{{ $bookingdata->date }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.created_at') }}</div>
                                            <div class="fw-bold">{{ $bookingdata->created_at->format('Y-m-d H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">{{ __('messages.service_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.service') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->service)->name }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.category') }}</div>
                                            <div class="fw-bold">{{ optional(optional($bookingdata->service)->category)->name }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.price') }}</div>
                                            <div class="fw-bold">{{ getPriceFormat(optional($bookingdata->service)->price) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">{{ __('messages.customer_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.name') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->customer)->display_name }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.email') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->customer)->email }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.contact_number') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->customer)->contact_number }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">{{ __('messages.provider_info') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.name') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->provider)->display_name }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.email') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->provider)->email }}</div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="text-muted">{{ __('messages.contact_number') }}</div>
                                            <div class="fw-bold">{{ optional($bookingdata->provider)->contact_number }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-master-layout>