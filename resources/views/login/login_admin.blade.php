<!DOCTYPE html>
@php
$profiles = App\Models\ProfileApp::where('id', 1)->first();
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Login Sistem Laboratorium - {{ $profiles->nama_alias }}</title>
        <!-- Meta -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <meta name="description" content="{{ $profiles->deskripsi }}" />
        <meta name="keywords" content="{{ $profiles->keyword }}" />
        <meta name="author" content="@Yogasetiaonce" />
        <meta name="email" content="gedeyoga1126@gmail.com" />
        <meta name="website" content="{{ url('/') }}" />
        <meta name="Version" content="1" />
        <meta name="docsearch:language" content="id">
        <meta name="docsearch:version" content="1">
        <link rel="canonical" href="{{ url('/') }}">
        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="manifest" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="mask-icon" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}" color="#6CC4A1">
        <meta name="msapplication-TileColor" content="#b91d47">
        <meta name="theme-color" content="#6CC4A1">
        <meta name="application-name" content="{{ $profiles->nama_alias }}">
        <meta name="msapplication-TileImage" content="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="HandheldFriendly" content="true" />
        <!-- Twitter -->
        <meta name="twitter:widgets:csp" content="on">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:url" content="{{ url('/') }}">
        <meta name="twitter:site" content="{{ $profiles->nama_alias }}">
        <meta name="twitter:creator" content="@Yogasetiaonce">
        <meta name="twitter:title" content="{{ $profiles->nama_alias }}">
        <meta name="twitter:description" content="{{ $profiles->deskripsi }}">
        <meta name="twitter:image" content="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <!-- Facebook -->
        <meta property="og:locale" content="id_ID" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="{{ $profiles->nama_alias }}">
        <meta property="og:description" content="{{ $profiles->deskripsi }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1000">
        <meta property="og:image:height" content="500">

		<!-- BEGIN: Theme CSS-->
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/bootstrap.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/bootstrap-extended.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/colors.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/components.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/themes/dark-layout.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/themes/bordered-layout.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/themes/semi-dark-layout.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/plugins/forms/form-validation.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/css/pages/page-auth.css')}}"> 
		<!-- BEGIN: Custom CSS-->
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/assets/css/style.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/app-assets/vendors/css/extensions/toastr.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('dist/login/css/ext.css')}}">
		<!-- END: Custom CSS-->
		<script>
			var BASE_URL = "{{url('/')}}";
		</script> 
    </head> 
	<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
		<header>   
            <nav class="navbar navbar-expand-lg navbar-light" style="background:#253b80 none repeat scroll 0 0"> 
                @if((new \Jenssegers\Agent\Agent())->isMobile())
                <div class="container">
                    <div class="col-md-4">
                        <a class="navbar-brand" href="{{url('/')}}">
                            <div class="text-center">
                                <img src="{{asset('dist/img/Logo-Final-03.png')}}" alt="Logo" class="d-inline-block align-text-top">
                            </div>
                          <div id="site-identity">
                            <div class="site-title">Politeknik Transportasi Darat Bali</div> 
                            <div class="site-description">Badan Layanan Umum Poltrada Bali</div>
                          </div>
                        </a>
                    </div> 
                </div> 
                @else
                <div class="container">
                    <div class="col-md-4">
                        <a class="navbar-brand" href="{{url('/')}}">
                          <img src="{{asset('dist/img/Logo-Final-03.png')}}" alt="Logo" class="d-inline-block align-text-top float-left p-1">
                          <div id="site-identity">
                            <div class="site-title">Politeknik Transportasi Darat Bali</div> 
                            <div class="site-description">Badan Layanan Umum Poltrada Bali</div>
                          </div>
                        </a>
                    </div> 
                </div> 	
                @endif
            </nav> 
        </header>
      
        
		<section class="vh-100">
            <div class="container h-custom" style="min-height:550px;">
                <div class="row d-flex justify-content-center align-items-center h-100 mt-4">
                    <div class="col-md-8 col-lg-6 col-xl-4"> 
                        <form id="form-data" class="form-horizontal" onsubmit="return false">
                        @csrf 
                        <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start mb-2">
                            <h3>Selamat datang,
                            <br>
                            di Aplikasi <b>SILAB</b> Poltrada Bali
                            </h3>
                        </div>
                
                        <!-- Email input -->
                        <div class="form-outline mb-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="email" name="email" id="email" class="form-control form-control-lg" required autocomplete="off" placeholder="Masukkan email.." value="poltradadev@email.com">
                                </div>
                            </div>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-2"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group input-group-merge form-password-toggle">
                                            <input type="password" name="password" id="password" class="form-control form-control-lg" required autocomplete="off" placeholder="Masukkan Password..">
                                            <div class="input-group-append">
                                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                            </div>
                                        </div> 
                                    </div> 
                                </div>
                            </div>
                        </div>
                
                        <div class="form-outline mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <p id="captchaShow">{!! captcha_img() !!}</p>
                                    </div>    
                                </div>
                                <div class="col-md-6">
                                <input type="text" class="form-control  form-control-lg" id="captcha" name="captcha" value="{{ old('captcha') }}" required autocomplete="captcha" placeholder="Masukkan captcha..." autofocus aria-describedby="login-captcha" tabindex="1" autofocus />
                                    <a href="javascript:void(0)" id="refreshcaptcha" class="text-primary"><span class="fa fa-refresh fa-spin"></span> <small>Refresh captcha</small></a>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-block btn-lg text-white" id="btn-save" style="background:#253b80 none repeat scroll 0 0; border-color:#253b80" tabindex="4">
                            MASUK KE APLIKASI
                        </button> 

                        </form>
                    </div>
                    <div class="col-md-9 col-lg-6 col-xl-5 text-center">
                        <div id="dt-banner">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center text-md-start justify-content-between py-4 px-4 px-xl-5" style="background:#253b80 none repeat scroll 0 0"> 
                <div class="text-white mb-3 mb-md-0" id="footer-login">
                </div> 
            </div>
        </section> 
        
		<!-- BEGIN: Vendor JS-->
		<script src="{{asset('dist/login/app-assets/vendors/js/vendors.min.js')}}"></script>
		<!-- BEGIN Vendor JS--> 

		<script src="{{asset('dist/login/app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
		<!-- BEGIN: Theme JS-->
		<script src="{{asset('dist/login/app-assets/js/core/app-menu.js')}}"></script>
		<script src="{{asset('dist/login/app-assets/js/core/app.js')}}"></script>
		<!-- END: Theme JS--> 
		<script src="{{ asset('dist/login/sweetalert2/sweetalert2.all.min.js') }}"></script>
		<script src="{{ asset('script/login/login_admin.js')}}"></script> 
    </body>
</html>
