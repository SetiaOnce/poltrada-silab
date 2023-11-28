<!--begin::Fonts(mandatory for all pages)-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Vendor Stylesheets(used for this page only)-->
<link rel="stylesheet" href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}">
<!--end::Vendor Stylesheets-->
<link rel="stylesheet" href="{{ asset('/dist/icons/material-design-icon/css/materialdesignicons.min.css') }}">
<!--begin::Load Other css -->
<link href="{{ asset('/dist/plugins/pace/themes/green/pace-theme-flash.css') }}" rel="stylesheet" />
<!--end::Load Other css -->
<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="{{ asset('/dist/plugins/global/plugins.bundle.v817.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/dist/css/style.bundle.v817.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/dist/css/style.init.css') }}" rel="stylesheet" type="text/css" />
<!--end::Global Stylesheets Bundle-->
<!-- Base Route JS -->
@yield('css')
<script src="{{ asset('/dist/js/base_route.js') }}"></script>
<script>
    var BASE_URL = "{{url('/')}}";
</script> 