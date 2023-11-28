jQuery(document).ready(function() {
    _loadFaq();
});

const _loadFaq = () => {
	$.ajax({
        url: BASE_URL+ "/ajax_load_faq",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
           $('#sectionFaq').html(data);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
