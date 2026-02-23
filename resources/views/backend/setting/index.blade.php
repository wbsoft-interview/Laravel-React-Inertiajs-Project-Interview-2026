@extends('backend.master')
@section('title') Settings | Master Template @endsection
@section('setting') active @endsection
@section('styles')
@endsection
@section('main_content_section')

<div class="app-content content min-vh-100">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row ps-2">
            <div class="content-header-left col-md-9 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h3 class="content-header-title float-start my-3 mb-0">General Settings</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body mt-2">
            <div class="row">
                <div class="col-12">

                    <!-- profile -->
                    <div class="card">
                        @if(isset($logo) && $logo != null)
                        <form method="POST" action="{{route('logo.update', $logo->id)}}" enctype="multipart/form-data">
                            @csrf
                            @else
                            <form method="POST" action="{{route('logo.store')}}" enctype="multipart/form-data">
                                @csrf
                                @endif

                                <div class="card-header border-bottom">
                                    <h4 class="card-title">Sidebar</h4>
                                </div>
                                <div class="card-body py-2 my-25">
                                    <div class="row">
                                        <div class="col-md-6 h-100">
                                            <div class="col mb-3">

                                                <div id="card_body_5"
                                                    class=" mb-3 position-relative custom-soft-setting">
                                                    <div class="select_imgWith_preview">
                                                        <label class="fw-bolder" for="name">Sidebar Logo <span
                                                                class=" text-danger">(Recommanded
                                                                W:150px,H:40px)</span></label> <br>

                                                        <img id="uploadPreview1"
                                                        src="{{ isset($logo) && $logo?->logo_image ? Storage::url('uploads/logoImg/' . $logo->logo_image) : asset('backend/template-assets/images/img_preview.png') }}">

                                                        <input id="uploadImage1" type="file" name="logo_image"
                                                            onchange="PreviewImage('uploadImage1','uploadPreview1');"
                                                            required />
                                                        <span onclick="cancelPreview('uploadImage1','uploadPreview1')"
                                                            class="material-symbols-outlined" id="image_remove_buttton">
                                                            close </span>
                                                    </div>
                                                </div>

                                                @error('logo_image')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 h-100">
                                            <div class="col mb-2">
                                                <span>
                                                    <div class="form-group custom-select2-form">
                                                        <label for="logo_width">Logo Width <span
                                                                class=" text-danger">(In pixel)</span></label>

                                                        <input type="number" class="form-control" name="logo_width"
                                                            id="logo_width" value="150" placeholder="Width" required>
                                                    </div>

                                                    @error('logo_width')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </span>
                                            </div>

                                            <div class="col mb-2">
                                                <span>
                                                    <div class="form-group custom-select2-form">
                                                        <label for="logo_height">Logo Height <span
                                                                class=" text-danger">(In pixel)</span></label>

                                                        <input type="number" class="form-control" name="logo_height"
                                                            id="logo_height" value="40" placeholder="Height" required>
                                                    </div>

                                                    @error('logo_height')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <input type="submit" name="submit" id="formSubmit"
                                                class="btn btn btn-success" value="Update Logo">
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>

                    <div class="card mt-3">
                        <form action="{{route('footer-text-submit')}}" method="post">
                            @csrf
                            <input type="hidden" name="footer_id" value="{{$footerText->id ?? ''}}">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Footer Text</h4>
                            </div>
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="com-md-12 mb-3">
                                        <div class="form-group">
                                            <label for="text">Footer Text <span
                                                    class=" text-danger fw-bolder">(required)</span></label>

                                            <textarea class="form-control" name="text" id="text" cols="2" rows="2"
                                                placeholder=" Footer Text" required>{!! optional($footerText)->text !!}</textarea>
                                        </div>

                                        @error('text')
                                        <span class=text-danger>{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <input type="submit" name="submit" id="formSubmit" class="btn btn btn-success"
                                            value="Update Text">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->

@endsection

@section('scripts')
<script>
    //For package description...
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

    function validate(){
        if (($('#invoice_terms').val()) == '') {
            event.preventDefault();
            document.getElementById('warnTextDesc').classList.add('active')
            return false;
        }
    };
    function validateFU(){
        if (($('#invoice_terms').val()) == '') {
            event.preventDefault();
            document.getElementById('warnTextDesc').classList.add('active')
            return false;
        }
    };
</script>
@endsection