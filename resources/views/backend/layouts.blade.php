<!DOCTYPE html>
@php
    $profiles = App\Models\ProfileApp::where('id', 1)->first();
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="robots" content="noindex, nofollow" />
        <title>{{ $title }} - {{ $profiles->nama_alias }}</title>
        <!-- Meta -->
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

        @include('backend.partials.styles')
    </head>
    <body id="kt_app_body" data-kt-app-page-loading-enabled="true"
        data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
        data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
        data-kt-app-sidebar-push-footer="true" class="app-default">
        <!--begin::Theme mode setup on page load || data-kt-app-page-loading="on"-->
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
        <!--end::Theme mode setup on page load-->
        <!--begin::loader--
        <div class="app-page-loader">
            <span class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </span>
        </div>
        <!--end::Loader-->
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
                @include('backend.partials.header')
                <!--begin::Wrapper-->
                <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                    @if (session()->get('key_level') == 'silab-administrator')
                        @include('backend.partials.sidebar_admin')
                    @else
                        @include('backend.partials.sidebar_kanit')
                    @endif
                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-xxl ">
                                    <!-- Body Content start -->
                                    @yield('content')
                                    <!-- Body Content end -->
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        @include('backend.partials.footer')
                    </div>
                    <!--end:::Main-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="bi bi-chevron-up text-light"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::App-->
        @include('backend.partials.scripts')
    </body>
</html>