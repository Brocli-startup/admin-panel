<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>


<div class="col-md-12">
    <div class="row ">
        <div class="col-md-3">
            <div class="user-sidebar">
                <div class="user-body user-profile text-center mx-0 px-0">
                    <div class="user-img">
                        <img class="rounded-circle avatar-90 image-fluid profile_image_preview"
                            src="{{ getSingleMedia($user_data,'profile_image', null) }}" alt="profile-pic">
                    </div>
                    <div class="sideuser-info">
                        <span class="mb-2">{{ $user_data->first_name. ' ' .$user_data->last_name }}</span>
                        <!-- <a>{{ $user_data->email }}</a> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="user-content">
                {{ html()->form('POST', route('updateProfile'))
                ->attribute('data-toggle', 'validator')
                ->attribute('enctype', 'multipart/form-data')
                ->id('user-form')
                ->open() }}
            
            <input type="hidden" name="profile" value="profile">
            {{ html()->hidden('username') }}
            {{ html()->hidden('email') }}
            {{ html()->hidden('id', $user_data->id ?? null)
                ->placeholder('id')
                ->class('form-control') }}
                <div class="row ">

                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.first_name') . ' <span class="text-danger">*</span>')
                            ->class('form-control-label')
                            ->for('first_name')
                             }}
                        {{ html()->text('first_name',$user_data->first_name)
                            ->placeholder(__('messages.first_name'))
                            ->class('form-control')
                            ->required() }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.last_name') . ' <span class="text-danger">*</span>')
                            ->class('form-control-label')
                            ->for('last_name')
                             }}
                        {{ html()->text('last_name', $user_data->last_name)
                            ->placeholder(__('messages.last_name'))
                            ->class('form-control')
                            ->required() }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.username') . ' <span class="text-danger">*</span>')
                            ->class('form-control-label')
                            ->for('username')
                             }}
                        {{ html()->text('username',$user_data->username)
                            ->placeholder(__('messages.username'))
                            ->class('form-control')
                            ->required() }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>
                    @if(auth()->user()->hasRole('provider'))
                        <div class="form-group col-md-6">
                            {{ html()->label(__('messages.designation') . ' <span class="text-danger">*</span>')
                                ->class('form-control-label')
                                ->for('designation')
                                 }}
                            {{ html()->text('designation', $user_data->designation)
                                ->placeholder(__('messages.designation'))
                                ->class('form-control')
                                ->required() }}
                            <small class="help-block with-errors text-danger"></small>
                        </div>
                    @endif
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.select_name', ['select' => __('messages.country')]), 'country_id')->class('form-control-label') }}
                        <br />
                        {{ html()->select('country_id', [optional($user_data->country)->id => optional($user_data->country)->name], optional($user_data->country)->id)
                            ->class('form-group select2js country')
                            ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.country')]))
                            ->attribute('data-ajax--url', route('ajax-list', ['type' => 'country'])) }}
                    </div>
                    
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.select_name', ['select' => __('messages.state')]), 'state_id')->class('form-control-label') }}
                        <br />
                        {{ html()->select('state_id', [optional($user_data->state)->id => optional($user_data->state)->name], optional($user_data->state)->id)
                            ->class('form-group select2js state_id')
                            ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.state')])) }}
                    </div>
                    
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.select_name', ['select' => __('messages.city')]), 'city_id')->class('form-control-label') }}
                        <br />
                        {{ html()->select('city_id', [optional($user_data->city)->id => optional($user_data->city)->name], optional($user_data->city)->id)
                            ->class('form-group select2js city_id')
                            ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.city')])) }}
                    </div>
                    
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.email') . ' <span class="text-danger">*</span>', 'email')->class('form-control-label') }}
                        {{ html()->email('email',$user_data->email)
                            ->placeholder(__('messages.email'))
                            ->class('form-control')
                            ->required()
                            ->attribute('pattern', '[^@]+@[^@]+\.[a-zA-Z]{2,}')
                            ->attribute('title', 'Please enter a valid email address') }}
                        <small class="help-block with-errors text-danger"></small>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="mobile" class="form-control-label">{{ __('messages.contact_number') }} <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text px-0"><i class="ph ph-phone"></i></span>
                            {{ html()->text('contact_number', old('contact_number', $user_data->contact_number))
                                ->id('contact_number')
                                ->class('form-control')
                                ->placeholder(__('messages.contact_number'))
                                ->required()
                                ->attribute('pattern', '[0-9]{10}')
                                ->attribute('oninput', 'this.value = this.value.replace(/[^0-9]/g, \'\')') }}
                            <div class="invalid-feedback" id="contact_number-error">Contact number field is required.</div>
                        </div>
                    </div>           

                    @if(auth()->user()->hasRole('handyman'))

                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.select_name', ['select' => __('messages.handymantype')]) . ' <span class="text-danger">*</span>', 'handymantype_id')
                            ->class('form-control-label') }}
                        <br />
                        {{ html()->select('handymantype_id', [optional($user_data->handymantype)->id => optional($user_data->handymantype)->name], optional($user_data->handymantype)->id)
                            ->class('select2js form-group handymantype')
                            ->required()
                            ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.handymantype')]))
                            ->attribute('data-ajax--url', route('ajax-list', ['type' => 'handymantype'])) }}
                    </div>
                
                    <div class="form-group col-md-6">
                        {{ html()->label(__('messages.select_name', ['select' => __('messages.provider_address')]), 'name')
                            ->class('form-control-label') }}
                        <br />
                        {{ html()->select('service_address_id', [optional($user_data->handymanAddressMapping)->id => optional($user_data->handymanAddressMapping)->address], $user_data->service_address_id)
                            ->class('select2js form-group service_address_id')
                            ->id('service_address_id')
                            ->attribute('data-ajax--url', route('ajax-list', ['type' => 'provider_address', 'provider_id' => $user_data->provider_id]))
                            ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.provider_address')])) }}
                    </div>
                @endif
                
                <div class="form-group col-md-6">
                    {{ html()->label(__('messages.status') . ' <span class="text-danger">*</span>', 'status')
                        ->class('form-control-label') }}
                    {{ html()->select('status', ['1' => __('messages.active'), '0' => __('messages.inactive')],$user_data->status)
                        ->class('form-control select2js')
                        ->required() }}
                </div>
                
                <div class="form-group col-md-6">
                    {{ html()->label(__('messages.choose_profile_image'), 'profile_image')
                        ->class('form-control-label') }}
                    <div class="custom-file">
                        {{ html()->file('profile_image')
                            ->class('custom-file-input custom-file-input-sm detail')
                            ->id('profile_image')
                            ->attribute('accept','image/*') }}                        
                        <label class="custom-file-label upload-label" id="imagelabel"
                        for="profile_image">{{ __('messages.profile_image') }}</label>                    
                    </div>
                </div>
                
                <div class="form-group col-md-12">
                    {{ html()->label(__('messages.address'), 'address')
                        ->class('form-control-label') }}
                    {{ html()->textarea('address',$user_data->address)
                        ->class('form-control textarea')
                        ->rows(2)
                        ->placeholder(__('messages.address')) }}
                </div>
                
                  @if($user_data->user_type =='provider')   

                <div class="form-group col-md-12 mt-4">
                    <h4>{{ __('messages.why_choose_me') }}</h4>
                </div>
            
                <div class="form-group col-md-12">
                    {{ html()->label(__('messages.title'))->class('form-control-label')->for('title') }}
                    {{ html()->text('title', $user_data->title)
                        ->class('form-control')
                        ->placeholder(__('messages.title'))
                    }}
                    <small class="help-block with-errors text-danger"></small>
                </div>
            
                <div class="form-group col-md-12">
                    {{ html()->label(__('messages.description'))->class('form-control-label')->for('about_description') }}
                    {{ html()->textarea('about_description',$user_data->about_description)
                        ->class('form-control textarea')
                        ->rows(2)
                        ->placeholder(__('messages.description'))
                    }}
                </div>
            
                

                @if($user_data->reason != null)

                    @foreach($user_data->reason as $reason)
                     <div class="form-section1 form-group col-md-12 ">
                            <div class="row">
                                <div class="form-group col-md-12 d-flex">
                                    {{ html()->text('reason[]', $reason)
                                        ->placeholder(__('messages.reason'))
                                        ->class('form-control')
                                    }}
                                    <small class="help-block with-errors text-danger"></small>
                                    <div class="form-group col-3 mb-0 align-self-center">
                                        <button class="remove-section1 button-custom button-remove" data-title="remove" title="Remove">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    @endforeach

                @endif
            
                <div class="form-section form-group col-md-12 ">
                    {{ html()->label(__('messages.reason'))->class('form-control-label')->for('reason') }}
                    <div class="row">
                        <div class="form-group col-md-12 d-flex">
                            {{ html()->text('reason[]')
                                ->placeholder(__('messages.reason'))
                                ->class('form-control')
                            }}
                            <small class="help-block with-errors text-danger"></small>

                            <div class="form-group mb-0 col-3 align-self-center">
                                
                                <button class="remove-section  button-custom button-remove"> <i class="far fa-trash-alt"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="form-group col-md-12">
                    <div class="form-group row">
                        <div class="col-md-9 text-md-right pe-1">
                            <button type="button" id="add-section" class="button-custom button-added">
                                <i class="fas fa-plus me-2"></i>Add More Reason
                            </button>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
                @endif

                    <div class="col-md-12">
                    {{ html()->submit(__('messages.update'))->class('btn btn-md btn-primary float-md-end') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// (function($) {
// 	"use strict";
$(document).ready(function() {
    $('.select2js').select2({
        width: '100%',
        // dropdownParent: $(this).parent()
    });
    var country_id = "{{ isset($user_data->country_id) ? $user_data->country_id : 0 }}";
    var state_id = "{{ isset($user_data->state_id) ? $user_data->state_id : 0 }}";
    var city_id = "{{ isset($user_data->city_id) ? $user_data->city_id : 0 }}";

    stateName(country_id, state_id);
    $(document).on('change', '#country_id', function() {
        var country = $(this).val();
        $('#state_id').empty();
        $('#city_id').empty();
        stateName(country);
    })
    $(document).on('change', '#state_id', function() {
        var state = $(this).val();
        $('#city_id').empty();
        cityName(state, city_id);
    })

     $(document).ready(function () {
        // Add Section
        $("#add-section").click(function () {
            var newSection = $(".form-section:first").clone();
            newSection.find('input').val(''); // Clear input values
            $(".form-section:last").after(newSection);
            updateRemoveButtonVisibility();
        });

        // Remove Section
        $(document).on('click', '.remove-section', function () {
            if ($(".form-section").length > 1) {
                $(this).closest('.form-section').remove();
                updateRemoveButtonVisibility();
            }
        });

          // Remove Section
        $(document).on('click', '.remove-section1', function () {
            
         $(this).closest('.form-section1').remove();
            
        });

        // Function to update Remove button visibility
        function updateRemoveButtonVisibility() {
            if ($(".form-section").length > 1) {
                $('.remove-section').show();
            } else {
                $('.remove-section').hide();
            }
        }

        // Initially hide Remove button if there's only one section
        updateRemoveButtonVisibility();
    });

    // $(document).on('keyup', '#contact_number', function() {
    //     var input = document.querySelector("#contact_number");
    //     var iti = window.intlTelInput(input, {
    //         initialCountry: "in", 
    //         separateDialCode: true, 
    //         utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" // To handle number formatting
    //     });
    //     var inputValue = input.value.replace(/[^0-9+\- ]/g, '');
    //     if (inputValue.length > 15) {
    //         inputValue = inputValue.substring(0, 15);
    //         $('#contact_number_err').text('Contact number should not exceed 15 characters');
    //     } else {
    //         $('#contact_number_err').text('');
    //     }
    //     input.value = inputValue;
    //     if (inputValue.match(/^[0-9+\- ]+$/)) {
    //         $('#contact_number_err').text('');
    //     } else {
    //         $('#contact_number_err').text('Please enter a valid mobile number');
    //     }
    // });


    function stateName(country, state = "") {
        var state_route = "{{ route('ajax-list', [ 'type' => 'state','country_id' =>'']) }}" + country;
        state_route = state_route.replace('amp;', '');

        $.ajax({
            url: state_route,
            success: function(result) {
                $('#state_id').select2({
                    width: '100%',
                    placeholder: "{{ trans('messages.select_name',['select' => trans('messages.state')]) }}",
                    data: result.results
                });
                if (state != null) {
                    $("#state_id").val(state).trigger('change');
                }
            }
        });
    }

    function cityName(state, city = "") {
        var city_route = "{{ route('ajax-list', [ 'type' => 'city' ,'state_id' =>'']) }}" + state;
        city_route = city_route.replace('amp;', '');

        $.ajax({
            url: city_route,
            success: function(result) {
                $('#city_id').select2({
                    width: '100%',
                    placeholder: "{{ trans('messages.select_name',['select' => trans('messages.city')]) }}",
                    data: result.results
                });
                if (city != null || city != 0) {
                    $("#city_id").val(city).trigger('change');
                }
            }
        });
    }
    $(document).on('change', '#profile_image', function() {
        readURL(this);
    })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            var res = isImage(input.files[0].name);

            if (res == false) {
                var msg = "{{ __('messages.image_png_gif') }}";
                Snackbar.show({
                    text: msg,
                    pos: 'bottom-center',
                    backgroundColor: '#d32f2f',
                    actionTextColor: '#fff'
                });
                return false;
            }

            reader.onload = function(e) {
                $('.profile_image_preview').attr('src', e.target.result);
                $("#imagelabel").text((input.files[0].name));
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(document).ready(function() {

        var currentImage = "{{ getSingleMedia($user_data,'profile_image', null) }}";


        if (currentImage !== "") {

            var fileName = currentImage.split('/').pop();

            $('#imagelabel').text(fileName);
        }
    });


    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return true;
        }
        return false;
    }

    // Initialize intl-tel-input for the contact number
    var input = document.querySelector("#contact_number");
    var iti = window.intlTelInput(input, {
        initialCountry: "in", // Set the default country code
        separateDialCode: true, // Show the dial code separately
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js" // To handle number formatting
    });

    // Update the contact number input on change
    $(input).on('change', function() {
        var number = iti.getNumber(); // Get the full number with country code
        console.log("Entered number: ", number); // Log the number
        $('#contact_number').val(number); // Update the input value
    });

    // Handle form submission
    $('#user-form').on('submit', function(e) {
        if (!iti.isValidNumber()) {
            e.preventDefault(); // Prevent form submission if the number is invalid
            $('#contact_number-error').text('Please enter a valid mobile number.').show(); // Show error message
        } else {
            $('#contact_number-error').hide(); // Hide error message if valid
        }
    });
})
// })(jQuery);
</script>