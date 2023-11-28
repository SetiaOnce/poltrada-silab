//Class Definition
var save_method;
var table;
//Load Datatables banner
const _loadDataRak = () => {
    table = $('#dt-rak').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_rak",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'kode_rak', name: 'kode_rak'},
            { data: 'status', name: 'status'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "10%", "targets": 0, "className": "align-top text-center" },
            { "width": "60%", "targets": 1, "className": "align-top" },
            { "width": "20%", "targets": 2, "className": "align-top text-center" },
            { "width": "10%", "targets": 3, "className": "align-top text-center" },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><div class="blockui-message"><span class="spinner-border text-primary align-middle me-2"></span> Mohon Tunggu...</div></div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-transparent" data-width="fit" data-style="btn-sm btn-secondary" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            //Custom Table
            $("#dt-rak_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
        },
    });
    $("#dt-rak").css("width", "100%");
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_rak') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset();
        $('[name="id"]').val(""), $('#iGroup-Status').hide();
    } else {
        let id = $('[name="id"]').val();
        _editData(id);
    }
}
//Add data
const _addData = () => {
    save_method = "add_data";
    _clearForm(),
    $("#card-form .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Rak</h3>`
    ),
    $("#card-data").hide(), $("#card-form").show();
}
//Edit data
const _editData = (idp) => {
    save_method = "update_data";
    let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/rak_edit',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id),

                $('#kode_rak').val(data.row.kode_rak);
                //Status
                if (data.row.status == 1) {
                    $('#status').prop('checked', true),
                    $('#iGroup-Status .form-check-label').text('AKTIF');
                } else {
                    $('#status').prop('checked', false),
                    $('#iGroup-Status .form-check-label').text('TIDAK AKTIF');
                }
                $('#iGroup-Status').show();
                $("#card-form .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Rak</h3>`
                ),
                $("#card-data").hide(), $("#card-form").show();
            } else {
                Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log("load data is error!");
            Swal.fire({
                title: "Ooops!",
                text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                icon: "error",
                allowOutsideClick: false,
            });
        },
    });
}
//Save data by Enter
$("#form-data input").keyup(function (event) {
    event.preventDefault();
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save data Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    var kode_rak = $('#kode_rak');
    
    if (kode_rak.val() == '') {
        toastr.error('Kode rak masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        kode_rak.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }
    
    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_data") {
        textConfirmSave = "Tambahkan data sekarang ?";
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
            let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-data")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/rak_save";
            if(save_method == 'update_data') {
                ajax_url = BASE_URL+ "/app_admin/ajax/rak_update";
            }
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
                        var message = 'Rak berhasil diperbarui'
                        if(save_method == 'add_data'){
                            var message = 'Rak baru berhasil ditambahkan'
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_rak'), _loadDataRak();
                        });
                    } else {
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
//Update Status Data
const _updateStatus = (idp, value) => {
    let textLbl = 'Nonaktifkan';
    if(value=='1') {
        textLbl = 'Aktifkan';
    }
    let textSwal = textLbl+ ' Rak sekarang ?';
    Swal.fire({
        title: "",
        html: textSwal,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: BASE_URL+ "/app_admin/ajax/rak_update_status",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp, value
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        _loadDataRak();
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        _loadDataRak();
                    });
                }
            });
        }
    });
}
//Delete Data
// const _deleteData = (idp) => {
//     Swal.fire({
//         title: "",
//         html: "Hapus Data sekarang?",
//         icon: "question",
//         showCancelButton: true,
//         allowOutsideClick: false,
//         confirmButtonText: "Ya",
//         cancelButtonText: "Tidak, Batalkan!"
//     }).then(result => {
//         if (result.value) {
//             let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
//             blockUi.block(), blockUi.destroy();
//             // Load Ajax
//             $.ajax({
//                 url: BASE_URL+ "/app_admin/ajax/bidang_keilmuan_destroy",
//                 headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
//                 type: "POST",
//                 dataType: "JSON",
//                 data: {
//                     idp
//                 }, success: function (data) {
//                     blockUi.release(), blockUi.destroy();
//                     Swal.fire({ title: "Success!", html: "Data berhasil dihapus", icon: "success", allowOutsideClick: false }).then(function (result) {
//                         _loadDataRak();
//                     });
//                 }, error: function (jqXHR, textStatus, errorThrown) {
//                     blockUi.release(), blockUi.destroy();
//                     Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
//                         console.log("Update data is error!");
//                         _loadDataRak();
//                     });
//                 }
//             });
//         }
//     });
// }
//Class Initialization
jQuery(document).ready(function() {
    _loadDataRak();

    //status changer
    $('#status').change(function() {
        if(this.checked) {
            $('#iGroup-Status .form-check-label').text('AKTIF');
        }else{
            $('#iGroup-Status .form-check-label').text('TIDAK AKTIF');
        }
    });
});