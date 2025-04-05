<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('subcategory list'))
                            <a href="{{ route('subcategory.index') }}" class=" float-end btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ html()->form('POST', route('subcategory.store'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle', 'validator')->id('subcategory')->open()}}
                        {{ html()->hidden('id',$subcategory->id ?? null) }}

                @include('partials._language_toggale')
                @foreach($language_array as $language)
                    <div id="form-language-{{ $language['id'] }}" class="language-form" style="display: {{ $language['id'] == app()->getLocale() ? 'block' : 'none' }};">
                        <div class="row">
                        @foreach(['name' => __('messages.name'), 'description' => __('messages.description')] as $field => $label)
                        <div class="form-group col-md-{{ $field === 'name' ? '4' : '12' }}">
                            {{ html()->label($label . ($field === 'name' ? ' <span class="text-danger">*</span>' : ''), $field)->class('form-control-label language-label') }}

                            @php
                               if ($language['id'] === 'en') {
                                    // Use the English value for 'en' language
                                    $value = $subcategory ? $subcategory->$field : null;
                                } else {
                                    // Use the translation value for other languages, or set null if not available
                                    $value = $subcategory && $subcategory->translate($field, $language['id']) 
                                        ? $subcategory->translate($field, $language['id']) 
                                        : null;
                                }
                                // Set the input name
                                $name = $language['id'] == 'en' ? $field : "translations[{$language['id']}][$field]";
                            @endphp

                            @if($field === 'name')
                                {{ html()->text($name, $value)
                                    ->placeholder($label)
                                    ->class('form-control')
                                    ->attribute('title', 'Please enter alphabetic characters and spaces only')
                                    ->attribute('data-required', 'true') }}
                            @else
                                {{ html()->textarea($name, $value)
                                    ->class('form-control textarea')
                                    ->rows(3)
                                    ->placeholder($label) }}
                            @endif

                            <small class="help-block with-errors text-danger"></small>
                        </div>
                        @endforeach

                        <!-- Category Selection -->
                        <div class="form-group col-md-4">
                            {{ html()->label(__('messages.select_name', ['select' => __('messages.category')]) . ' <span class="text-danger">*</span>', 'category_id')->class('form-control-label') }}
                            <select name="category_id"
                                    id="category_id_{{ $language['id'] }}"
                                    class="form-control select2js-category"
                                    data-select2-type="category"
                                    data-selected-id="{{ $subcategory->category_id ?? '' }}"
                                    data-language-id="{{ $language['id'] }}"
                                    data-ajax--url="{{ route('ajax-list', ['type' => 'category', 'language_id' => $language['id']]) }}"
                                    data-placeholder="{{ __('messages.select_name', ['select' => __('messages.category')]) }}" >
                            </select>
                            <small class="help-block with-errors text-danger"></small>
                        </div>
                        </div>
                    </div>
                @endforeach

                            
                            <div class="form-group col-md-4">
                                {{ html()->label(trans('messages.status').' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                {{ html()->select('status', ['1' => __('messages.active'), '0' => __('messages.inactive')],$subcategory->status)->class('form-control select2js')->required()}}
                            </div>
                    
                            <div class="form-group col-md-4">
                                <label class="form-control-label" for="subcategory_image">{{ __('messages.image') }} <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" name="subcategory_image" class="custom-file-input" onchange="previewImage(event)" accept="image/*" required>
                                    @if($subcategory && getMediaFileExit($subcategory, 'subcategory_image'))
                                        <label class="custom-file-label upload-label">{{ $subcategory->getFirstMedia('subcategory_image')->file_name }}</label>
                                    @else
                                        <label class="custom-file-label upload-label">{{ __('messages.choose_file', ['file' => __('messages.image')]) }}</label>
                                    @endif
                                </div>
                            </div>
                    
                            <div class="col-md-2 mb-2">
                                <div class="image-preview-container">
                                    <img id="subcategory_image_preview" src="{{ getMediaFileExit($subcategory, 'subcategory_image') ? getSingleMedia($subcategory, 'subcategory_image') : '' }}" alt="Image preview" class="attachment-image mt-1" style="width: 150px; {{ getMediaFileExit($subcategory, 'subcategory_image') ? '' : 'display: none;' }}">
                                    <a class="text-danger remove-file" id="removeButton" onclick="removeImage(event, '{{ route('remove.file', ['id' => $subcategory->id, 'type' => 'subcategory_image']) }}')" style="{{ getMediaFileExit($subcategory, 'subcategory_image') ? 'display: inline;' : 'display: none;' }}">
                                        <i class="ri-close-circle-line"></i>
                                    </a>
                                </div>
                            </div>
                    
                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="custom-control custom-switch custom-control-inline">
                                    {{ html()->checkbox('is_featured', $subcategory->is_featured)
                                        ->class('custom-control-input')
                                        ->id('is_featured')
                                    }}
                                    <label class="custom-control-label" for="is_featured">{{ __('messages.set_as_featured') }}</label>
                                </div>
                            </div>
                        </div>
                    
                        {{ html()->submit(trans('messages.save'))->class('btn btn-md btn-primary float-end')->id('saveButton') }}
                        {{ html()->form()->close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('bottom_script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        function previewImage(event) {
            const preview = document.getElementById('subcategory_image_preview');
            const fileLabel = document.querySelector('.custom-file-label');
            const saveButton = document.getElementById('saveButton');
            const removeButton = document.getElementById('removeButton');

            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block'; // Show the image
            fileLabel.textContent = event.target.files[0].name; // Update label with the file name

            // Show the remove button and enable the save button
            removeButton.style.display = 'inline';
            saveButton.disabled = false;
        }

        function removeImage(event, removeUrl) {
            event.preventDefault(); // Prevent default link behavior

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to remove the subcategory image?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    const preview = document.getElementById('subcategory_image_preview');
                    const fileLabel = document.querySelector('.custom-file-label');
                    const saveButton = document.getElementById('saveButton');
                    const removeButton = document.getElementById('removeButton');

                    // AJAX request to remove the media file
                    $.ajax({
                        url: removeUrl,
                        type: 'POST',
                        success: function(result) {
                            // Handle success
                            preview.src = '';
                            preview.style.display = 'none';
                            document.querySelector('input[name="subcategory_image"]').value = ''; // Clear the file input
                            fileLabel.textContent = '{{ __('messages.choose_file', ['file' => __('messages.image')]) }}'; // Reset the label text
                            saveButton.disabled = true; // Disable the save button
                            removeButton.style.display = 'none'; // Hide the remove button

                            // Optionally show a success message
                            Swal.fire(
                                'Deleted!',
                                'Your subcategory image has been removed.',
                                'success'
                            );
                        },
                        error: function(xhr, status, error) {
                            console.error('Error removing media file:', error);
                        }
                    });
                }
            });
        }

        function removeLocalImage() {
            const preview = document.getElementById('subcategory_image_preview');
            const fileLabel = document.querySelector('.custom-file-label');
            const saveButton = document.getElementById('saveButton');
            const removeButton = document.getElementById('removeButton');

            // Check if the image exists before removing
            if (preview.src) {
                preview.src = '';
                preview.style.display = 'none';
                document.querySelector('input[name="subcategory_image"]').value = ''; // Clear the file input
                fileLabel.textContent = '{{ __('messages.choose_file', ['file' => __('messages.image')]) }}'; // Reset the label text
                saveButton.disabled = true; // Disable the save button
                removeButton.style.display = 'none'; // Hide the remove button
            }
        }

        document.addEventListener('DOMContentLoaded', function() { 
            checkImage();
        });

        function checkImage() { 
            var id = @json($subcategory->id); 
            var route = "{{ route('check-image', ':id') }}";
            route = route.replace(':id', id);  
            var type = 'subcategory';

            $.ajax({
                url: route,
                type: 'GET',   
                data: {
                    type: type,   
                }, 
                success: function(result) {  
                    var attachments = result.results;   
                    var attachmentsCount = Object.keys(attachments).length; 
                    if (attachmentsCount == 0) { 
                        $('input[name="subcategory_image"]').attr('required', 'required');
                        document.getElementById('saveButton').disabled = true; // Disable button initially
                    } else { 
                        $('input[name="subcategory_image"]').removeAttr('required');
                        document.getElementById('saveButton').disabled = false; // Enable if there's an image
                    }          
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);  
                }
            });
        }


//         $(document).ready(function () {
//     /**
//      * Initialize Select2 for a given element
//      * @param {jQuery} $element - The jQuery object for the select box
//      */
//     function initializeSelect2($element) {
//         const selectedId = $element.data('selected-id'); // Get the preselected ID
//         const ajaxUrl = $element.data('ajax--url');
//         const placeholder = $element.data('placeholder');

//         $element.select2({
//             placeholder: placeholder,
//             ajax: {
//                 url: ajaxUrl,
//                 dataType: 'json',
//                 delay: 250,
//                 data: function (params) {
//                     return {
//                         q: params.term, // Search term
//                     };
//                 },
//                 processResults: function (data) {
//                     return {
//                         results: data.map(function (item) {
//                             return { id: item.id, text: item.text };
//                         }),
//                     };
//                 },
//                 cache: true,
//             },
//         });

//         // Preselect the value during edit
//         if (selectedId) {
//             $.ajax({
//                 url: ajaxUrl, // Fetch the preselected item
//                 data: { id: selectedId },
//                 dataType: 'json',
//                 success: function (response) {
//                     const selectedItem = response.find(item => item.id == selectedId);
//                     if (selectedItem) {
//                         // Create and append the selected option
//                         const option = new Option(selectedItem.text, selectedItem.id, true, true);
//                         $element.append(option).trigger('change');
//                     }
//                 },
//                 error: function () {
//                     console.error('Failed to fetch selected item for:', selectedId);
//                 },
//             });
//         }
//     }

//     /**
//      * Synchronize dropdowns of the same type across all languages
//      * @param {string} type - The type of dropdown (e.g., 'category', 'subcategory')
//      * @param {number} selectedId - The selected ID
//      */
//     function synchronizeDropdowns(type, selectedId) {
//         $(`.select2js-${type}`).each(function () {
//             const $dropdown = $(this);

//             // Fetch the translated value for the selected ID
//             $.ajax({
//                 url: $dropdown.data('ajax--url'),
//                 data: { id: selectedId },
//                 dataType: 'json',
//                 success: function (response) {
//                     const translatedItem = response.find(item => item.id == selectedId);
//                     if (translatedItem) {
//                         const option = new Option(translatedItem.text, translatedItem.id, true, true);
//                         $dropdown.empty().append(option).trigger('change');
//                     }
//                 },
//             });
//         });
//     }

//     // Initialize all Select2 dropdowns dynamically
//     $('[data-select2-type]').each(function () {
//         initializeSelect2($(this));
//     });

//     // Listen for changes and synchronize all dropdowns of the same type
//     $('[data-select2-type]').on('select2:select', function (e) {
//         const $dropdown = $(this);
//         const selectedId = e.params.data.id;
//         const type = $dropdown.data('select2-type');

//         synchronizeDropdowns(type, selectedId);
//     });

//     // Handle language toggle
//     $('.language-toggle').on('click', function () {
//         const languageId = $(this).data('language-id');
//         $('.language-form').hide();
//         $(`#form-language-${languageId}`).show();
//     });
// });




    </script>
    @endsection
</x-master-layout>
