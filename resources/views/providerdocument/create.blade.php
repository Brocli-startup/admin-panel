<x-master-layout>
    <div class="container-fluid">
    @include('partials._provider')
        <div class="row">
        <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('providerdocument list'))
                                <a href="{{ route('providerdocument.show',['providerdocument' => $providerdata->id]) }}" class=" float-end btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ html()->form('POST', route('providerdocument.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->id('provider_document')->open() }}
                            {{ html()->hidden('id',$provider_document->id ?? null) }}
                            <div class="row">
                                @if(auth()->user()->hasAnyRole(['admin','demo_admin']))
                                <div class="form-group col-md-4">
                                    {{ html()->label(__('messages.select_name', ['select' => __('messages.providers')]) . ' <span class="text-danger">*</span>', 'provider_id')
                                        ->class('form-control-label')
                                    }}
                                    <br />
                                    {{ html()->select('provider_id', [$providerdata->id => $providerdata->display_name], $providerdata->id)
                                        ->class('select2js form-group providers')
                                        ->required()
                                        ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.providers')]))
                                    }}
                                </div>
                                @endif
                    
                                @php
                                    $is_required = optional($provider_document->document)->is_required == 1 ? '*' : '';
                                @endphp
                    
                                <div class="form-group col-md-4">
                                    {{ html()->label(__('messages.select_name', ['select' => __('messages.document')]) . ' <span class="text-danger">* </span>', 'document_id')
                                        ->class('form-control-label')
                                    }}
                                    <br />
                                    {{ html()->select('document_id', [optional($provider_document->document)->id => optional($provider_document->document)->name." ".$is_required], optional($provider_document->document)->id)
                                        ->class('select2js form-group document_id')
                                        ->id('document_id')
                                        ->required()
                                        ->attribute('data-placeholder', __('messages.select_name', ['select' => __('messages.document')]))
                                        ->attribute('data-ajax--url', route('ajax-list', ['type' => 'documents']))
                                    }}
                                    <a href="{{ route('document.create') }}"><i class="fa fa-plus-circle mt-2"></i> {{ trans('messages.add_form_title', ['form' => trans('messages.document') ]) }}</a>
                                </div>
                    
                                @if(auth()->user()->hasAnyRole(['admin','demo_admin']))
                                <div class="form-group col-md-4">
                                    {{ html()->label(trans('messages.is_verify') . ' <span class="text-danger">*</span>', 'is_verified')
                                        ->class('form-control-label')
                                    }}
                                    {{ html()->select('is_verified', ['1' => __('messages.verified'), '0' => __('messages.unverified')], old('is_verified'))
                                        ->id('is_verified')
                                        ->class('form-control select2js')
                                        ->required()
                                    }}
                                </div>
                                @endif
                    
                                <div class="form-group col-md-4">
                                    {{ html()->label(__('messages.upload_document') . ' <span class="text-danger">*</span>', 'provider_document')
                                        ->class('form-control-label')
                                    }}
                                    <div class="custom-file">
                                        <input type="file" id="provider_document" name="provider_document" class="custom-file-input" @if(!$provider_document || !getMediaFileExit($provider_document, 'provider_document')) required @endif>
                                        @if($provider_document && getMediaFileExit($provider_document, 'provider_document'))
                                        <label class="custom-file-label upload-label">{{ $provider_document->getFirstMedia('provider_document')->file_name }}</label>
                                        @else
                                        <label class="custom-file-label upload-label">{{ __('messages.choose_file', ['file' =>  __('messages.document') ]) }}</label>
                                        @endif
                                    </div>
                                </div>
                    
                                @if(getMediaFileExit($provider_document, 'provider_document'))
                                    <div class="col-md-2 mb-2 position-relative">
                                        @php
                                            $file_extention = config('constant.IMAGE_EXTENTIONS');
                                            $image = getSingleMedia($provider_document,'provider_document');
                                            $extention = in_array(strtolower(imageExtention($image)), $file_extention);
                                        @endphp
                                        @if($extention)   
                                            <img id="provider_document_preview" src="{{ $image }}" alt="#" class="attachment-image mt-1">
                                        @else
                                            <img id="provider_document_preview" src="{{ asset('images/file.png') }}" class="attachment-file">
                                        @endif
                                        <a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $provider_document->id, 'type' => 'provider_document']) }}"
                                            data--submit="confirm_form"
                                            data--confirmation='true'
                                            data--ajax="true"
                                            title='{{ __("messages.remove_file_title", ["name" => __("messages.image") ]) }}'
                                            data-title='{{ __("messages.remove_file_title", ["name" => __("messages.image") ]) }}'
                                            data-message='{{ __("messages.remove_file_msg") }}'>
                                            <i class="ri-close-circle-line"></i>
                                        </a>
                                        <a href="{{ $image }}" class="d-block mt-2" download target="_blank"><i class="fas fa-download "></i> {{ __('messages.download') }}</a>
                                    </div>
                                @endif
                            </div>
                    
                            {{ html()->submit(trans('messages.save'))
                                ->class('btn btn-md btn-primary float-end')
                            }}
                        {{ html()->form()->close() }}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>  
    @section('bottom_script')
        <script type="text/javascript">
            (function($) {
                "use strict";
                    $(document).ready(function(){ 
                        $(document).on('change' , '#document_id' , function (){
                            var data = $('#document_id').select2('data')[0];
                           
                            if(data.is_required == 1)
                            {
                                $('#document_required').text('*');
                                $('#provider_document').attr('required');
                            } else {
                                $('#document_required').text('');
                                $('#provider_document').attr('required', false);
                            }
                        })
                    })
            })(jQuery);

    document.addEventListener('DOMContentLoaded', function() { 
    checkImage();
});
function checkImage() { 
    var id = @json($providerdata->id ); 
    var route = "{{ route('check-image', ':id') }}";
    route = route.replace(':id', id);  
    var type = 'provider_document';

    $.ajax({
        url: route,
        type: 'GET',   
        data: {
            type: type,   
        }, 
        success: function(result) {  
            var attachments = result.results;  

            if (attachments && attachments.length === 0) { 
                $('input[name="provider_document"]').attr('required', 'required');
            } else { 
                $('input[name="provider_document"]').removeAttr('required');
            }         
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);  
        }
    });
}
        </script>
    @endsection
</x-master-layout>