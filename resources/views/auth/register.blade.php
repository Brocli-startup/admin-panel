<x-guest-layout>
   <section class="login-content">
      <div class="container h-100">
         <div class="row align-items-center justify-content-center h-100">
            <div class="col-md-5">
               <div class="card p-3">
                  <div class="card-body">
                     <div class="auth-logo">
                        <a href="{{route('frontend.index')}}">
                           <img src="{{ getSingleMedia(imageSession('get'),'logo',null) }}" class="img-fluid rounded-normal" alt="logo">
                        </a>
                     </div>
                     <h3 class="mb-3 fw-bold text-center">{{__('auth.get_start')}}</h3>
                     <!-- Session Status -->
                     <x-auth-session-status class="mb-4" :status="session('status')" />

                     <!-- Validation Errors -->
                     <x-auth-validation-errors class="mb-4" :errors="$errors" />
                     <form method="POST" action="{{ route('register') }}" data-bs-toggle="validator">
                        {{csrf_field()}}
                        <div class="row">
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="username" class="text-secondary">{{__('auth.username')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" id="username" name="username" value="{{old('username')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.username') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="first_name" class="text-secondary">{{__('auth.first_name')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.first_name') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="last_name" class="text-secondary">{{__('auth.last_name')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.last_name') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="email" class="text-secondary">{{__('auth.email')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" type="email" id="email" name="email" value="{{old('email')}}" required placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.email') ]) }}" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="password" class="text-secondary">{{__('auth.login_password')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" type="password" id="password" name="password" required autocomplete="new-password" placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.login_password') ]) }}">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="password_confirmation" class="text-secondary">{{__('auth.confirm_password')}} <span class="text-danger">*</span></label>
                                 <input class="form-control" onkeyup="checkPasswordMatch()" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('auth.enter_name',[ 'name' => __('auth.confirm_password') ]) }}">
                                 <small class="help-block with-errors text-danger" id="confirm_passsword"></small>

                              </div>
                           </div>
                           <!-- User Type Selection -->
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="user_type" class="text-secondary">{{ __('messages.user_type') }} <span class="text-danger">*</span></label>
                                 <select name="usertype" class="form-control select2 mb-5" id="user_type" style="width:100%">
                                    <option value="provider">{{ __('messages.provider') }}</option>
                                    <option value="handyman">{{ __('messages.handyman') }}</option>
                                 </select>
                              </div>
                           </div>

                           <!-- Provider Section -->
                           <div class="col-lg-12" id="provider_section" style="display: none;">
                              <div class="form-group">
                                 <label for="providerdata" class="text-secondary">{{ __('messages.provider') }}</label>
                                 <select name="provider_id" class="form-control select2 mb-5" id="providerdata" style="width:100%">
                                    <option value="">{{ __('messages.select_provider') }}</option>
                                 </select>
                              </div>
                           </div>

                           <!-- Commission Section -->
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="user_commission" class="text-secondary">{{ __('messages.user_commission') }} <span class="text-danger">*</span></label>
                                 <select name="providertype_id" class="form-control select2 mb-5" id="providertype" style="width:100%">
                                    <option value="">{{ __('messages.select_provider_type') }}</option>
                                 </select>
                                 <select name="handymantype_id" class="form-control select2 mb-5 d-none" id="handymantype" style="width:100%">
                                    <option value="">{{ __('messages.select_handyman_type') }}</option>
                                 </select>
                              </div>
                           </div>

                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="designation" class="text-secondary">{{__('messages.designation')}}</label>
                                 <input type="text" id="designation" name="designation" class="form-control" placeholder="{{__('placeholder.designation')}}" aria-label="designation"
                                    aria-describedby="basic-addon6">
                              </div>
                           </div>
                           <div class="col-lg-12 mt-2">
                              <div class="form-check mb-3 d-flex align-items-center">
                                 <input type="checkbox" class="form-check-input mt-0" id="customCheck1" required>
                                 <label class="form-check-label ps-2" for="customCheck1">
                                    {{-- {{__('auth.agree')}} <a class="btn-link p-0 text-capitalize" href="{{ url('/') }}/term-conditions">{{__('auth.term_service')}}</a> &amp; <a class="btn-link p-0 text-capitalize" href="{{ url('/') }}/privacy-policy">{{__('auth.privacy_policy')}}</a> --}}
                                    {{ __('auth.agree') }}
                                       <a class="btn-link p-0 text-capitalize" href="{{ url('term-conditions') }}">
                                          {{ __('auth.term_service') }}
                                       </a> &amp;
                                       <a class="btn-link p-0 text-capitalize" href="{{ url('privacy-policy') }}">
                                          {{ __('auth.privacy_policy') }}
                                       </a>

                                    <small class="help-block with-errors text-danger"></small>
                                 </label>
                              </div>
                           </div>

                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-2 w-100" id="submit-btn">{{ __('auth.create_account') }}</button>
                        <div class="col-lg-12 mt-3">
                           <p class="mb-0 text-center">{{__('auth.already_have_account')}} <a class="btn-link p-0 text-capitalize" href="{{route('auth.login')}}">{{__('auth.sign_in')}}</a></p>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
         function checkPasswordMatch() {
            const password = $("#password").val();
            const confirmPassword = $("#password_confirmation").val();
            const errorElement = $("#confirm_passsword");
            const submitBtn = $("#submit-btn");

            if (password !== confirmPassword) {
               errorElement.text("{{ __('auth.password_mismatch_error') }}");
               submitBtn.prop("disabled", true);
            } else {
               errorElement.text("");
               submitBtn.prop("disabled", false);
            }
         }

         $(document).ready(function() {
    function fetchTypes(userType, providerId = null) {
        $.ajax({
            url: '{{ route("ajax-list") }}',
            type: 'GET',
            data: {
                type: userType === 'provider' ? 'providertype' : 'handymantype',
                provider_id: providerId // Include provider_id if available
            },
            success: function(response) {
                const providerDropdown = $('#providertype').toggleClass('d-none', userType !== 'provider');
                const handymanDropdown = $('#handymantype').toggleClass('d-none', userType !== 'handyman');

                if (response.status === 'true') {
                    const targetDropdown = userType === 'provider' ? providerDropdown : handymanDropdown;
                    targetDropdown.empty().append($('<option>', { value: '', text: userType === 'provider' ? '{{ __('messages.select_provider_type') }}' : '{{ __('messages.select_handyman_type') }}' }));

                    $.each(response.results, function(index, item) {
                        targetDropdown.append($('<option>', { value: item.id, text: item.text }));
                    });
                }
            },
            error: function() {
                console.error('Error fetching types');
            }
        });
    }

    function fetchProviders() {


        var baseURL = "{{ url('/') }}";
        $.ajax({
            url:baseURL + '/api/user-list',
            type: 'GET',
            data: { user_type: 'provider', status: 1, per_page: 25, page: 1 },
            success: function(response) {
                const providerData = $('#providerdata').empty().append($('<option>', { value: '', text: '{{ __('messages.select_provider') }}' }));

                if (response?.data?.length) {
                    $.each(response.data, function(index, item) {
                        providerData.append($('<option>', { value: item.id, text: item.first_name + ' ' + item.last_name }));
                    });
                } else {
                    providerData.append($('<option>', { value: '', text: '{{ __('messages.no_providers_found') }}' }));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching providers:', error);
            }
        });
    }

    $('#user_type').change(function() {
        const selectedUserType = $(this).val();
        fetchTypes(selectedUserType);
        $('#provider_section').toggle(selectedUserType === 'handyman'); // Show only when handyman is selected
        $('#providertype').val('');  // Clear provider type selection
        $('#handymantype').val('');  // Clear handyman type selection

        if (selectedUserType === 'handyman') {
            fetchProviders(); // Fetch provider list when handyman is selected
        }
    }).trigger('change');

    $('#providerdata').change(function() {
        if ($('#user_type').val() === 'handyman') {
            const providerId = $(this).val();

            fetchTypes('handyman', providerId); // Fetch handyman types based on selected provider
        }
    });
});

      </script>


   </section>
</x-guest-layout>
