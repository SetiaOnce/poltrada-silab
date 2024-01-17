//Class Definition
var fg_type_action;
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Mohon Tunggu ...</div>';
//Class Initialization
jQuery(document).ready(function() {
    $('.image-popup').magnificPopup({
        type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
        image: {
            verticalFit: true
        }
    });
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
//check login before action of alat peraga
const _checkLogin = (type_action, fid_alat_peraga) => {
    fg_type_action = type_action;
    $.ajax({
        url: BASE_URL+ "/ajax/check_login",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if (data.status==true) {
                $('[name="fid_alat_peraga"]').val(fid_alat_peraga), $('[name="type_action"]').val(type_action);
                // check the action if perawatan or pemeriksaan
                if(type_action == 'PERAWATAN'){
                    var headerTitle = '<i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Perawatan Alat Peraga';
                    $('#form-pemeriksaan').hide(), $('#form-perawatan').show();                 
                }else{
                    var headerTitle = '<i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Pemeriksaan Alat Peraga';
                    $('#form-pemeriksaan').show(), $('#form-perawatan').hide();                                 
                }
                $('#modalAction .modal-title').html(headerTitle);                
                $('#modalAction').modal('show');                
            }else{
                Swal.fire({
                    title: "Oopss!",
                    text: 'Anda belum login pada aplikasi pegawai, silakan lakukan login lalu ulangi langkah ini kembali!',
                    icon: "warning",
                    allowOutsideClick: false,
                }).then(function (result) {
                    window.location.href = "https://pegawai.poltradabali.ac.id/login"; 
                });
            }
        }, complete: function(){
            _loadDropifyFile('', '#foto_perawatan'), _loadDropifyFile('', '#foto_pemeriksaan');
            //FLATPICKER OPTIONS
            var tglPerawatan = $('#tgl_perawatan').val();
            $("#tgl_perawatan").flatpickr({
                defaultDate: tglPerawatan,
                dateFormat: "d/m/Y"
            });
            //FLATPICKER OPTIONS
            var tglPemeriksaan = $('#tgl_pemeriksaan').val();
            $("#tgl_pemeriksaan").flatpickr({
                defaultDate: tglPemeriksaan,
                dateFormat: "d/m/Y"
            });
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Save data Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    if(fg_type_action == 'PERAWATAN'){
        var tgl_perawatan = $('#tgl_perawatan');
        var foto_perawatan = $('#foto_perawatan');
        var preview_foto = $('#iGroup-fotoPerawatan .dropify-preview .dropify-render').html();
        var keterangan = $('#keterangan_perawatan');
        if (tgl_perawatan.val() == '') {
            toastr.error('Tanggal pemeriksaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }if (preview_foto == '') {
            toastr.error('Foto masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            $('#iGroup-fotoPerawatan .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
                $(this).removeClass('border-2 border-danger');
            });
            foto_perawatan.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }if (keterangan.val() == '') {
            toastr.error('Keterangan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            keterangan.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }
        var textConfirmSave = "Simpan perawatan sekarang ?", formData = new FormData($("#form-perawatan")[0])
    }else{
        var tgl_pemeriksaan = $('#tgl_pemeriksaan');
        var foto_pemeriksaan = $('#foto_pemeriksaan');
        var preview_foto = $('#iGroup-fotoPemeriksaan .dropify-preview .dropify-render').html();
        var keterangan = $('#keterangan_pemeriksaan');
        if (tgl_pemeriksaan.val() == '') {
            toastr.error('Tanggal pemeriksaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }if (preview_foto == '') {
            toastr.error('Foto masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            $('#iGroup-fotoPemeriksaan .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
                $(this).removeClass('border-2 border-danger');
            });
            foto_pemeriksaan.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }if (keterangan.val() == '') {
            toastr.error('Keterangan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            keterangan.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }
        var textConfirmSave = "Simpan pemeriksaan sekarang ?", formData = new FormData($("#form-pemeriksaan")[0])
    }

    Swal.fire({
        title: "",
        text: textConfirmSave,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-alat"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy(); 
            var ajax_url = BASE_URL+ "/ajax/save_pemeriksaan_perawatan";
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
                    if (data.status == true) {
                        Swal.fire({title: "Success!",text: data.message, icon: "success", allowOutsideClick: false,
                        }).then(function (result) {
						    location.reload(true);
                        });
                    }else {
                        if(data.pesan_code=='format_inputan') {
                            Swal.fire({
                                title: "Ooops!",
                                html: data.pesan_error[0],
                                icon: "warning",
                                allowOutsideClick: false,
                            });
                        } else {
                            Swal.fire({
                                title: "Ooops!",
                                html: data.pesan_error,
                                icon: "warning",
                                allowOutsideClick: false,
                            });
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        }
    });
});