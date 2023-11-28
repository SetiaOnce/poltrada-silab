//Class Definition
var save_method;
var table;
//Class Initialization
jQuery(document).ready(function() {
    _loadPemeriksaanAlat();
    // selectpicker
    loadSelectpicker_alatperaga();
    //FLATPICKER OPTIONS
    var tglPemeriksaan = $('#tgl_pemeriksaan').val();
    $("#tgl_pemeriksaan").flatpickr({
        defaultDate: tglPemeriksaan,
        dateFormat: "d/m/Y"
    });
    /*************************
        OnChange Fid alat for Form pemeriksaan Alat
    *************************/
    $('#fid_alat_peraga').change(function () {
        var idp = $(this).val();
        if(idp=='' || idp==null){
            $('#dtl_dataAlat').html('');
        }else{
            $('#dtl_dataAlat').html(''), loadDtl_dataAlat(idp);
        }
    });
});
//Load Datatables 
const _loadPemeriksaanAlat = () => {
    table = $('#dt-pemeriksaanAlat').DataTable({
        // searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_pemeriksaan",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            // "data"  : function ( data ) {
            //     data.filterStatus  = $('#filterDtStatus').val();
            //     data.filterKategori  = $('#filterDtKategori').val();
            // }
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
            { data: 'alat_peraga', name: 'alat_peraga'},
            { data: 'tanggal_pemeriksaan', name: 'tanggal_pemeriksaan'},
            { data: 'keterangan', name: 'keterangan'},
            { data: 'foto', name: 'foto'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "35%", "targets": 1, "className": "align-top"},
            { "width": "10%", "targets": 2, "className": "align-top text-center" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "text-center", searchable:false, orderable:false},
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
            $("#dt-pemeriksaanAlat_length select").selectpicker(),
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
    $("#dt-pemeriksaanAlat").css("width", "100%");
}
/*************************
    Custom Select alat peraga
*************************/
function loadSelectpicker_alatperaga() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getalatperaga",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].kode_alat_peraga + ' - ' + data[i].nama_alat_peraga + '</option>';
            }
            $('#fid_alat_peraga').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Get Detail Data Alat
function loadDtl_dataAlat(idp) {
    let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax Load data from ajax
    $.ajax({
        url : BASE_URL+ "/app_admin/ajax/get_data_alat_on_select",
        type: "GET",
        dataType: "JSON",
        data:{
            idp
        },success: function(data)
        {
            if(data.status==true){
                
                var fotoImage = `<div class="d-flex flex-column flex-root mb-3">
                    <span class="opacity-70 mb-2">Foto: </span>
                    <span class="font-weight-bolder"><img src="`+data.url_foto+`" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="foto-alat" /></span>
                </div>`;
                var divDetailAlat = `<div class="col-lg-12">
                <div class="alert alert alert-success d-block fadeIn mb-10" role="alert">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <h3 class="font-weight-normal">Data Alat Peraga</h3>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-column flex-root mb-3">
                                <span class="fs-6 text-gray-400">Kode Alat Peraga: </span>
                                <span class="fs-6 fw-bolder text-gray-600">` +data.row.kode_alat_peraga+ `</span>
                            </div>
                            <div class="d-flex flex-column flex-root mb-3">
                                <span class="fs-6 text-gray-400">Nama Alat Peraga: </span>
                                <span class="fs-6 fw-bolder text-gray-600">` +data.row.nama_alat_peraga+ `</span>
                            </div>
                            <div class="d-flex flex-column flex-root mb-3">
                                <span class="fs-6 text-gray-400">Jumlah: </span>
                                <span class="fs-6 fw-bolder text-gray-600">` +data.row.jumlah+ `</span>
                            </div>
                            <div class="d-flex flex-column flex-root mb-3">
                                <span class="fs-6 text-gray-400">Lokasi: </span>
                                <span class="fs-6 fw-bolder text-gray-600">` +data.lokasi+ `</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            ` +fotoImage+ `
                            <div class="d-flex flex-column flex-root mb-3">
                                <span class="fs-6 text-gray-400">Laboratorium: </span>
                                <span class="fs-6 fw-bolder text-gray-600">` +data.laboratorium+ `</span>
                            </div>
                        </div>
                    </div>
                </div></div>`;
                $('#dtl_dataAlat').html(divDetailAlat);
                blockUi.release(), blockUi.destroy();
            }else{
                blockUi.release(), blockUi.destroy();
                swal.fire("Ooops!", "Terjadi kesalahan periksa koneksi internet lalu coba kembali!", "error");
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            blockUi.release(), blockUi.destroy();
            console.log('Load data is error!');
            swal.fire("Ooops!", "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", "error");
        }
    });
}
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
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_pemeriksaan') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset(), _loadDropifyFile('', '#foto'), $('#fid_alat_peraga').selectpicker('val', '');
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
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Pemeriksaan Alat Peraga</h3>`
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
        url: BASE_URL+ '/app_admin/ajax/pemeriksaan_edit',
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

                $('#keterangan').val(data.row.keterangan);
                // tanggal pemeriksaan
                $("#tgl_pemeriksaan").flatpickr({
                    defaultDate: data.tgl_pemeriksaan,
                    dateFormat: "d/m/Y"
                });
                //fid alat peraga
                $('select[name=fid_alat_peraga]').val(data.row.fid_alat_peraga),
                $('.selectpicker').selectpicker('refresh');
                // load detail alat peraga
                loadDtl_dataAlat(data.row.fid_alat_peraga);
                // file foto
                _loadDropifyFile(data.url_foto, '#foto');
                $("#card-form .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Data Pemeriksaan Alat</h3>`
                ),
                $("#card-data").hide(), $("#card-form").show();
                $('html, body').animate({scrollTop: $("#card-form").offset().top}, 1000); 
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
    var fid_alat_peraga = $('#fid_alat_peraga');
    var tgl_pemeriksaan = $('#tgl_pemeriksaan');
    var foto = $('#foto');
    var preview_foto = $('#iGroup-foto .dropify-preview .dropify-render').html();
    var keterangan = $('#keterangan');
    
    if (fid_alat_peraga.val() == '') {
        toastr.error('Alat peraga masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        fid_alat_peraga.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (tgl_pemeriksaan.val() == '') {
        toastr.error('Tanggal pemeriksaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        tgl_pemeriksaan.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (preview_foto == '') {
        toastr.error('Foto masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-foto .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        foto.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (keterangan.val() == '') {
        toastr.error('Keterangan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        keterangan.focus();
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
            let formData = new FormData($("#form-data")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/pemeriksaan_save";
            if(save_method == 'update_data') {
                ajax_url = BASE_URL+ "/app_admin/ajax/pemeriksaan_update";
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
                        var message = 'Pemeriksaan berhasil diperbarui'
                        if(save_method == 'add_data'){
                            var message = 'Pemeriksaan baru berhasil ditambahkan'
                        }
                        Swal.fire({title: "Success!",text: message, icon: "success", allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_pemeriksaan'), _loadPemeriksaanAlat();
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
//Delete Data data
const _deleteData = (idp) => {
    Swal.fire({
        title: "",
        html: "Hapus pemeriksaan sekarang?",
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
                url: BASE_URL+ "/app_admin/ajax/pemeriksaan_destroy",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: 'Data pemeriksaan berhasil dihapus...', icon: "success", allowOutsideClick: false }).then(function (result) {
                        _loadPemeriksaanAlat();
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        _loadPemeriksaanAlat();
                    });
                }
            });
        }
    });
}