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

        <div style="min-height: 90% !important;" class=" h-100 justify-content-center flex-column d-flex px-2">
            <form class="needs-validation card mt-3 container" action="{{route('webuser.password-forgot-OTP-sent')}}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <h3 class="text-center heading_text py-2">Forgot Password</h3>

                <div class="form-group custom-form-group pb-1">
                    <label class="custom-form-label" for="email">Mobile <span class="custom-danger">(requierd)</span></label>
                    <div class="single_input">
                        <span class="material-symbols-outlined">
                            call
                        </span>
                        <input class="input" type="number" placeholder="Mobile" name="mobile" id="mobile" required class="form-control">
                    </div>
                </div>

                <div class="condition pt-2 pb-2">
                    <button type="submit" class="primary_btn custom-common-submit-button" onclick="return checkValidate()">Verification Code Sent</button>
                    <label class="mt-2 text-center d-block" for="login">Don't have an account? <a
                            href="{{route('webuser.get-register')}}">Register</a></label>
                </div>
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
                    {{ request()->is('webuser/password-forgot') ? 'active' : '' }}
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
                <a href="{{route('webuser.get-register')}}" class="col-3 text-center">
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
{!! Toastr::message() !!}

<script>
    function checkValidate() {
        var mobileNumber = $("#mobile").val();

        if (mobileNumber != '' && mobileNumber.length != 11) { 
            event.preventDefault();
            toastr.error("Mobile number must be 11 digit."); 
        }

        return true;
    }
</script>

</html>