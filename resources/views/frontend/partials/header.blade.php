<div id="kt_app_header" class="app-header " data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false" style="top:0px;" data-kt-sticky-enabled="true">
    <!--begin::Header container-->
    <div class="app-container container-xxl d-flex align-items-stretch justify-content-between " id="kt_app_header_container">
        <!--begin::Logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15" id="kt_header_public_logo">
            <a href="">
                <svg class="bd-placeholder-img rounded w-100 h-40px app-sidebar-logo-default" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#868e96"></rect>
                </svg>
            </a>
        </div>
        <!--end::Logo-->
        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <!--begin::Menu wrapper-->
            <span class="d-flex"></span>
            <!--end::Menu wrapper-->
            
            <!--begin::Navbar-->
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">   
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">         
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ strtolower($activeMenu) == 'beranda' ? 'active' : '' }}" href="{{ url('/') }}" >
                            <span class="menu-icon"><i class="bi bi-house fs-4"></i></span>
                            <span class="menu-title">BERANDA</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> 
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link  {{ strtolower($activeMenu) == 'profile' ? 'active' : '' }}" href="{{ url('/profile') }}" >
                            <span class="menu-icon"><i class="bi bi-info-circle fs-4"></i></span>
                            <span class="menu-title">PROFILE</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> 
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ strtolower($activeMenu) == 'faq' ? 'active' : '' }}" href="{{ url('/faq') }}" >
                            <span class="menu-icon"><i class="bi bi-question-circle fs-4"></i></span>
                            <span class="menu-title">FAQ</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> 
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link {{ strtolower($activeMenu) == 'buku_tamu' ? 'active' : '' }}" href="{{ url('/buku-tamu') }}" >
                            <span class="menu-icon"><i class="bi bi-book fs-4"></i></span>
                            <span class="menu-title">BUKU TAMU</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> 
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ url('/jadwal_praktek/information') }}" target="_blank">
                            <span class="menu-icon"><i class="bi bi-calendar2-event-fill fs-4"></i></span>
                            <span class="menu-title">JADWAL</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item--> 
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a href="{{ url('/login') }}" class="btn btn-info btn-sm ml-2"><i class="mdi mdi-login fs-4"></i> Login</a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->   

                </div>
                <!--end::Menu-->
            </div>

            @if((new \Jenssegers\Agent\Agent())->isMobile())
            <!--Begin::Mobile tollbar-->	
            <div class="row app-navbar flex-shrink-0">
                <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show menu">
                    <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
                        <i class="bi bi-list fs-1"><span class="path1"></span><span class="path2"></span></i>            
                    </div>
                </div>
            </div>
            <!--end::Mobile tollbar-->	
            @endif
            <!--end::Navbar-->	
        </div>
        <!--end::Header wrapper-->            
    </div>
    <!--end::Header container-->
</div>