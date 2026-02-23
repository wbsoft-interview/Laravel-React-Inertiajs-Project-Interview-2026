@extends('frontend.master')
@section('title') Tiayra @endsection
@section('styles')
@endsection
@section('frontend_content')

<main>
  <div class="hero_home hero_kenburns">
    <div class="content">
      <h3>Your Dream Treatment</h3>
      <p>
        Embark on a Journey of Radiant Transformation at Tiayra: Unveiling Beauty, Nurturing Wellness
      </p>
      <a href="#appoinmentSection" class="btn btn-outline-dark custom-book-appoin-btn">Book Appointment</a>
    </div>
  </div>
  <!-- /Hero -->

  <div class="container py-5" id="branchSection">
    <div class="main_title">
      <h2>Discover the <strong>online</strong> branches!</h2>
      <p>Usu habeo equidem sanctus no. Suas summo id sed, erat erant oporteat cu pri. In eum omnes molestie. Sed ad
        debet scaevola, ne mel.</p>
    </div>
    <div class="row add_bottom_30">
      <div class="col-lg-4">
        <div class="h-100">
          <div class="box_feat" id="icon_1">
            <span></span>
            <p class="mt-3"><strong>📍 Bashudhara Branch :</strong>
              House # 15, (5th Floor), Block # A, EBL Bank Building, Bashundhara Main Road, Bashundhara R/A,Dhaka.</p>
          </div>
        </div>
      </div>
        

      <div class="col-lg-4">
        <div class="h-100">
          <div class="box_feat" id="icon_1">
            <span></span>
            <p class="mt-3"><strong>📍Eskaton Branch:</strong>
              OTTOWAN CENTER, (8th floor)
              121/5 New Eskaton Road, Dhaka.</p>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="h-100">
          <div class="box_feat" id="icon_1">
            <p class="mt-3"><strong>📍 Uttara Branch:</strong>
              House #4, Road #9, Sector #1,
              (Besides Scholastica School), Uttara Model Town, Dhaka.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End container -->

  <div class="bg_color_1">
    <div class="container py-5" id="doctorsSection">
      <div class="main_title">
        <h2>Our Respected Physicians</h2>
        <p>Cum doctus civibus efficiantur in imperdiet deterruisset.</p>
      </div>
      <div class="row">

        {{-- @foreach($providerData as $singlePD)
        @if(isset($singlePD) && $singlePD != null)
        <div class="col-lg-4 col-md-6 mb-4">
            @if(isset($singlePD->provider_image) && $singlePD->provider_image != null)
            <a class="box_feat_about h-100 p-2" href="{{ asset('uploads/provider/'.$singlePD->provider_image) }}" data-rel="lightcase">
              <img src="{{ asset('uploads/provider/'.$singlePD->provider_image) }}" class="custom-dr-img" height="300" />
            
            @else
            <a class="box_feat_about h-100 p-2" href="{{ asset('backend/template-assets/') }}/images/img_preview.png" data-rel="lightcase">
            <img src="{{ asset('backend/template-assets/') }}/images/img_preview.png" class="custom-dr-img" height="300" />
            
            @endif
            <h3 class="custom-font-common-color">{{$singlePD->provider_name}}</h3>
            <p class="custom-font-common-color">{!! $singlePD->provider_academic !!}</p>
            </a>
        </div>
        @endif
        @endforeach --}}

      </div>
      <!--/row-->
    </div>
  </div>

  <div class="container py-5">
    <div class="main_title">
      <h2>Common Services</h2>
      <p>Usu habeo equidem sanctus no. Suas summo id sed, erat erant oporteat cu pri.</p>
    </div>
    <div id="reccomended" class="owl-carousel owl-theme">

      {{-- @foreach($frontendCommonServiceData as $singleFCSD)
      @if(isset($singleFCSD) && $singleFCSD != null)
      <div class="item">
          <div class="title"></div>
          @if(isset($singleFCSD->service_image) && $singleFCSD->service_image != null)
          <a href="{{ asset('uploads/service/thumbnail/'.$singleFCSD->service_image) }}"
            data-rel="lightcase">
          <img src="{{asset('uploads/service/thumbnail/'.$singleFCSD->service_image)}}" height="300" alt="">
          </a>
          @else
          <a href="{{ asset('backend/template-assets/') }}/images/img_preview.png"
            data-rel="lightcase">
          <img src="{{ asset('backend/template-assets/') }}/images/img_preview.png"  height="300" class="custom-service-img">
          </a>
          @endif
      </div>
      @endif
      @endforeach --}}

      {{-- <div class="item">
        <a href="javascript:void(0)">
          <div class="title">
          </div><img src="{{asset('frontend')}}/img/gallery/6.jpg" alt="">
        </a>
      </div> --}}

    </div>
    <!-- /carousel -->
  </div>
  <!-- /white_bg -->

  <div class="bg_color_1">
    <div class="container py-5" id="serviceSection">
      <div class="main_title">
        <h2>Our Services</h2>
        <p>Nec graeci sadipscing disputationi ne, mea ea nonumes percipitur. Nonumy ponderum oporteat cu mel, pro movet
          cetero at.</p>
      </div>
      <div class="row">

        {{-- @foreach($frontendServiceData as $singleFSD)
        @if(isset($singleFSD) && $singleFSD != null)
        <div class="col-lg-3 col-md-6 h-100">
          @if(isset($singleFSD->service_image) && $singleFSD->service_image != null)
          <a href="{{ asset('uploads/service/thumbnail/'.$singleFSD->service_image) }}" class="box_cat_home h-100" data-rel="lightcase">
          @else
         <a href="{{ asset('backend/template-assets/') }}/images/img_preview.png" class="box_cat_home h-100"
          data-rel="lightcase">
          @endif
            <i class="icon-info-4"></i>
            @if(isset($singleFSD->service_image) && $singleFSD->service_image != null)
            <img src="{{asset('uploads/service/thumbnail/'.$singleFSD->service_image)}}" class="custom-service-img" height="200" alt="">
            @else
            <img src="{{ asset('backend/template-assets/') }}/images/img_preview.png" class="custom-service-img" height="200" alt="">
            @endif
            <h3 class="custom-font-common-color fw-bolder custom-service-font">{{$singleFSD->service_name}}</h3>
            <p class="mb-0">{{$singleFSD->service_details}}</p>
          </a>

        </div>
        @endif
        @endforeach --}}
        
        {{-- <div class="col-lg-3 col-md-6 h-100">
          <a href="javascript:void(0)" class="box_cat_home h-100">
            <i class="icon-info-4"></i>
            <img src="{{asset('frontend')}}/images/services/2.jpg" class="custom-service-img" height="200" alt="">
            <h3 class="custom-font-common-color fw-bolder custom-service-font">Facial Theraphy</h3>
          </a>
        </div> --}}
        
      </div>
      <!-- /row -->
    </div>
  </div>
  <!-- /container -->
  
  <!-- Photo Gallery -->
  <div class="container py-5 custom-photo-gallery-block">
    <div class="container" id="serviceSection">
      <div class="main_title">
        <h2>Photo Gallery</h2>
        <p>Tiayra Photo Gallery: Where every image finds its stage to shine, captivating viewers with its seamless elegance and
        empowering creators to share their artistry with the world</p>
      </div>
      
      <div class="owl-slider">
        <div id="carousel" class="owl-carousel">

          @foreach(range(1, 11) as $index)
          <div class="item">
            <a href="{{ asset('frontend/images/photoGallery/'.$index.'.jpg') }}" data-rel="lightcase">
            <img src="{{ asset('frontend/images/photoGallery/'.$index.'.jpg') }}" alt="Image {{ $index }}">
            </a>
          </div>
          @endforeach
          
        </div>
      </div>
    </div>
    <!-- /carousel -->
  </div>
  <!-- /Photo Gallery -->
  
  <!-- Testimonial -->
  <div class="bg_color_1 py-5 custom-photo-gallery-block custom-testimonial-block">
    <div class="main_title">
      <h2>Testimonial</h2>
    </div>
  
    <div id="carouselExampleDark" class="container carousel carousel-dark slide py-4">
      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="10000">
          <div class="text-center w-75 mx-auto h-100">
            <p>The perfect look comes with the perfect care and Tiayra have it for me. Not once but took their several
              services and I
              am really pleased. Wishing them go higher as they deserve.</p>
  
            <div class="profile-block-for-test d-flex justify-content-center">
              <a href="{{asset('frontend')}}/images/photoGallery/6.jpg" data-rel="lightcase">
                <img src="{{asset('frontend')}}/images/photoGallery/6.jpg" class="rounded-circle" height="60"
                  width="60" alt="Profile Image">
              </a>
              <div class="right text-start px-3 my-auto">
                <p class="testimonial-author mb-0 fw-bolder text-primary">- Maymuna Binte Manir</p>
                <p class="testimonial-author">Student</p>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item" data-bs-interval="2000">
          <div class="text-center w-75 mx-auto h-100">
            <p>It was really a fantastic experience and they care so well that fades away any confusion or afraid.</p>
  
            <div class="profile-block-for-test d-flex justify-content-center">
              <a href="{{asset('frontend')}}/images/photoGallery/1.jpg" data-rel="lightcase">
                <img src="{{asset('frontend')}}/images/photoGallery/1.jpg" class="rounded-circle" height="60"
                  width="60" alt="Profile Image">
              </a>
              <div class="right text-start px-3 my-auto">
                <p class="testimonial-author mb-0 fw-bolder text-primary">- Arika Jaman</p>
                <p class="testimonial-author">Homemaker</p>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="text-center w-75 mx-auto h-100">
            <p>My job is to fly high to all directions and so my skin goes on roughness and several environmental problem.
              I took
              services of Tiayra and now I am confident about my glow even after going under roughness.</p>
  
            <div class="profile-block-for-test d-flex justify-content-center">
              <a href="{{asset('frontend')}}/images/photoGallery/4.jpg" data-rel="lightcase">
                <img src="{{asset('frontend')}}/images/photoGallery/4.jpg" class="rounded-circle" height="60"
                  width="60" alt="Profile Image">
              </a>
              <div class="right text-start px-3 my-auto">
                <p class="testimonial-author mb-0 fw-bolder text-primary">- Jennyfer Jinia</p>
                <p class="testimonial-author">Traveller</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Carousel dots -->


      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  
    <!-- /carousel -->
  </div>
  <!-- /Testimonial -->

  <!-- Video Gallery -->
  <div class="container my-5 custom-photo-gallery-block">
    <div class="container" id="serviceSection">
      <div class="main_title">
        <h2>Video Gallery</h2>
      </div>
  
      <div class="row">
        <div class="col-md-4 h-100">
          <div class="card p-2 custom-200px">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/8pvZ4VtKv9g" frameborder="0"
              allowfullscreen></iframe>
          </div>
        </div>
        <div class="col-md-4 h-100">
          <div class="card p-2 custom-200px">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/OWu3OAh4N8k" frameborder="0"
              allowfullscreen></iframe>
          </div>
        </div>
        <div class="col-md-4 h-100">
          <div class="card p-2 custom-200px">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/KAtGEYo2Zi8" frameborder="0"
              allowfullscreen></iframe>
          </div>
        </div>
      </div>

      <div class="text-center py-3">
        <a href="javascript:void(0)" class="btn btn-primary">Watch More On Youtube</a>
      </div>
    </div>
    <!-- /carousel -->
  </div>
  <!-- /Video Gallery -->

  <div id="hero_register">
    <div id="appoinmentSection" class="container py-5">
      <div class="row">
        <div class="col-lg-6">
          <h1>It's time to Appointment</h1>
          <p class="lead">Demo Text</p>
          <div class="box_feat_2">
            <i class="pe-7s-clock"></i>
            <h3>Open Hours:</h3>
            <p>Sunday-Friday: 09:00 AM - 10:00 PM.</p>
          </div>
          <div class="box_feat_2">
            <i class="pe-7s-mail-open-file"></i>
            <h3>Email:</h3>
            <p>info@Untree.co</p>
          </div>
          <div class="box_feat_2">
            <i class="pe-7s-phone"></i>
            <h3>Call:</h3>
            <p>+88 0173 058 8404</p>
          </div>
        </div>
        <!-- /col -->
        <div class="col-lg-6 ml-auto">
          <div class="box_form m-0 p-0">
            <div class="py-3">
              <h4 class="px-3">Appoinment Here</h4>
              <hr class="p-0 m-0">
            </div>
            <form class="pb-3 px-3" action="#" method="post" enctype="multipart/form-data" onsubmit="return checkValidate()">
              @csrf
              <div class="row">

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="fw-bolder" for="name">Patient Name<span class=" text-danger">*</span> </label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="fw-bolder" for="phone">Patient Phone<span class=" text-danger">*</span> </label>
                    <input type="number" name="phone" id="phone" id="phone" class="form-control" onkeyup="changeSubmitBtn()" placeholder="Phone" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="fw-bolder" for="email">Patient Email<span class=" text-danger"></span> </label>
                    <input type="email" name="email" id="email" id="email" class="form-control" placeholder="Email">
                  </div>
                </div>
                
                <div class="col-md-6 ">
                  <div class="form-group">
                    <label class="fw-bolder" for="name">Appoinment Date<span class=" text-danger">*</span> </label>
                    <input type="text" id="appoinment_date" name="appoinment_date"
                      class="form-control flatpickr-disabled-range custom-date-picker custom-cursor-pointer" 
                      value="{{Carbon\Carbon::parse($todayDate)->format('d-m-Y')}}" placeholder="Date" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="fw-bolder" for="name">Patient Problem<span class=" text-danger">*</span> </label>
                    <textarea class="form-control" cols="2" rows="2" name="problem" id="problem" placeholder="Problem"
                      required></textarea>
                  </div>
                </div>
              </div>
              <!-- /row -->
              <p class="text-center mt-3">
                <input type="submit" id="submitBtn" class="btn_1" value="Submit">
              </p>
              <div class="mt-3" >
                <p class="mb-0 text-success fw-bolder" id="appoinmentSuccessMessage"></p>
                <p class="mb-0 text-danger fw-bolder" id="appoinmentErrorMessage"></p>
                <p id="appoinSerialText"></p>
              </div>
            </form>
          </div>
          <!-- /box_form -->
        </div>
        <!-- /col -->
      </div>
      <!-- /row -->
    </div>
  </div>
  <!-- /app_section -->
</main>

@endsection

@section('scripts')
<script>
  $(document).ready(function(){
        $("#branch_id").select2();
        $("#provider_id").select2();
        $("#service_id").select2();
        $("#gender").select2();
    });

  //For date & time section...
  $("#appoinment_date").flatpickr({
    allowInput: true,
    dateFormat: "d-m-Y",
	});

  $("#appoinment_time").flatpickr({
    enableTime: true,
    noCalendar: true,
    allowInput: true
  });

  //To change submit button...
  function changeSubmitBtn(){
    $("#submitBtn").removeClass('bg-danger');
    $("#submitBtn").removeClass('bg-success');
    $("#submitBtn").addClass('bg-custom-success');
    $("#submitBtn").val('Submit');
    $("#submitBtn").prop('disabled', false);
    $("#appoinmentErrorMessage").text('');
    $("#appoinmentSuccessMessage").text('');
    $("#appoinSerialText").text('');
  }

  //To check validate...
  function checkValidate() {
      event.preventDefault();
  }


  jQuery("#carousel").owlCarousel({
    autoplay: true,
    rewind: true, /* use rewind if you don't want loop */
    margin: 20,
    /*
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    */
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    nav: true,
    responsive: {
      0: {
        items: 1
      },

      600: {
        items: 3
      },

      1024: {
        items: 4
      },

      1366: {
        items: 4
      }
    }
  });
</script>
@endsection