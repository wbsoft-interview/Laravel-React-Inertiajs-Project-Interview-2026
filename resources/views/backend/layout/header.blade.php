<div class="top-navbar
">
    <nav class="navbar navbar-expand justify-content-between">
        <div class="container-fluid">

            <a class="d-inline-block d-lg-none ml-auto more-button">
                <span class="material-symbols-outlined">menu</span>
            </a>

            <a id="sidebarCollapse" class=" d-lg-block d-none position-relative">
                <span class="material-symbols-outlined">menu</span>
            </a>

            <a class=" navbar-brand d-lg-block  d-none" href="{{route('admin.dashboard')}}"> Dashboard</a>



            <div class="collapse navbar-collapse justify-content-end custom-header-menu-icon" id="navbarSupportedContent">
                <ul class="nav navbar-nav ml-auto">
                    <!-- notification section start     -->
                    <li class="nav-item active">
                        <a href="javascript:void(0)" class="nav-link" data-bs-toggle="modal"
                        data-bs-target="#filterClient">
                            <span class="material-symbols-outlined">group</span>
                        </a>
                    </li>
                    <!-- notification section end     -->
                    
                    <!-- notification section start     -->
                    <li class="nav-item active">
                        <a href="#" class="nav-link">
                            <span class="material-symbols-outlined">storefront</span>
                        </a>
                    </li>
                    <!-- notification section end     -->



                    <!-- shortcut section start  -->
                    <li class="nav-item dropdown shortcut">
                        <a class="nav-link" href="#" data-bs-toggle="dropdown">
                            <span class="material-symbols-outlined">apps</span>
                        </a>
                        <ul class="dropdown-menu shortcut overflow-hidden p-0">
                            <li class="navbar px-4">
                                <span class="text">Shortcut</span>
                                <span class="material-symbols-outlined"> dataset </span>
                            </li>
                            <hr class="m-0">
                            <li class="row m-0">
                                <div class="col-6 border text-center py-2">
                                    <a href="#">
                                        <p class="text m-0"><span class="material-symbols-outlined"> list </span>
                                        </p>
                                        <p class="m-0 super-small"><small>Category</small> </p>
                                    </a>
                                </div>
                                <div class="col-6 border text-center py-2">
                                    <a href="#">
                                        <p class="text m-0"><span class="material-symbols-outlined"> medical_information </span>
                                        </p>
                                        <p class="m-0 super-small"><small>Service</small> </p>
                                    </a>
                                </div>
                            </li>
                            <li class="row m-0">
                                <div class="col-6 border text-center py-2">
                                    <a href="#">
                                        <p class="text m-0"><span class="material-symbols-outlined"> group </span>
                                        </p>
                                        <p class="m-0 super-small"><small>Client</small> </p>
                                    </a>
                                </div>
                                <div class="col-6 border text-center py-2">
                                    <a href="#">
                                        <p class="text m-0"><span class="material-symbols-outlined"> group </span>
                                        </p>
                                        <p class="m-0 super-small"><small>Provider</small> </p>
                                    </a>
                                </div>
                            </li>
                            <li class="row m-0">
                                <div class="col-6 border text-center py-2">
                                    <a href="#">
                                        <p class="text m-0"><span class="material-symbols-outlined"> payments </span>
                                        </p>
                                        <p class="m-0 super-small"><small>Payment Type</small> </p>
                                    </a>
                                </div>
                                <div class="col-6 border text-center py-2">
                                    <a href="#">
                                        <p class="text m-0"><span class="material-symbols-outlined"> meeting_room </span>
                                        </p>
                                        <p class="m-0 super-small"><small>Branch</small> </p>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- shortcut section end  -->


                    <!-- profile section start  -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            @if(Auth::user()->image != null)
                            <img src="{{ Auth::user()->image }}" alt="profile"
                                class="rounded-circle" width="30" height="30">
                            @else
                            <img src="{{ asset('backend/template-assets') }}/images/avator.jpg" alt="profile"
                                class="rounded-circle" width="30" height="30">
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('admin.profile') }}" class="d-flex align-items-center">
                                    @if(Auth::user()->image != null)
                                    <img src="{{ Auth::user()->image }}"
                                        class="rounded-circle me-2" alt="" width="30" height="30">
                                    @else
                                    <img src="{{ asset('backend/template-assets') }}/images/avator.jpg"
                                        class="rounded-circle me-2" alt="" width="30" height="30">
                                    @endif

                                    <div class="ms-2">
                                        <p class="text m-0">{{Auth::user()->name}}</p>
                                        <p class="m-0"><small>{{Auth::user()->role}}</small> </p>
                                    </div>
                                </a>
                            </li>
                            <hr>
                            <li class="nav-item">
                                <a href="{{ route('admin.profile') }}">
                                    <span class="material-symbols-outlined"> person </span>
                                    <span class="text">Profile</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.logout') }}">
                                    <span class="material-symbols-outlined"> logout </span>
                                    <span class="text">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- profile section end  -->
                </ul>
            </div>
        </div>
    </nav>
</div>