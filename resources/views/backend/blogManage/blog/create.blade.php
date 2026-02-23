@extends('backend.master')
@section('title') Blog | Master Template @endsection
@section('blog') active @endsection
@section('blog.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Blog Create</span></h3>
    </div>
</div>

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="card">
        <div class="card-body">
            <form action="{{route('blog.store')}}" method="post" enctype="multipart/form-data" onsubmit="return checkValidate()">
                @csrf
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row">

                        <div class="col-md-8 mb-2">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="title">Title <span class=" text-danger">*</span> </label>
                                    <input type="text" name="title" required class="form-control" placeholder="Title ">
                                </div>
                                
                                @error('title')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <div class="form-group custom-select2-form">
                                    <label for="blog_category_id">Expense Category <span class=" text-danger">*</span>
                                    </label>
                                    <select name="blog_category_id" id="blog_category_id" class="form-select select2" required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach($blogCategoryData as $singleBCD)
                                        <option value="{{$singleBCD->id}}">{{$singleBCD->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                @error('blog_category_id')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <div class="form-group form_add_new_post">
                                    <label for="summernote" class="form-label"> Description <span class="text-danger">*</span></label>
                            
                                    <textarea class="fomr-control summernote" id="post_details" name="post"></textarea>
                                    <span id="warnTextDesc2" class="text-danger warn d-none">Description is required</span>
                                </div>
                            
                                @error('post')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="name">Blog Photo <span class=" text-danger">*</span></label> <br>
                                    <div class=" position-relative custom-soft-setting dropzone">
                                        <div class="select_imgWith_preview py-2">
                                            <img id="uploadPreview1" src="{{ asset('backend/template-assets/') }}/images/img_preview.png">
                            
                                            <div id="dropzone-block" class="custom-media-upload-block mt-3">
                                                <span class="dropzone-label fw-bolder">Upload Photo</span>
                                            </div>
                                            <input class="custom-max-width-100" id="uploadImage1" type="file" name="photo"
                                                onchange="PreviewImageFP('uploadImage1','uploadPreview1');" required />
                                        </div>
                                    </div>
                            
                                    @error('photo')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"
                            style="margin-top: 15px;">
                            <button type="submit" class="btn btn btn-success m-0 py-2 text-right">Save</button>
                        </div>

                    </div>

                </div>
            </form>
        </div>

    </div>

</div>
<!-- END: Content-->
@endsection

@section('scripts')
<script>
    $("#blog_category_id").select2();

    $(document).ready(function() {
    $('.summernote').summernote({
        placeholder: 'Hello stand alone ui',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['', 'codeview', 'help']]
        ]
      });
    });

    function checkValidate(){
        if (($('#post_details').val()) == '') {
            document.getElementById('warnTextDesc2').classList.add('active')
            return false;
        }
    };

    //For image preview...
    function PreviewImageFP(selectFile, previewImg) {
        // $("#dropzone-block").css('display','none');
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById(selectFile).files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById(previewImg).src = oFREvent.target.result;
        };
    }

    function cancelPreviewFP(selectFile, previewImg) {
        const img = document.getElementById(previewImg);
        img.src = window.location.origin + "/backend/template-assets/images/img_preview.png";
        $("#" + selectFile).val('');
    }
</script>
@endsection