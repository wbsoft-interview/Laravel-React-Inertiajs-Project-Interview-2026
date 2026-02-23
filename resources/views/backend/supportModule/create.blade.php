@extends('backend.master')
@section('title') Support Ticket | Master Template @endsection
@section('ticket-support') active @endsection
@section('ticket-support.index') active @endsection
@section('styles')
@endsection


@section('main_content_section')
<div class="row py-3 ps-2">
    <div class="heading d-flex justify-content-start align-items-center">
        <h3 class="mb-0"><span> Ticket Create</span></h3>
    </div>
</div>

<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="card">
        <div class="card-body">
            <form action="{{route('ticket-support.store')}}" method="post" enctype="multipart/form-data"
                onsubmit="return checkValidate()">
                @csrf
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row">

                        <div class="col-md-8 mb-2">
                            <div class="mb-2">
                                <div class="form-group custom-select2-form">
                                    <label for="support_type">Support Type <span class=" text-danger">*</span>
                                    </label>
                                    <select name="support_type" id="support_type" class="form-select select2"
                                        required>
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>

                                @error('support_type')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="subject">Subject <span class=" text-danger">*</span> </label>
                                    <input type="text" name="subject" required class="form-control" placeholder="Subject ">
                                </div>
                            
                                @error('subject')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <div class="form-group form_add_new_post">
                                    <label for="summernote" class="form-label"> Details <span
                                            class="text-danger">*</span></label>

                                    <textarea class="fomr-control summernote" id="post_details" name="details"></textarea>
                                    <span id="warnTextDesc2" class="text-danger warn d-none">Details is
                                        required</span>
                                </div>

                                @error('post')
                                <span class=text-danger>{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label for="name">Attachment Photo <span class=" text-danger"></span></label> <br>
                                    <div class=" position-relative custom-soft-setting dropzone">
                                        <div class="select_imgWith_preview py-2">
                                            <img id="uploadPreview1"
                                                src="{{ asset('backend/template-assets/') }}/images/img_preview.png">

                                            <div id="dropzone-block" class="custom-media-upload-block mt-3">
                                                <span class="dropzone-label fw-bolder">Upload Photo</span>
                                            </div>
                                            <input class="custom-max-width-100" id="uploadImage1" type="file"
                                                name="image" onchange="PreviewImageFP('uploadImage1','uploadPreview1');"
                                                />
                                        </div>
                                    </div>

                                    @error('image')
                                    <span class=text-danger>{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="margin-top: 15px;">
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
    $("#support_type").select2();

    $(document).ready(function() {
    $('.summernote').summernote({
        placeholder: 'Details',
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