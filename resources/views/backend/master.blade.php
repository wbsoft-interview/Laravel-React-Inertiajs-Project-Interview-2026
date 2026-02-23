<!DOCTYPE html>
<html lang="en">

{{-- Begin-Head  --}}

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tab-icon.png') }}">

    {{-- style add  --}}
    @include('backend.layout.partial.style')
    {{-- style end  --}}
</head>

<body>
    <div class="wrapper">

        <!-- for small screen overlay when navbar visible  start-->
        <div class="body-overlay"></div>
        <!-- for small screen overlay when navbar visible  end-->

        <!-- Sidebar   start-->
        @include('backend.layout.sidebar')

        <!-- Sidebar   end-->



        <!-- Page Content  start-->
        <div id="content" class="
            ">

            <!-- top navbar start  -->
            @include('backend.layout.header')

            <!-- top navbar end  -->


            <!-- main-content start  -->

            <div class="main-content custom-master-main-content p-0
                ">

                {{-- original main content  start --}}
                @yield('main_content_section')
                {{-- original main content  end --}}
            </div>

            <!-- footer start  -->
            @include('backend.layout.footer')
            <!-- footer end  -->
        </div>
        <!-- page content end     -->
    </div>

    @yield('modal_section')

    @include('backend.layout.partial.script')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        Swal.fire({
        icon: 'success',
        title: 'Success',
        text: @json(session('success')),
        timer: 4000,
        showConfirmButton: false
    });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
        icon: 'error',
        title: 'Error',
        text: @json(session('error')),
        timer: 4000,
        showConfirmButton: false
    });
    </script>
    @endif

    <script>
        $(document).ready(function(){
            $("#client_id_fh").select2({
                dropdownParent: $('#filterClient')
            });
        });
    </script>
</body>
{{-- End-body --}}

</html>