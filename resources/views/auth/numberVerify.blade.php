
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Verify</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/themes/bordered-layout.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/themes/semi-dark-layout.min.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="../admin/app-assets/css/pages/authentication.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../admin/assets/css/style.css">
    <!-- END: Custom CSS-->

  </head>
  <!-- END: Head-->

  @extends('frontend.first_master')
  @section('first_content')

  <!-- BEGIN: Body-->
  <body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
      <div class="content-overlay"></div>
      <div class="header-navbar-shadow"></div>
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body"><div class="auth-wrapper auth-basic px-2">
  <div class="auth-inner my-2">
    <!-- two steps verification basic-->
    <div class="card mb-0">
      <div class="card-body">
        <a href="index.html" class="brand-logo">
        </a>

        <h2 class="card-title fw-bolder mb-1">Two Step Verification 💬</h2>
        <p class="card-text mb-75">
          We sent a verification code to your mobile. Enter the code from the mobile in the field below.
        </p>

        @php
          $mobile = Session::get('mobile');
        @endphp
        
        <p class="card-text fw-bolder mb-2">{{$mobile}}</p>
        <form  method="POST" action="{{ route('otp-verify-code') }}">
        @csrf

          <h6>Type your 6 digit security code</h6>

          	<div class="form-group">
                <label class="form-label" for="verify_code">Otp</label>
                  <input name="verify_code" type="text" 
                        class="form-control{{ $errors->has('verify_code') ? ' is-invalid' : '' }}" 
                           required autofocus placeholder="Otp">
                 @if($errors->has('verify_code'))
               	<div class="invalid-feedback">
                   {{ $errors->first('verify_code') }}
                </div>
                 @endif            
            </div>

              @if(isset($userMobile))
                <input type="hidden" value="{{$userMobile}}" name="mobile">
              @endif

            <br>
          <button type="submit" class="btn btn-primary w-100" tabindex="4">Verify</button><br><br>
        </form>
        <a type="submit" href="{{route('change.number')}}" class="btn btn-info w-100" tabindex="4">Change Number</a>

        <p class="text-center mt-2">
          <span>Didn’t get the code?</span><a href="Javascript:void(0)"><span>&nbsp;Resend</span></a>
          <span>or</span>
          <a href="Javascript:void(0)"><span>&nbsp;Call Us</span></a>
        </p>
      </div>
    </div>
    <!-- /two steps verification basic -->
  </div>
</div>

        </div>
      </div>
    </div>
    <!-- END: Content-->
    @endsection

    <!-- BEGIN: Vendor JS-->
    <script src="{{asset('backend')}}/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../admin/app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="../admin/app-assets/js/core/app-menu.min.js"></script>
    <script src="../admin/app-assets/js/core/app.min.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="../admin/app-assets/js/scripts/pages/auth-two-steps.min.js"></script>
    <!-- END: Page JS-->

    <script>
      $(window).on('load',  function(){
        if (feather) {
          feather.replace({ width: 14, height: 14 });
        }
      })
    </script>
  </body>
  <!-- END: Body-->
</html>