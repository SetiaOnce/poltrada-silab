<!--begin::Fonts-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Global Stylesheets Bundle(used by all pages)-->
<link href="{{ asset('/dist/plugins/global/plugins.bundle.css') }}" rel="stylesheet" />
<link href="{{ asset('/dist/css/style.bundle.css') }}" rel="stylesheet" />
<!--end::Global Stylesheets Bundle-->
<!--begin::Load Other css -->
<link href="{{ asset('/dist/plugins/pace/themes/green/pace-theme-flash.css') }}" rel="stylesheet" />
<!--end::Load Other css -->
<!--begin::Custom Stylesheets Bundle-->
<link href="{{ asset('/dist/css/style.backend.init.css') }}" rel="stylesheet" />
<!--end::Custom Stylesheets Bundle-->
<link rel="stylesheet" href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}">
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<!-- Base Route JS -->
<script src="{{ asset('/dist/js/base_route.js') }}"></script>
<script>
    var BASE_URL = "{{url('/')}}";
</script> 