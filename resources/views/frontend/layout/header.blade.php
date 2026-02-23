<div class="layer"></div>
<!-- Mobile menu overlay mask -->
<header class="header_sticky">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div id="logo_home">
                    <a href="#" title="Tiayra">
                        <img src="{{asset('frontend')}}/images/logo.png" class="custom-homepage-logo" alt="">
                    </a>
                </div>
            </div>
            <nav class="col-lg-9 col-6">
                <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="#0"><span>Menu mobile</span></a>
                <div class="main-menu">
                    <ul>
                        <li class="submenu">
                            <a href="#" class="show-submenu">Home</a>
                        </li>
                        <li class="submenu">
                            <a href="#branchSection" class="show-submenu">Branch</a>
                        </li>
                        <li class="submenu">
                            <a href="#doctorsSection" class="show-submenu">Doctors</a>
                        </li>
                        <li class="submenu">
                            <a href="#serviceSection" class="show-submenu">Service</a>
                        </li>
                        
                        <li class="submenu">
                            <a href="#appoinmentSection" class="show-submenu">Appointment</a>
                        </li>
                        
                        <li class="submenu">
                            <a href="#contactSection" class="show-submenu">Contact Us</a>
                        </li>
                        
                        <li class="submenu">
                            @if(Auth::guard('webuser')->check())
                            <!-- Show "Log Out" if the user is authenticated -->
                            <a href="{{ route('webuser.logout') }}" class="show-submenu">Log Out</a>
                            @else
                            <!-- Show "Login" if the user is not authenticated -->
                            <a href="{{ route('webuser.get-login') }}" class="show-submenu">Login</a>
                            @endif
                        </li>
                    </ul>
                </div>
                <!-- /main-menu -->
            </nav>
        </div>
    </div>
    <!-- /container -->
</header>
<!-- /header -->