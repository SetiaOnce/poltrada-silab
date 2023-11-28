jQuery(document).ready(function() {
    _loadProfilePerpustakaan();
});

const _loadProfilePerpustakaan = () => {
	$.ajax({
        url: BASE_URL+ "/ajax_load_profile",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('#sectionProfile').html(data.profileLaboratorium);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
