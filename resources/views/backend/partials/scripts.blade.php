<!--begin::Javascript-->
<script>
    var hostUrl = "{{ asset('/dist/') }}";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('/dist/plugins/global/plugins.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/js/scripts.bundle.v817.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js') }}"></script>
<!--begin::Ads Javascript Bundle-->
<script type="text/javascript" src="{{ asset('/dist/plugins/pace/pace.min.js') }}"></script>
<!--end::Ads Javascript Bundle-->
<script src="{{ asset('/dist/js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('/dist/js/jquery.mask.min.js') }}"></script>

@if (session()->get('key_level') == 'silab-administrator')
<script src="{{ asset('script/backend/notifikasi.js') }}"></script>
@endif
@yield('js')
<script src="{{ asset('/dist/js/backend_app.init.js') }}"></script>
<!--end::Vendors Javascript-->
<!--end::Javascript-->