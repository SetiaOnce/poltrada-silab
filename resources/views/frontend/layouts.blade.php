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
        <link rel="canonical" href="">
        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="manifest" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
        <link rel="mask-icon" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}" color="#6CC4A1">
        <meta name="msapplication-TileColor" content="#b91d47">
        <meta name="theme-color" content="#6CC4A1">
        <meta name="application-name" content="{{ $profiles->nama_alias }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="HandheldFriendly" content="true" />
        @include('frontend.partials.styles')
    </head>
    <body id="kt_app_body" data-kt-app-layout="light-header" data-kt-app-header-fixed="false" data-kt-app-toolbar-enabled="true"  class="app-default">
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
                @include('frontend.partials.header')
                <!--begin::Wrapper-->
                <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
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
                                    @include('frontend.partials.kebijakan_aplikasi')
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Content wrapper-->
                        @include('frontend.partials.footer')
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
        @include('frontend.partials.scripts')
    </body>
</html>