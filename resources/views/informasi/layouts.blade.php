@php
$data = App\Models\ProfileApp::where('id', 1)->first();
$app_version = 1.1;
$title = 'Informasi Jadwal Praktek';
$thumb = asset('dist/img/logo/'.$data->backend_logo_icon);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<!--begin::Head-->
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="robots" content="noindex, follow" />
        <title>{{ $title }} - {{ $data->nama }}</title>
        <meta name="description" content="{{ $data->deskripsi }}" />
        <meta name="keywords" content="{{ $data->keyword }}" />
        <meta name="author" content="Yoga Setiaonce" />
        <meta name="email" content="gedeyoga1126@gmail.com" />
        <meta name="website" content="{{ url('/') }}" />
        <meta name="Version" content="{{ $app_version }}" />
        <meta name="docsearch:language" content="id">
        <meta name="docsearch:version" content="{{ $app_version }}">
        <link rel="canonical" href="{{ url('/') }}">
        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ $thumb }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ $thumb }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ $thumb }}">
        <link rel="manifest" href="{{ $thumb }}">
        <link rel="mask-icon" href="{{ $thumb }}" color="#6CC4A1">
        <meta name="msapplication-TileColor" content="#b91d47">
        <meta name="theme-color" content="#6CC4A1">
        <meta name="application-name" content="{{ $data->nama }}">
        <meta name="msapplication-TileImage" content="{{ $thumb }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="HandheldFriendly" content="true" />
        <!-- Twitter -->
        <meta name="twitter:widgets:csp" content="on">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:url" content="{{ url('/') }}">
        <meta name="twitter:site" content="{{ $data->nama }}">
        <meta name="twitter:creator" content="@yogasetiaonce">
        <meta name="twitter:title" content="{{ $title }} - {{ $data->nama }}">
        <meta name="twitter:description" content="{{ $data->deskripsi }}">
        <meta name="twitter:image" content="{{ $thumb }}">
        <!-- Facebook -->
        <meta property="og:locale" content="id_ID" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="{{ $title }} - {{ $data->nama }}">
        <meta property="og:description" content="{{ $data->deskripsi }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ $thumb }}">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:width" content="1000">
        <meta property="og:image:height" content="500">
		@include('informasi.partials.styles')
        <style>
            video {
                position: absolute;
                object-fit: cover;
                width: 100%;
                height: 100%;
                z-index: -1;
            }
        </style>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
            @include('informasi.partials.header')
			<!--begin::Authentication - Sign-in -->
			<div id="bg-login" class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background: linear-gradient(0deg, rgb(21 33 26), #ffffff8f);">
                <video autoplay='' muted='' loop='' playsinline='' preload='metadata' poster="{{ asset('dist/img/background-poltrada.jpg') }}" id="videoBackground" src="{{ asset('dist/video-backgroun.mp4') }}"  type='video/mp4'></video>
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-5 pb-lg-10">
                    <!-- Body Content start -->
                    @yield('content')
                    <!-- Body Content end -->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - Sign-in-->
            <!--begin::Container-->
            <div class="d-flex flex-column align-items-center justify-content-center" style="background: #274472;">
                <!--begin::Copyright-->
                <div class="text-white order-2 order-md-1 copyRight">
                    {!! $data->copyright !!}
                </div>
                <!--end::Copyright-->
            </div>
            <!--end::Container-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
        @include('informasi.partials.scripts')
	</body>
	<!--end::Body-->
</html>