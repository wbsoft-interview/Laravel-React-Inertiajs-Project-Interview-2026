
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
    <title>Two Steps Verification Basic - Vuexy - Bootstrap HTML admin template</title>
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
          
          <h2 class="brand-text text-primary ms-1">Vuexy</h2>
        </a>
        <p class="card-text mb-75">
          We will sent a verification code to your mobile. Enter the mobile number in the field below.
        </p>
        <p class="card-text fw-bolder mb-2">******0789</p>

        <form  method="POST" action="{{ route('update-user-password') }}">
        @csrf

          	<div class="form-group">
                <label class="form-label" for="mobile">Mobile Number</label>
                  <input name="mobile" type="text"  value="{{ $userMobile }}"
                        class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" 
                           required autofocus placeholder="Your Mobile Number">
                 @if($errors->has('mobile'))
               	<div class="invalid-feedback">
                   {{ $errors->first('mobile') }}
                </div>
                 @endif            
            </div>
          	
            <div class="form-group">
                <label class="form-label" for="password">New Password</label>
                  <input name="password" type="password" 
                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                           required autofocus placeholder="············">
                 @if($errors->has('password'))
               	<div class="invalid-feedback">
                   {{ $errors->first('password') }}
                </div>
                 @endif            
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                  <input name="password_confirmation" type="password" 
                        class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" 
                           required autofocus placeholder="············">
                 @if($errors->has('password_confirmation'))
               	<div class="invalid-feedback">
                   {{ $errors->first('password_confirmation') }}
                </div>
                 @endif            
            </div>
              
            <br>
          <button type="submit" class="btn btn-primary w-100" tabindex="4">Update</button>
        </form>
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