"use strict";
// Class Definition
//isi kebijaan Input
$('#isi_kebijakan').summernote({
    placeholder: 'Isi kebijakan aplikasi ...',
    toolbar: [
        ['style', ['bold', 'italic', 'underline']], ['insert', ['link']], ['view', ['codeview']],
        ['para', ['ul', 'ol']]
    ],
    height: 200, minHeight: null, maxHeight: null, dialogsInBody: false, focus: false, popatmouse: false, lang: 'id-ID'
});
//Load File Dropify
const _loadDropifyFile = (url_file, paramsId) => {
    if (url_file == "") {
        let drEvent1 = $(paramsId).dropify({
            defaultFile: '',
        });
        drEvent1 = drEvent1.data('dropify');
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = '';
        drEvent1.destroy();
        drEvent1.init();
    } else {
        let drEvent1 = $(paramsId).dropify({
            defaultFile: url_file,
        });
        drEvent1 = drEvent1.data('dropify');
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = url_file;
        drEvent1.destroy();
        drEvent1.init();
    }
}
//begin::Dropify
$('.dropify-upl').dropify({
    messages: {
        'default': '<span class="btn btn-sm btn-secondary">Drag/ drop file atau Klik disini</span>',
        'replace': '<span class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Drag/ drop atau Klik untuk menimpa file</span>',
        'remove':  '<span class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Reset</span>',
        'error':   'Ooops, Terjadi kesalahan pada file input'
    }, error: {
        'fileSize': 'Ukuran file terlalu besar, Max. ( {{ value }} )',
        'minWidth': 'Lebar gambar terlalu kecil, Min. ( {{ value }}}px )',
        'maxWidth': 'Lebar gambar terlalu besar, Max. ( {{ value }}}px )',
        'minHeight': 'Tinggi gambar terlalu kecil, Min. ( {{ value }}}px )',
        'maxHeight': 'Tinggi gambar terlalu besar, Max. ( {{ value }}px )',
        'imageFormat': 'Format file tidak diizinkan, Hanya ( {{ value }} )'
    }
});
// load kebijakan aplikasi
const _loadKebijakanAplikasi = () => {
    $("#form-kebijakanApp")[0].reset(), $('#isi_kebijakan').summernote('code', '');
    let target = document.querySelector('#cardKebijakanApp'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: BASE_URL+ "/app_admin/ajax/load_kebijakan_aplikasi",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();

            $('#judul').val(data.response.judul);
            // dropify icon image
            _loadDropifyFile(data.url_icon, '#icon_image')
            //Summernote Isi Kebijakan
            let isi_kebijakan = data.response.isi_kebijakan;
            $('#isi_kebijakan').summernote('code', isi_kebijakan);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
//Handle Enter Submit Form 
$("#form-kebijakanApp input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-save").click();
    }
});
// Handle Button Save Form Kebijakan Aplikasi
$('#btn-save').on('click', function (e) {
    e.preventDefault();
    $('#btn-save').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
    let  
    judul = $('#judul'),
    isi_kebijakan = $('#isi_kebijakan'),
    icon_image = $('#icon_image'),
    priview_iconimage = $('#iGroup-iconImage .dropify-preview .dropify-render').html();

    if (judul.val() == '') {
        toastr.error('Judul masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        judul.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    } if (isi_kebijakan.summernote('isEmpty')) {
        toastr.error('Isi kebijakan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        isi_kebijakan.summernote('focus');
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    } if (priview_iconimage == '') {
        toastr.error('Icon Gambar masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-iconImage .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        icon_image.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }
    Swal.fire({
        title: "",
        text: "Simpan perubahan sekarang ?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#cardKebijakanApp'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($('#form-kebijakanApp')[0]), ajax_url= BASE_URL+ "/app_admin/ajax/kebijakan_aplikasi_update";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();

                    if (data.status==true){
                        Swal.fire({
                            title: "Success!", text: "Kebijakan aplikasi berhasil diperbarui...", icon: "success"
                        }).then(function (result) {
                            // load kebijakan aplikasi
                            _loadKebijakanAplikasi();
                        });
                    }else{
                        if(data.pesan_code=='format_inputan') {   
                            Swal.fire({title: "Ooops!", text: data.pesan_error[0], icon: "warning", allowOutsideClick: false});  
                        } else {
                            Swal.fire("Ooops!", "Gagal melakukan proses data, mohon cek kembali isian pada form yang tersedia.", "error");  
                        }
                    }
                    
                }, error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false});
                }
            });
        }else{
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        }
    });
});

// Class Initialization
jQuery(document).ready(function() {
    _loadKebijakanAplikasi();
});