<!-- COMMON SCRIPTS -->
<script src="{{asset('frontend')}}/js/jquery-3.7.1.min.js"></script>
<script src="{{asset('frontend')}}/js/common_scripts.min.js"></script>
<script src="{{asset('frontend')}}/js/functions.js"></script>

<!-- SPECIFIC SCRIPTS -->
<script src="{{asset('frontend')}}/js/vegas.min.js"></script>
<script>
    $(function() {
        "use strict";

        // Define the base URL
        var baseUrl = window.location.protocol + '//' + window.location.host + "/frontend/";
        // var baseUrl = "http://127.0.0.1:8000/frontend/";
        
        $('.hero_kenburns').vegas({
        slides: [
        { src: baseUrl + 'img/slides/1.jpg' },
        { src: baseUrl + 'img/slides/3.jpg' },
        { src: baseUrl + 'img/slides/2.jpg' }
        ],
        overlay: false,
        transition: 'fade2',
        animation: 'kenburnsUpRight',
        transitionDuration: 1000,
        delay: 5000,
        animationDuration: 30000
        });
    });
</script>

<!-- Image preview  -->
{{-- <script src="{{asset('frontend')}}/image-preview/js/jquery.js"></script> --}}
<script src="{{asset('frontend')}}/image-preview/js/lightcase.js"></script>
<script src="{{asset('frontend')}}/image-preview/js/swiper.min.js"></script>
<script src="{{asset('frontend')}}/image-preview/js/progress.js"></script>
<script src="{{asset('frontend')}}/image-preview/js/functions.js"></script>
<!-- Image preview  -->

<script src="{{asset('frontend')}}/js/select2.min.js"></script>
<script src="{{asset('frontend')}}/js/flatpickr.min.js"></script>
<script src="{{asset('frontend')}}/js/toastr.min.js"></script>
{!! Toastr::message() !!}

@yield('scripts')