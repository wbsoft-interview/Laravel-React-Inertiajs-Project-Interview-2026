@extends('backend.master')
@section('title') Profile | Master Template @endsection
@section('admin/profile') active @endsection
@section('admin/profile') active @endsection
@section('styles')
<style>
    .setting-custom-select2-form .select2-container--default .select2-selection--single {
        padding-top: 5px;
        padding-bottom: 5px;
    }

</style>
@endsection

@section('main_content_section')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class=" d-flex align-items-center justify-content-between my-3">
        <div class="align-items-center">
            <h3 class="mb-0 pb-0">Personal Profile<span class="divider"></span></h3>
        </div>
    </div>

    <div class="content p-0">
        <div class="content-body">
            <section class="app-user-view-account">
                <div class="row">
                    <!-- User Sidebar -->
                    <div class="col-xl-4 col-lg-5 col-md-5">
                        <!-- User Card -->
                        {{-- @include('admin.profile.sidebar') --}}
                        <!-- /User Card -->
                        <div class="card">
                            <div class="card-header text-start">
                                <h4 class="mb-0 pb-0">User Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="user-avatar-section pb-3">
                                    <div class="d-flex align-items-center flex-column">
                                        <a href="{{ Auth::user()->image ? asset('storage/uploads/user_img/'.Auth::user()->image) : asset('backend/template-assets/images/img_preview.png') }}"
                                            data-rel="lightcase">
                                            <img class="rounded"
                                                src="{{ Auth::user()->image ? asset('storage/uploads/user_img/'.Auth::user()->image) : asset('backend/template-assets/images/img_preview.png') }}"
                                                alt="avatar" height="110" width="110">
                                        </a>

                                        <div class="user-info text-center mt-3">
                                            <h4 class="mb-0">{{ Auth::guard()->user()->name}}</h4>
                                            <span
                                                class="badge bg-secondary text-light">{{ Auth::guard()->user()->role}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-container">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <span class="fw-bolder me-25">Name:</span>
                                            <span>{{ Auth::guard()->user()->name}}</span>
                                        </li>

                                        <li class="mb-2">
                                            <span class="fw-bolder me-25">Email:</span>
                                            <span>{{ Auth::user()->email }}</span>
                                        </li>

                                        <li class="mb-2">
                                            <span class="fw-bolder me-25">Mobile:</span>
                                            <span>{{ Auth::user()->mobile }}</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <div class="card-footer">
                                <a href="#" class="btn btn-primary custom-profile-security w-100" data-bs-toggle="modal"
                                    data-bs-target="#updateSecurity">Password Change</a>
                            </div>

                        </div>

                        {{-- //To update... --}}
                        <div class="modal fade" id="updateSecurity" tabindex="-1" data-bs-backdrop='static'>
                            <div class="modal-dialog modal-dialog-centered max-width-900px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="oneInputModalLabel">Password Update</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('admin.security.update')}}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">

                                                <div class="col-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="old_password">Old Password <span
                                                                class=" text-danger">*</span></label>
                                                        <input type="password" name="old_password" class="form-control"
                                                            placeholder="Old Password" required>
                                                    </div>

                                                    @error('old_password')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="new_password">New Password <span
                                                                class=" text-danger">*</span></label>
                                                        <input type="password" name="new_password" class="form-control"
                                                            placeholder="New Password" required>
                                                    </div>

                                                    @error('new_password')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="form-group">
                                                        <label for="confirm_password">Confirm Password <span
                                                                class=" text-danger">*</span></label>
                                                        <input type="password" name="confirm_password"
                                                            class="form-control" placeholder="Confirm Password"
                                                            required>
                                                    </div>

                                                    @error('confirm_password')
                                                    <span class=text-danger>{{$message}}</span>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ User Sidebar -->

                    <!-- User Content -->
                    <div class="col-xl-8 col-lg-7 col-md-7">
                        <div class="card shadow">
                            <h4 class="card-header">Update Profile Basic</h4>

                            <form action="{{route('admin.profile.update', Auth::user()->id )}}" method="post"
                                enctype="multipart/form-data" onsubmit="return checkValidate()">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-8 mb-2">
                                            <div class="mb-2">
                                                <div class="form-group">
                                                    <label for="name">Name <span class=" text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ Auth::user()->name }}" placeholder="Name" required>
                                                </div>

                                                @error('name')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-2">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile <span
                                                            class=" text-danger">*</span></label>
                                                    <input type="number" name="mobile" id="mobile" class="form-control"
                                                        value="{{ Auth::user()->mobile }}" placeholder="Mobile"
                                                        required>
                                                </div>

                                                @error('mobile')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-2">
                                                <div class="form-group">
                                                    <label for="email">Email <span class=" text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ Auth::user()->email }}" placeholder="Email" required>
                                                </div>

                                                @error('email')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label for="name">Profile Photo <span
                                                        class=" text-danger"></span></label> <br>
                                                <div class=" position-relative custom-soft-setting dropzone">
                                                    <div class="select_imgWith_preview py-2">
                                                        <img id="uploadPreview1"
                                                            src="{{ Auth::user()->image ? asset('storage/uploads/user_img/'.Auth::user()->image) : asset('backend/template-assets/images/img_preview.png') }}">

                                                        <div id="dropzone-block" class="custom-media-upload-block mt-3">
                                                            {{-- <span class="dropzone-label fw-bolder">Upload Photo</span> --}}
                                                        </div>
                                                        <input id="uploadImage1" type="file" name="image"
                                                            onchange="PreviewImageFP('uploadImage1','uploadPreview1');" />
                                                    </div>
                                                </div>

                                                @error('image')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <div class="form-group custom-select2-form setting-custom-select2-form">
                                                    <label for="roles">Role <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="roles" id="roles" class="form-select select2 mt-2"
                                                        disabled required>
                                                        <option value="" selected disabled>
                                                            {{Str::title(Auth::user()->role)}} </option>
                                                    </select>
                                                </div>

                                                @error('roles')
                                                <span class=text-danger>{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-success w-100">Update Profile</button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!--/ User Pills -->

                </div>
                <!--/ User Content -->
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection

@section('scripts')
<script>
    $("#branch_id").select2()

    function checkValidate() {
        var mobileNumber = $("#mobile").val();

        if (mobileNumber != '' && mobileNumber.length != 11) { 
            event.preventDefault();
            toastr.error("Mobile number must be 11 digit."); 
        }

        return true;
    }

    $(document).ready(function(){
        $('#pills-security').css('display', 'none');
        $(document).on('click','#pills-account-tab', function(){
            $('#pills-security').css('display', 'none');
        })
        $(document).on('click','#pills-security-tab', function(){
            $('#pills-security').css('display', 'block');
        })
    })

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