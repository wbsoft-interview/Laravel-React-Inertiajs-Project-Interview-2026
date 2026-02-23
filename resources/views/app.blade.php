<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mastering Panel</title>

    <!-- bootstrap css  -->
        <link rel="stylesheet" href="{{ asset('backend/template-assets/') }}/css/bootstrap.min.css">
        <!-- font awesome  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- google fonts  -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;600;700&family=Raleway:wght@300&family=Roboto:ital,wght@0,100;1,900&family=Yuji+Hentaigana+Akari&display=swap"
            rel="stylesheet">
        
        <!-- google icon  -->
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        {{-- datatable css --}}
        <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <!-- YOUR IMAGE PREVIEW CSS -->
        <link rel="stylesheet" href="{{asset('backend')}}/image-preview/css/lightcase.css">
        
        <!-- custom css  -->
        <link rel="stylesheet" href="{{ asset('backend/template-assets/') }}/css/style.css">
        <link rel="stylesheet" href="{{ asset('backend')}}/custom/css/flatpickr.min.css">
        <link rel="stylesheet" href="{{asset('frontend')}}/css/toastr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('backend')}}/custom/css/custom.css">
        
        <style>
            .modal input {
                padding-top: 7px;
                padding-bottom: 7px;
            }
        
            .custom-time-picker {
                padding-top: 18px !important;
                padding-bottom: 19px !important;
            }
        
            #content .main-content {
                overflow-x: unset;
            }
        </style>

    {{-- CSS (unchanged — keep what already works) --}}
    @vite('resources/js/app.jsx')
</head>

<body>

    {{-- Inertia root (creates #app automatically) --}}
    @inertia

    {{-- =====================
        GLOBAL JS (ONLY ONCE)
    ====================== --}}

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="{{asset('backend')}}/template-assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('backend')}}/custom/js/flatpickr.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Legacy / theme JS -->
    <script src="{{asset('backend')}}/template-assets/js/scritp.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/lightcase.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/swiper.min.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/progress.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/functions.js"></script>

</body>

</html>