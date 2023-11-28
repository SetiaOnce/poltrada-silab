"use strict";
// Class Initialization
jQuery(document).ready(function() {
    loadSytem.init();
});

// Class Definition
const loadSytem = function() {
    //load System info app
    const _loadSystemInfo = () => {
        $.ajax({
            url: BASE_URL+ "/ajax_load_site_info",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                let headerLogo = `
                    <img alt="Logo" src="` +data.public_header+ `" class="h-40px app-sidebar-logo-default" />
                `;
                $('#kt_header_public_logo a').html(headerLogo);
                $('#footerCopyright').html(data.copyright);
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    };
    //load kebijakan aplikasi
    const _loadKebijakanAPlikasi = () => {
        $.ajax({
            url: BASE_URL+ "/ajax_load_kebijakan_aplikasi",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#sectionKebijakanAplikasi').html(data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    };
    // Public Functions
    return {
        // public functions
        init: function() {
            _loadSystemInfo(); 
            _loadKebijakanAPlikasi();
        }
    };
}();
