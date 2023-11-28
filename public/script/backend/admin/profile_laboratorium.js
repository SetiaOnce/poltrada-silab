"use strict";
// Class Definition
//Profile laboratorium Input
$('#profile_laboratorium').summernote({
    placeholder: 'Isi deskripsi profile laboratorium ...',
    height: 600, minHeight: null, maxHeight: null, dialogsInBody: false, focus: false,
    callbacks: {
        onImageUpload: function(image) {
            var target = document.querySelector('#cardProfile'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy(), _uploadFile_editor(image[0], '#profile_laboratorium'), blockUi.release(), blockUi.destroy();
        }
    }
});
// load profile laboratorium
const _loadProfileLaboratorium = () => {
    $("#form-editProfileLaboratorium")[0].reset(), $('#profile_laboratorium').summernote('code', '');
    let target = document.querySelector('#cardProfile'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: BASE_URL+ "/app_admin/ajax/load_profile_laboratorium",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            //Summernote profile laboratorium
            let profile_laboratorium = data.profile;
            $('#profile_laboratorium').summernote('code', profile_laboratorium);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
            blockUi.release(), blockUi.destroy();
        }
    });
}
// Handle Button Reset / Batal Form profile laboratorium
$('#btn-resetFormSiteInfo').on('click', function (e) {
    e.preventDefault();
    _loadProfileLaboratorium();
});

//Handle Enter Submit Form Edit profile laboratorium
$("#form-editProfileLaboratorium input").keyup(function(event) {
    if (event.keyCode == 13 || event.key === 'Enter') {
        $("#btn-save").click();
    }
});

// Handle Button Save Form profile laboratorium
$('#btn-save').on('click', function (e) {
    e.preventDefault();
    $('#btn-save').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
    let profile_laboratorium = $('#profile_laboratorium');

    if (profile_laboratorium.summernote('isEmpty')) {
        toastr.error('Profile laboratorium masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        profile_laboratorium.summernote('focus');
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
            let target = document.querySelector('#cardProfile'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($('#form-editProfileLaboratorium')[0]), ajax_url= BASE_URL+ "/app_admin/ajax/profile_laboratorium_update";
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
                        Swal.fire({title: "Success!", text: 'Profile laboratorium sukses diperbarui..', icon: "success", allowOutsideClick: false}).then(function (result) {
                            _loadProfileLaboratorium();
                        });
                    }else{
                        if(data.pesan_code=='format_inputan') {   
                            Swal.fire({title: "Ooops!", text: data.pesan_error[0], icon: "warning", allowOutsideClick: false});  
                        } else {
                            Swal.fire({title: "Ooops!", text: data.pesan_error, icon: "warning", allowOutsideClick: false});    
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
    _loadProfileLaboratorium();
});