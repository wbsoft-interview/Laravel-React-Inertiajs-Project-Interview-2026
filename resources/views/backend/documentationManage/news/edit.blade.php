@extends('backend.master')
@section('title', 'Documentation | Master Template')
@section('documentation', 'active')
@section('documentation.index', 'active')

@section('styles')
    <!-- Include Select2 CSS here if not already loaded -->
    <style>
        /* simple styling for each tag chip */
        .tag-chip {
            display: inline-flex;
            align-items: center;
            background: #0d6efd;
            /* Bootstrap primary */
            color: #fff;
            border-radius: .25rem;
            padding: .25rem .5rem;
            margin: .125rem;
            font-size: .875rem;
        }

        .tag-chip .remove-btn {
            margin-left: .5rem;
            cursor: pointer;
            font-weight: bold;
        }

        .custom-time-picker {
            padding-top: 17px !important;
            padding-bottom: 17px !important;
        }

        .custom-date-picker {
            line-height: 0.5 !important;
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }


    </style>
@endsection

@section('main_content_section')
    <div class="row py-3 ps-2">
        <div class="heading d-flex justify-content-start align-items-center">
            <h3 class="mb-0"><span>Update Documentation</span></h3>
        </div>
    </div>

    <div class="app-content content">
        <form action="{{ route('documentation.update', $singleDocumentationData->id) }}" method="post" enctype="multipart/form-data"
            onsubmit="return checkValidate()" novalidate>
            @csrf
            @method('put')
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">

                                <!-- Title EN -->
                                <div class="col-md-12 mb-3">
                                    <label for="title_en">Title (EN) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_en" id="title_en" class="form-control" placeholder="Enter English Title"
                                        value="{{ $singleDocumentationData->title_en }}" required>
                                    @error('title_en')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- <div class="col-md-12 mb-3">
                                    <div class="">
                                        <label for="permalink">Permalink <span class="text-danger"></span></label>
                                        <input type="text" name="permalink" id="permalink" class="form-control" placeholder="Enter title"
                                            value="{{ $singleDocumentationData->permalink_slug }}">
                                        @error('permalink')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                            
                                <!-- Post EN -->
                                {{-- <div class="col-md-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <label for="post_en" class="form-label mb-0">Description (EN) <span class="text-danger">*</span></label>
                                        <div class="custom-add-media-block">
                                            <a href="javascript:void(0)" class="btn btn-success rounded-0 d-flex gap-1 align-items-center border-0"
                                                data-bs-toggle="modal" onclick="addMediaFPD('post_en')">
                                                <i class="fa fa-plus my-auto"></i>
                                                <span class="custom-add-media-button-text my-auto">New Photo</span>
                                            </a>
                                        </div>
                                    </div>
                                    <textarea id="post_en" name="post_en" class="form-control summernote ">{!! $singleDocumentationData->post_en !!}</textarea>
                                    @error('post_en')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                            
                                <!-- Post BN -->
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <label for="post_en" class="form-label mb-0">Description (BN) <span class="text-danger">*</span></label>
                                        <div class="custom-add-media-block">
                                            <a href="javascript:void(0)" class="btn btn-success rounded-0 d-flex gap-1 align-items-center border-0"
                                                data-bs-toggle="modal" onclick="addMediaFPD('post_en')">
                                                <i class="fa fa-plus my-auto"></i>
                                                <span class="custom-add-media-button-text my-auto">New Photo</span>
                                            </a>
                                        </div>
                                    </div>
                                    <textarea id="post_en" name="post_en" class="form-control summernote ">{!! $singleDocumentationData->post_en !!}</textarea>
                                    <span id="warnTextDesc1" class="text-danger warn d-none">Description is required</span>
                                    @error('post_en')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            
                                <!-- News Photo -->
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="name">News Photo <span class="text-danger"></span></label>
                                        <div class="position-relative custom-soft-setting dropzone">
                                            <div class="select_imgWith_preview py-2">
                                                @if(isset($singleDocumentationData) && $singleDocumentationData->photo != null)
                                                <img id="uploadPreview1" src="{{ isset($singleDocumentationData) && $singleDocumentationData?->photo ? Storage::url('uploads/documentationImg/' . $singleDocumentationData->photo) : asset('backend/template-assets/images/img_preview.png') }}">
                                                @else
                                                <img id="uploadPreview1" src="{{ asset('backend/template-assets/images/img_preview.png') }}">
                                                
                                                <div id="dropzone-block" class="custom-media-upload-block mt-3">
                                                    <span class="dropzone-label fw-bolder">Upload Photo</span>
                                                </div>
                                                @endif
                                                <input class="custom-max-width-100" id="uploadImage1" type="file" name="photo"
                                                    onchange="PreviewImageFP('uploadImage1','uploadPreview1');" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>

                            <!-- ACTION BUTTONS -->

                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="row">
                        <div class="mb-3 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="pb-0 mb-0">Others</h4>
                                </div>
                                <div class="card-body">
                                    <div class="body-section">
                                        <p class="d-flex gap-2 mb-1">
                                            <i class="fa fa-eye my-auto"></i>
                                            <span class="my-auto">Published By: <b id="publishedByText">--</b></span>
                                        </p>
                                
                                        <p class="d-flex gap-2 mb-1">
                                            <i class="fa fa-eye my-auto"></i>
                                            <span class="my-auto">Visibility: <b id="visibilityText">Published</b></span>
                                        </p>
                                
                                        <p class="d-flex gap-2 mb-1">
                                            <i class="fa fa-calendar my-auto"></i>
                                            <span class="my-auto">Published: <b id="publishDateTimeText">--</b></span>
                                        </p>
                                
                                        <p class="d-flex gap-2 mb-1">
                                            <i class="fa fa-calendar my-auto"></i>
                                            <span class="my-auto">Documentation Format: <b id="newsFormatText">Col 1</b></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <a href="javascript:void(0)" class="btn btn-primary py-2 my-1 w-100" data-bs-toggle="modal"
                                        data-bs-target="#modifyOthersInfo">
                                        Modify
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="pb-0 mb-0">Categories</h4>
                                </div>
                                <div class="card-body">
                                    <div class="categories-list">
                                        @foreach($newsCategoryData->whereNull('parent_category_id') as $category)
                                        {{-- Main Category --}}
                                        <div class="form-check mb-2">
                                            <input class="form-check-input category-parent" type="checkbox" id="category{{ $category->id }}"
                                                data-category-id="{{ $category->id }}" name="documentation_category_id[]" value="{{ $category->id }}"
                                                {{ in_array($category->id, old('documentation_category_id', $categoryIds ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category{{ $category->id }}">
                                                {{ $category->category_name_en }}
                                            </label>
                                        </div>
                                        
                                        {{-- Subcategories --}}
                                        @php
                                        $subcategories = $newsCategoryData->where('parent_category_id', $category->id);
                                        @endphp
                                        
                                        @if($subcategories->count())
                                        <div class="subcategory ps-4">
                                            @foreach($subcategories as $subcategory)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input category-child" type="checkbox" id="category{{ $subcategory->id }}"
                                                    data-parent-id="{{ $category->id }}" name="documentation_category_id[]" value="{{ $subcategory->id }}"
                                                    {{ in_array($subcategory->id, old('documentation_category_id', $categoryIds ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="category{{ $subcategory->id }}">
                                                    {{ $subcategory->category_name_en }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="pb-0 mb-0">Tags</h4>
                                </div>
                                <div class="card-body" style="max-height: 320px; overflow-y: auto; padding-right: 10px;">
                                    <!-- User -->
                                    <div class="mb-3">
                                        <div class="form-group custom-select2-form">
                                            <label for="documentation_tag_id">Tags <span class=" text-danger"></span>
                                            </label>
                                            <select name="documentation_tag_id[]" id="documentation_tag_id" class="form-select select2" multiple>
                                                <option value="" disabled>Select Tag</option>
                                                @foreach ($newsTagData as $singleNTData)
                                                @if(isset($singleNTData) && $singleNTData != null)
                                                <option value="{{ $singleNTData->id }}" {{ in_array($singleNTData->id, $tagIds ?? []) ? 'selected' : '' }}>
                                                    {{ $singleNTData->tag_name_en }}
                                                </option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        @error('documentation_tag_id')
                                        <span class=text-danger>{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success py-2 my-1 w-100">
                                        Update News
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- //Add new account.. --}}
            <div class="modal fade" id="modifyOthersInfo" tabindex="-1" aria-labelledby="oneInputModalLabel" aria-hidden="true"
                data-bs-backdrop='static'>
                <div class="modal-dialog modal-dialog-centered max-width-900px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="oneInputModalLabel">Update Other Information</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="row px-4 my-4">
                                
                                <!-- User -->
                                <div class="mb-3 col-md-6">
                                    <div class="form-group custom-select2-form">
                                        <label for="published_by_id">Published By <span class=" text-danger">*</span>
                                        </label>
                                        <select name="published_by_id" id="published_by_id" class="form-select select2">
                                            <option value="" selected disabled>Select User</option>
                                            @foreach ($userData as $singleUData)
                                            <option value="{{ $singleUData->id }}" {{ $singleDocumentationData->published_by_id == $singleUData->id ? 'selected' : '' }}>
                                                {{ Str::title($singleUData->name) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    @error('published_by_id')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>

                                <!-- Layout Format -->
                                <div class="mb-3 col-md-6">
                                    <div class="form-group custom-select2-form">
                                        <label for="is_published">Visibility <span class=" text-danger">*</span>
                                        </label>
                                        <select name="is_published" id="is_published" class="form-select select2" required>
                                            <option value="" disabled>Select Visibility</option>
                                            <option value="1" {{ old('is_published', $singleDocumentationData->is_published ?? '') == 1 ? 'selected' : '' }}>
                                                Published</option>
                                            <option value="2" {{ old('is_published', $singleDocumentationData->is_published ?? '') == 2 ? 'selected' : '' }}>
                                                Unpublished</option>
                                            <option value="3" {{ old('is_published', $singleDocumentationData->is_published ?? '') == 3 ? 'selected' : '' }}>Save
                                                As Draft</option>
                                        </select>
                                    </div>
                                
                                    @error('is_published')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>
                                
                                <!-- Layout Format -->
                                <div class="mb-3 col-md-6">
                                    <div class="form-group custom-select2-form">
                                        <label for="layout_format">Layout <span class=" text-danger">*</span>
                                        </label>
                                        <select name="layout_format" id="layout_format" class="form-select select2" required>
                                            <option value="" selected disabled>Select Layout</option>
                                            @foreach($layoutData as $singleLD)
                                            <option value="{{$singleLD}}" {{$singleLD == $singleDocumentationData->layout_format ? 'selected' : ''}}>{{$singleLD}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    @error('layout_format')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <!-- Publish Time -->
                                    <div class="mb-3">
                                        <label for="publish_date">Publish Date <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpickr-basic custom-date-picker" name="publish_date" id="publish_date"
                                            placeholder="DD-MM-YYYY" value="{{Carbon\Carbon::parse($todayDate)->format('d-m-Y')}}" required>
                                
                                        @error('publish_time')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close Modal</button>
                        </div>
                    </div>
                </div>
            </div>


        </form>
    </div>


@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $("#division_id").select2();
            $("#district_id").select2();
            $("#upozila_id").select2();
        });

        // Initialize single-select Category
        $("#documentation_category_id").select2();
        $("#documentation_tag_id").select2();
        $("#published_by_id").select2({
        dropdownParent: $('#modifyOthersInfo')
        });
        $("#is_published").select2({
        dropdownParent: $('#modifyOthersInfo')
        });
        $("#layout_format").select2({
        dropdownParent: $('#modifyOthersInfo')
        });

        $(".flatpickr-basic").flatpickr({
            allowInput: true,
            dateFormat: "d-m-Y",
        });

        // Initialize Summernote editor
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Enter news description here...',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview', 'help']]
                ]
            });
        });

        // Description validation
        function checkValidate() {
            const publishedBy = $('#published_by_id').val();
            const publishTime = $('#publish_time').val();

            if (!publishedBy || publishedBy === "") {
                $('#modifyOthersInfo').modal('show');
                $('#published_by_id').next('.select2-container').addClass('border-danger'); // Optional styling
                alert('Please select a Published By user.');
                return false;
            }
            if (($('#post_en').val()) == '') {
                document.getElementById('warnTextDesc1').classList.add('active')
                return false;
            }
            return true;
        }

        // Image preview helper
        function PreviewImageFP(inputId, previewId) {
            const fileInput = document.getElementById(inputId);
            const previewImg = document.getElementById(previewId);
            const dropzoneBlock = document.getElementById("dropzone-block");

            previewImg.style.width = "100%";
            previewImg.style.objectFit = "cover";

            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    dropzoneBlock.style.display = "none"; 
                };
                reader.readAsDataURL(file);
            }
        }

        function cancelPreviewFP(selectFile, previewImg) {
            const img = document.getElementById(previewImg);
            img.src = window.location.origin + "/backend/template-assets/images/img_preview.png";
            $("#" + selectFile).val('');
        }
    </script>

    <script>
        (function() {
            // holds { id, text } for each selected tag
            let tags = [];

            function renderTags() {
                const $container = $('#tag-container').empty();
                const $hidden = $('#tags-hidden').empty();

                tags.forEach((tagObj, idx) => {
                    // 1) visual chip
                    $container.append(`
                    <span class="tag-chip">
                        ${tagObj.text}
                        <span class="remove-btn" data-idx="${idx}">&times;</span>
                    </span>
                `);
                    // 2) hidden form input
                    $hidden.append(
                        `<input type="hidden" name="news_tags[]" value="${tagObj.id}">`
                    );
                });
            }

            // Add-button handler
            $('#add-tag-btn').on('click', () => {
                const val = $('#tag-select').val();
                const text = $('#tag-select option:selected').text();
                if (!val) return;

                // de-duplicate
                if (!tags.some(t => t.id == val)) {
                    tags.push({
                        id: val,
                        text: text
                    });
                    renderTags();
                }

                // reset dropdown
                $('#tag-select').val('');
            });

            // Remove-button handler
            $('#tag-container').on('click', '.remove-btn', function() {
                const idx = $(this).data('idx');
                tags.splice(idx, 1);
                renderTags();
            });
        })();
    </script>

    <script>
        $(document).ready(function () {
        // Initialize Select2 for dropdowns with dropdownParent set to the modal
        $("#published_by_id").select2({
        dropdownParent: $('#modifyOthersInfo'),
        width: '100%'
        });
        $("#is_published").select2({
        dropdownParent: $('#modifyOthersInfo'),
        width: '100%'
        });
        $("#layout_format").select2({
        dropdownParent: $('#modifyOthersInfo'),
        width: '100%'
        });
        
        // Initialize Flatpickr for date and time pickers
        flatpickr('#publish_date', {
        dateFormat: 'd-m-Y',
        defaultDate: '{{ Carbon\Carbon::parse($todayDate)->format('d-m-Y') }}'
        });
        
        flatpickr('#publish_time', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        time_24hr: true
        });
        
        // Function to update the card-body section
        function updateCardBody() {
        // Published By
        
        const publishedByOption = $('#published_by_id option:selected');
        const publishedByText = publishedByOption.val() && !publishedByOption.prop('disabled') ? publishedByOption.text() : '--';
        $('#publishedByText').text(publishedByText);
        
        // Visibility
        const visibilityText = $('#is_published option:selected').text();
        $('#visibilityText').text(visibilityText || 'Published');
        
        // News Format
        const newsFormatText = $('#layout_format').val();
        $('#newsFormatText').text(newsFormatText || 'Col 1');
        
        // Publish Date and Time
        const date = $('#publish_date').val();
        const time = $('#publish_time').val();
        const publishDateTime = date && time ? `${date} ${time}` : '--';
        $('#publishDateTimeText').text(date);
        }
        
        // Bind Select2 event listeners
        $('#published_by_id').on('select2:select', updateCardBody);
        $('#is_published').on('select2:select', updateCardBody);
        $('#layout_format').on('select2:select', updateCardBody);
        
        // Bind change event listeners for date and time
        $('#publish_date').on('change', updateCardBody);
        $('#publish_time').on('change', updateCardBody);
        
        // Update card-body when modal is shown (to reflect initial values)
        $('#modifyOthersInfo').on('shown.bs.modal', function () {
        updateCardBody();
        // Force Select2 to reposition dropdowns when modal is shown
        $('.select2-container').css('width', '100%');
        });
        
        // Reinitialize Select2 on modal hide to prevent issues with reused modals
        $('#modifyOthersInfo').on('hidden.bs.modal', function () {
        // $('.select2').select2('destroy');
        $("#published_by_id").select2({
        dropdownParent: $('#modifyOthersInfo'),
        width: '100%'
        });
        $("#is_published").select2({
        dropdownParent: $('#modifyOthersInfo'),
        width: '100%'
        });
        $("#layout_format").select2({
        dropdownParent: $('#modifyOthersInfo'),
        width: '100%'
        });
        });
        
        // Initial update to reflect default values
        updateCardBody();
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#documentation_tag_id').select2({
                placeholder: "Type, search or paste tags",
                tags: true,
                tokenSeparators: [',', ';'],
                width: '100%',
                ajax: {
                    url: "{{ route('documentation.tags.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(item){
                                return { id: item.id, text: item.tag_name_en };
                            })
                        };
                    },
                    cache: true
                },
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return { id: term, text: term, newTag: true };
                }
            });
    
        });
    </script>

    <script>
        $(document).ready(function () {
    
        // Parent → Children
        $('.category-parent').on('change', function () {
            const parentId = $(this).data('category-id');
            const isChecked = $(this).is(':checked');
    
            $('.category-child[data-parent-id="' + parentId + '"]')
                .prop('checked', isChecked);
        });
    
        // Children → Parent
        $('.category-child').on('change', function () {
            const parentId = $(this).data('parent-id');
    
            const totalChildren = $('.category-child[data-parent-id="' + parentId + '"]').length;
            const checkedChildren = $('.category-child[data-parent-id="' + parentId + '"]:checked').length;
    
            const parentCheckbox = $('.category-parent[data-category-id="' + parentId + '"]');
    
            parentCheckbox.prop('checked', totalChildren === checkedChildren);
        });
    
    });
    </script>
    
@endsection
