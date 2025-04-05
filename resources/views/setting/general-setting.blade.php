{{ html()->form('POST', route('generalsetting'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->open() }}

{{ html()->hidden('id',$generalsetting->id ?? null)->class('form-control')->placeholder('id') }}
{{ html()->hidden('page')->value($page)->class('form-control')->placeholder('id') }}
<div class="row">
    <div class="col-lg-6">

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.name') }}</label>
            <div class="col-sm-12">
                {{ html()->text('site_name', $generalsetting->site_name ?? '')->class('form-control')->placeholder(__('messages.site_name'))->id('site_name') }}

            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.description') }}</label>
            <div class="col-sm-12">
                {{ html()->textarea('site_description', $generalsetting->site_description ?? '')->class('form-control textarea')->rows(3)->placeholder(__('messages.site_description')) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.email') }}</label>
            <div class="col-sm-12">
                {{ html()->email('inquriy_email', $generalsetting->inquriy_email ?? '')->class('form-control')->placeholder(__('messages.inquriy_email')) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.phone') }}</label>
            <div class="col-sm-12">
                {{ html()->text('helpline_number', $generalsetting->helpline_number ?? '')->class('form-control helpline_number')->placeholder(__('messages.helpline_number')) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.website') }}</label>
            <div class="col-sm-12">
                {{ html()->text('website', $generalsetting->website ?? '')->class('form-control website')->placeholder(__('messages.website')) }}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            {{ html()->label(__('messages.country'))->for('country_id')->class('form-control-label col-sm-6') }}
            <div class="col-sm-12">
                {{ html()->select('country_id', [], $generalsetting->country_id)->class('select2js form-group country')->id('country_id')->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.country')])) }}
            </div>

        </div>

        <div class="form-group">
            {{ html()->label(__('messages.state'))->for('state_id')->class('form-control-label col-sm-6') }}

            <div class="col-sm-12">
                {{ html()->select('state_id', [], $generalsetting->state_id)->class('select2js form-group state_id')->id('state_id')->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.state')])) }}
            </div>
        </div>

        <div class="form-group">
            {{ html()->label(__('messages.city'))->for('city_id')->class('form-control-label col-sm-6') }}

            <div class="col-sm-12">
                {{ html()->select('city_id', [], $generalsetting->city_id)->class('select2js form-group city_id')->id('city_id')->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.city')])) }}
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.zipcode') }}</label>
            <div class="col-sm-12">
                {{ html()->text('zipcode', $generalsetting->zipcode ?? '')->class('form-control')->placeholder(__('messages.zipcode')) }}
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-6 form-control-label">{{ __('messages.address') }}</label>
            <div class="col-sm-12">
                {{ html()->textarea('address', $generalsetting->address ?? '')->class('form-control textarea')->rows(3)->placeholder(__('messages.address')) }}
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <div class="col-md-offset-3 col-sm-12 ">
                {{ html()->submit(__('messages.save'))->class('btn btn-md btn-primary float-md-right') }}
            </div>
        </div>
    </div>
</div>
{{ html()->form()->close() }}
<script>
    $(document).on('keyup', '.helpline_number', function() {
        var contactNumberInput = document.getElementById('helpline_number');
        var inputValue = contactNumberInput.value;
        inputValue = inputValue.replace(/[^0-9+\- ]/g, '');
        if (inputValue.length > 15) {
            inputValue = inputValue.substring(0, 15);
        }
        contactNumberInput.value = inputValue;
        if (inputValue.match(/^[0-9+\- ]+$/)) {
            $('.helpline_number').text('');
        } else {
            $('.helpline_number').text('Please enter a valid mobile number');
        }
    });

    $(document).ready(function() {
        loadCountry();
        var state_id = "{{ isset($generalsetting->state_id) ? $generalsetting->state_id : '' }}";
        var city_id = "{{ isset($generalsetting->city_id) ? $generalsetting->city_id : '' }}";

        stateName(country_id, state_id);
        $(document).on('change', '#country_id', function() {
            var country = $(this).val();
            $('#state_id').empty();
            $('#city_id').empty();
            stateName(country, state_id);
        })
        $(document).on('change', '#state_id', function() {
            var state = $(this).val();
            $('#city_id').empty();
            cityName(state, city_id);
        })
    })

    function loadCountry() {
        var country_id = "{{ isset($generalsetting->country_id) ? $generalsetting->country_id : '' }}";
        var country_route = "{{ route('ajax-list', ['type' => 'country']) }}";
        country_route = country_route.replace('amp;', '');

        $.ajax({
            url: country_route,
            success: function(result) {
                $('#country_id').select2({
                    width: '100%',
                    placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.state')]) }}",
                    data: result.results
                });

                if (country_id != null) {
                    $("#country_id").val(country_id).trigger('change');
                }
            }
        });
    }

    function stateName(country, state = "") {
        var state_route = "{{ route('ajax-list', ['type' => 'state', 'country_id' => '']) }}" + country;
        state_route = state_route.replace('amp;', '');

        $.ajax({
            url: state_route,
            success: function(result) {
                $('#state_id').select2({
                    width: '100%',
                    placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.state')]) }}",
                    data: result.results
                });
                if (state != null || state != 0) {
                    $("#state_id").val(state).trigger('change');
                }
            }
        });
    }

    function cityName(state, city = "") {
        var city_route = "{{ route('ajax-list', ['type' => 'city', 'state_id' => '']) }}" + state;
        city_route = city_route.replace('amp;', '');

        $.ajax({
            url: city_route,
            success: function(result) {
                $('#city_id').select2({
                    width: '100%',
                    placeholder: "{{ trans('messages.select_name', ['select' => trans('messages.city')]) }}",
                    data: result.results
                });
                if (city != null || city != 0) {
                    $("#city_id").val(city).trigger('change');
                }
            }
        });
    }
</script>

