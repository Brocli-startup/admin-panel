<x-guest-layout>
   <section class="login-content">
      <div class="container h-100">
         <div class="row align-items-center justify-content-center h-100">
            <div class="col-md-5">
               <div class="card p-3">
                  <div class="card-body">
                     <div class="auth-logo">
                        <a href="{{route('home')}}">
                           <img src="{{ getSingleMedia(imageSession('get'),'logo',null) }}" class="img-fluid rounded-normal" alt="logo">
                        </a>
                     </div>
                     <h3 class="mb-3 fw-bold text-center">{{__('auth.sign_in')}}</h3>
                     <p class="text-center text-secondary mb-4">{{__('auth.login_continue')}}</p>
                     <!-- Session Status -->
                     <x-auth-session-status class="mb-4" :status="session('status')" />

                     <!-- Validation Errors -->
                     <x-auth-validation-errors class="mb-4" :errors="$errors" />
                     <form method="POST" action="{{ route('login') }}" data-bs-toggle="validator">
                        {{csrf_field()}}
                        <div class="row">
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label class="text-secondary">{{__('auth.email')}} <span class="text-danger">*</span></label>
                                 <input id="email" name="email" value="{{request('email')}}" class="form-control" type="email" placeholder="{{ __('auth.enter_name',['name' => __('auth.email')]) }}" required autofocus>
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12 mt-2">
                              <div class="form-group">
                                 <div class="d-flex justify-content-between align-items-center">
                                    <label class="text-secondary">{{__('auth.login_password')}} <span class="text-danger">*</span></label>
                                    <!-- <label><a href="{{route('auth.recover-password')}}">{{__('auth.forgot_password')}}</a></label> -->
                                 </div>
                                 <input class="form-control" type="password" value="{{request('password')}}" placeholder="{{ __('auth.enter_name',['name' => __('auth.login_password') ]) }}" name="password" required autocomplete="current-password">
                                 <small class="help-block with-errors text-danger"></small>
                              </div>
                           </div>
                           <div class="col-lg-12 mb-2">
                              <div class="d-flex justify-content-end align-items-center">
                                 <!-- <label class="text-secondary">{{__('auth.login_password')}} <span class="text-danger">*</span></label> -->
                                 <a href="{{route('auth.recover-password')}}" class="btn-link p-0 text-capitalize"><i>{{__('auth.forgot_password')}}</i></a>
                              </div>
                           </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-2 w-100">{{ __('auth.login') }}</button>
                        <div class="text-center my-4 text-signup">
                           <label class="m-0 text-capitalize"> {{__('auth.dont_have_account')}}</label>
                           <a href="{{route('auth.register')}}" class="ms-1 btn-link align-baseline text-capitalize">{{__('auth.signup')}}</a>
                       </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</x-guest-layout>
