<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test paper</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/toastr.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('frontend')}}/css/custom.css">
</head>

<body>
    <div class="mobile-body no_scroll pt-2">
        <div class=" header row">
            <div class="header_content d-flex justify-content-between px-4 py-2 align-items-center">
                <div class=""><a href="{{route('webuser.dashboard')}}"> <i class="fa-solid fa-house"></i></a></div>
                <div class="">
                    <a href="{{route('webuser.dashboard')}}">
                        <img src="{{ asset('frontend/template-assets/') }}/images/logo.png" alt=""
                            style="width:120px; height: 30px;">
                    </a>
                </div>
                <div class=""><a href=""><i class="fa-solid fa-bell"></i></a></div>
            </div>
        </div>

        <div style="min-height: 90% !important;" class="h-100 justify-content-center flex-column d-flex px-2 mb-5">
            <form action="{{route('webuser.post-forgot-pass-verify-OTP')}}" method="POST" enctype="multipart/form-data"
                autocomplete="false" class="needs-validation container card mt-3" novalidate>
                @csrf
                <h3 class="text-center heading_text py-2">Enter Verification Code</h3>

                <div class="form-group custom-form-group">
                    {{-- <label class="custom-form-label" for="email">OTP Code <span class="custom-danger">(requierd)</span></label> --}}
                    <div class="single_input mb-3">
                        <span class="material-symbols-outlined">
                            deployed_code_update
                        </span>
                        <input autocomplete="false" type="number" placeholder="OTP Code" name="verify_code" required
                            class="form-control">
                    </div>
                </div>
                @php
                $getUserMobile = Crypt::encrypt($userMobile);
                @endphp
                <button class="primary_btn mb-0 w-100 mt-1 custom-common-submit-button" type="submit">Verify
                    OTP</button>
                <label class="mt-2 text-center d-block pb-2" for="login">
                    <a href="{{route('webuser.resend-OTP-for-password-change',['user_mobile' => $getUserMobile])}}">Resend OTP</a>
                </label>
            </form>
        </div>



        <footer class="p-2 custom-dashboard-footer">
            <div class="row ">
                <a href="{{route('webuser.homepage.class-list')}}" class="col-3 text-center">
                    <span class="material-symbols-outlined">
                        menu_book
                    </span>
                    <p>পড়াশোনা</p>
                </a>
                <a href="{{route('webuser.get-login')}}" class="col-3 text-center
                    {{ request()->is('webuser/login') ? 'active' : '' }}
                    {{ request()->is('webuser/password-forgot-otp-sent') ? 'active' : '' }}
                    {{ request()->is('webuser/forgot-pass-verify-OTP/*') ? 'active' : '' }}
                ">
                    <span class="material-symbols-outlined">
                        thumb_up
                    </span>
                    <p>লগইন</p>
                </a>
                <a href="{{route('webuser.homepage.exam')}}" class="col-3 text-center">
                    <span class="material-symbols-outlined">
                        schedule
                    </span>
                    <p>পরিক্ষা</p>
                </a>
                <a href="#" class="col-3 text-center
                    {{ request()->is('webuser/register') ? 'active' : '' }}
                ">
                    <span class="material-symbols-outlined">
                        person
                    </span>
                    <p>রেজিস্টার</p>
                </a>
            </div>
        </footer>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="{{asset('frontend')}}/js/toastr.min.js"></script>
<script src="{{asset('frontend')}}/js/select2.min.js"></script>
<script src="{{asset('frontend')}}/js/app.js"></script>
{!! Toastr::message() !!}

<script>
    $(document).ready(function() {
        $('#classname_id').select2();
    });
</script>

</html>