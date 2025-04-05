<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-block card-stretch">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-3">
                            <h5 class="fw-bold">{{ $pageTitle ?? trans('messages.list') }}</h5>
                            @if($auth_user->can('category list'))
                            <a href="{{ route('category.index') }}" class=" float-end btn btn-sm btn-primary"><i class="fa fa-angle-double-left"></i> {{ __('messages.back') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {{ html()->form('POST', route('category.store'))
                        ->attribute('enctype', 'multipart/form-data')
                        ->attribute('data-toggle', 'validator')
                        ->id('category-form')
                        ->open()
                    }}
                    {{ html()->hidden('id', $categorydata->id ?? null) }}

                    @include('partials._language_toggale')

                    <!-- Loop through all languages -->
                    @foreach($language_array as $language)
                    <div id="form-language-{{ $language['id'] }}" class="language-form" style="display: {{ $language['id'] == app()->getLocale() ? 'block' : 'none' }};">
                        <div class="row">
                            @foreach(['name' => __('messages.name'), 'description' => __('messages.description')] as $field => $label)
                                <div class="form-group col-md-{{ $field === 'name' ? '4' : '12' }}">
                                    {{ html()->label($label . ($field === 'name' ? ' <span class="text-danger">*</span>' : ''), $field)->class('form-control-label language-label') }}
                                    
                                    @php
                                        $value = $language['id'] == 'en' 
                                            ? $categorydata ? $categorydata->$field : '' 
                                            : ($categorydata ? $categorydata->translate($field, $language['id']) : '');
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
                        </div>
                    </div>
                    @endforeach

                        <!-- Image Field -->
                        <div class="form-group col-md-4">
                            <label class="form-control-label" for="category_image">{{ __('messages.image') }} <span class="text-danger">*</span></label>
                            <div class="custom-file">

                                <input type="file" name="category_image" class="custom-file-input" id="category_image"
                                 accept="image/*"  {{ is_null($categorydata->id) ? 'required' : '' }}>

                                @if($categorydata && getMediaFileExit($categorydata, 'category_image'))
                                    <label class="custom-file-label upload-label">{{ $categorydata->getFirstMedia('category_image')->file_name }}</label>
                                @else
                                    <label class="custom-file-label upload-label">{{ __('messages.choose_file',['file' =>  __('messages.image') ]) }}</label>
                                @endif
                            </div>
                            <small class="help-block with-errors text-danger" id="image-error"></small>
                        </div>

                        <img id="category_image_preview" src="" width="150px" />

                        <!-- Status Field -->
                        <div class="form-group col-md-4">
                            {{ html()->label(trans('messages.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                            {{ html()->select('status', ['1' => __('messages.active'), '0' => __('messages.inactive')], $categorydata->status)->id('role')->class('form-control select2js')->required() }}
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <div class="custom-control custom-switch">
                                    {{ html()->checkbox('is_featured', $categorydata->is_featured)->class('custom-control-input')->id('is_featured') }}
                                    <label class="custom-control-label" for="is_featured">{{ __('messages.set_as_featured') }}</label>
                                </div>
                            </div>
                        </div>

                        {{ html()->submit(trans('messages.save'))
                        ->class('btn btn-md btn-primary float-end')
                        ->attribute('onclick', 'return checkData()')
                        ->id('saveButton') }}
                     {{ html()->form()->close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .language-btn.btn-primary {
            background-color: #007bff;
            color: white;
        }
    </style>

<!-- <script>
    // Set the primary locale (default language)
    const primarylanguageId = "{{ app()->getLocale() ?? 'en' }}";


    function toggleLanguageForm(languageId) {

        document.querySelectorAll('.language-form').forEach(function(form) {
            form.style.display = 'none';

        });
         document.getElementById('form-language-' + languageId).style.display = 'block';

        document.querySelectorAll('.language-btn').forEach(function(btn) {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-outline-secondary');
        });

        document.querySelector(`.language-btn[onclick*="${languageId}"]`).classList.add('btn-primary');


        setRequiredFields(languageId);
    }

    function setRequiredFields(languageId) {

        document.querySelectorAll('.language-form input, .language-form textarea').forEach(function(input) {
            input.removeAttribute('required');
        });

        document.querySelectorAll(`#form-language-${languageId} input, #form-language-${languageId} textarea`).forEach(function(input) {
            input.setAttribute('required', 'required');
        });

    }

    document.addEventListener('DOMContentLoaded', function() {
        setRequiredFields(0);
    });

    function checkData() {
    let isValid = true;

    const primaryLanguageSection = document.getElementById(`form-language-${primarylanguageId}`);

    if (primaryLanguageSection) {

        document.querySelectorAll(`#form-language-${primarylanguageId} input, #form-language-${primarylanguageId} textarea`).forEach(function(input) {
            input.setAttribute('required', 'required');
        });

        const requiredFields = primaryLanguageSection.querySelectorAll('input[required], textarea[required]');

        requiredFields.forEach(function(input) {

            if (!input.value) {

                console.log('input value:' + input.value )
                isValid = false;
                Snackbar.show({text: `Please complete the required fields in the primary language ${primarylanguageId}` ,pos: 'bottom-right',backgroundColor:'#d32f2f',actionTextColor:'#fff'});
                input.focus();
                return false;
            }
        });

    }

    return isValid;
}
    // Function to show a snackbar notification
    function showSnackbar(message) {
        const snackbar = document.getElementById("snackbar");
        snackbar.textContent = message;
        snackbar.className = "show";
        setTimeout(function() {
            snackbar.className = snackbar.className.replace("show", "");
        }, 3000);
    }
</script> -->
    @section('bottom_script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        function previewImage(event) {
            const preview = document.getElementById('category_image_preview');
            const fileLabel = document.querySelector('.custom-file-label');
            const saveButton = document.getElementById('saveButton');
            const removeButton = document.getElementById('removeButton');

            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block'; // Show the image
            fileLabel.textContent = event.target.files[0].name; // Update label with the file name

            // Show the remove button and enable the save button
            $('#removeButton').removeClass('d-none');
            saveButton.disabled = false;
        }

        function removeImage(event, removeUrl) {
            event.preventDefault(); // Prevent default link behavior

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to remove the category image?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    const preview = document.getElementById('category_image_preview');
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
                            document.querySelector('input[name="category_image"]').value = ''; // Clear the file input
                            fileLabel.textContent = '{{ __('messages.choose_file', ['file' => __('messages.image')]) }}'; // Reset the label text
                            saveButton.disabled = true; // Disable the save button
                            $('#removeButton').addClass('d-none'); // Hide the remove button

                            // Optionally show a success message
                            Swal.fire(
                                'Deleted!',
                                'Your category image has been removed.',
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
            const preview = document.getElementById('category_image_preview');
            const fileLabel = document.querySelector('.custom-file-label');
            const saveButton = document.getElementById('saveButton');
            const removeButton = document.getElementById('removeButton');

            // Check if the image exists before removing
            if (preview.src) {
                preview.src = '';
                preview.style.display = 'none';
                document.querySelector('input[name="category_image"]').value = ''; // Clear the file input
                fileLabel.textContent = '{{ __('messages.choose_file', ['file' => __('messages.image')]) }}'; // Reset the label text
                saveButton.disabled = true; // Disable the save button
                $('#removeButton').addClass('d-none'); // Hide the remove button
            }
        }

        function removeImage(event, removeUrl) {
    event.preventDefault(); // Prevent default link behavior

    // SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to remove the category image?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            const preview = document.getElementById('category_image_preview');
            const fileLabel = document.querySelector('.custom-file-label');
            const saveButton = document.getElementById('saveButton');
            const removeButton = document.querySelector('.remove-button'); // Get the remove button

            // AJAX request to remove the media file
            $.ajax({
                url: removeUrl,
                type: 'POST',
                success: function(result) {
                    // Handle success, e.g., show a success message
                    preview.src = '';
                    preview.style.display = 'none';
                    document.querySelector('input[name="category_image"]').value = ''; // Clear the file input
                    fileLabel.textContent = '{{ __('messages.choose_file', ['file' => __('messages.image')]) }}'; // Reset the label text
                    saveButton.disabled = true; // Disable the save button
                    // removeButton.style.display = 'none'; // Hide the remove button
                    $('#removeButton').addClass('d-none');
                    // Optionally show a success message
                    Swal.fire(
                        'Deleted!',
                        'Your category image has been removed.',
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





function removeLocalImage(event) {
    const preview = document.getElementById('category_image_preview');
    const fileLabel = document.querySelector('.custom-file-label');
    const saveButton = document.getElementById('saveButton');
    const removeButton = document.querySelector('.remove-button'); // Get the remove button

    preview.src = '';
    preview.style.display = 'none';
    document.querySelector('input[name="category_image"]').value = ''; // Clear the file input
    fileLabel.textContent = '{{ __('messages.choose_file', ['file' => __('messages.image')]) }}'; // Reset the label text

    // Disable save button if image is required and not present
    saveButton.disabled = true;

    // Hide the remove button
    $('#removeButton').addClass('d-none');
}



        document.addEventListener('DOMContentLoaded', function() {
            checkImage();
        });

        function checkImage() {
            var id = @json($categorydata->id);
            var route = "{{ route('check-image', ':id') }}";
            route = route.replace(':id', id);
            var type = 'category';

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
                        $('input[name="category_image"]').attr('required', 'required');
                        document.getElementById('saveButton').disabled = true; // Disable button initially
                    } else {
                        $('input[name="category_image"]').removeAttr('required');
                        document.getElementById('saveButton').disabled = false; // Enable if there's an image
                        $('#removeButton').removeClass('d-none');
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
