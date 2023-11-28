"use strict";
// Class Initialization
jQuery(document).ready(function() {
    _loadSideWidgetBuku();
    $('.image-popup').magnificPopup({
        type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
        image: {
            verticalFit: true
        }
    });
    $('#listDataExemplar').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Sedang memuat foto #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: false,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">Foto #%curr%</a> tidak dapat dimuat...',
            titleSrc: function(item) {
                return item.el.attr('title') + '<small>'+item.el.attr('subtitle')+'</small>';
            }
        }
    });
});
//load side widget
const _loadSideWidgetBuku = () => {
    $.ajax({
        url: BASE_URL+ "/ajax_load_side_widget_buku",
        type: "GET",
        dataType: "JSON",
        data: {
            no_isbn,fid_kategori
        },success: function (data) {
            $('#sectionSideWidget').html(data);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}