
    
    <!-- jquery  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" 
    integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- popper js  -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
    <!-- bootstrap js  -->
    <script src="{{ asset('backend/template-assets/') }}/js/bootstrap.min.js"></script>
    <!-- select2 js  -->
    
    {{-- datatable js  --}}
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    
    {{-- sweetalert2  --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/template-assets/') }}/js/sweetAlert2.js"></script>
    <!-- tinymce text editor -->
    <script src="https://cdn.tiny.cloud/1/1b7n2ihuyajk18uvws5xss8gpdsmdwno2s6st3vrcgo788qy/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <!-- custom js  -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend')}}/custom/js/flatpickr.min.js"></script>
    <script src="{{ asset('backend/template-assets/') }}/js/scritp.js"></script>

    <!-- Image preview  -->
    {{-- <script src="{{asset('backend')}}/image-preview/js/jquery.js"></script> --}}
    <script src="{{asset('backend')}}/image-preview/js/lightcase.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/swiper.min.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/progress.js"></script>
    <script src="{{asset('backend')}}/image-preview/js/functions.js"></script>
    <!-- Image preview  -->

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="{{asset('backend')}}/custom/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

    <script>
        // Validation errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}", "Validation Error", { "progressBar": true });
            @endforeach
        @endif
    </script>

    @yield('scripts')