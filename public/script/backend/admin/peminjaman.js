//Class Definition
var save_method;
// variable Initialization
var table;
//Class Initialization
jQuery(document).ready(function() {
    _loadDataPeminjaman();
    // selectpicker
    loadSelectpicker_statuspeminjaman(),loadSelectpicker_laboratorium();
    //FLATPICKER OPTIONS
    var tglPinjam = $('#tgl_peminjaman').val(), tglKembali = $('#tgl_pengembalian').val();
    $("#tgl_peminjaman").flatpickr({ defaultDate: tglPinjam,  dateFormat: "d/m/Y", minDate: "today" });
    $("#tgl_pengembalian").flatpickr({defaultDate: tglKembali, dateFormat: "d/m/Y", minDate: "today" });
    // mask input
    $('.inputmax20').mask('00000000000000000000'), $('.inputmax10').mask('0000000000'), $('#tgl_peminjaman').inputmask('99/99/9999', { placeholder: 'dd/mm/yyyy' }), $('#tgl_pengembalian').inputmask('99/99/9999', { placeholder: 'dd/mm/yyyy' });
    //FLATPICKER OPTIONS FILTERS
    var startDate = $('#filterDt-startDate').val(), endDate = $('#filterDt-endDate').val();
    $("#filterDt-startDate").flatpickr({ defaultDate: startDate, dateFormat: "d/m/Y" });
    $("#filterDt-endDate").flatpickr({ defaultDate: endDate, dateFormat: "d/m/Y" });
    // apply filter data
    $('#btn-applyFilter').click(function(){
        _loadDataPeminjaman();
    });
    // Reset filter data
    $('#btn-resetFilter').click(function(){
        $("#filterDt-startDate").val('');
        $("#filterDt-endDate").val('');
        _loadDataPeminjaman();
    });
    // on change status peminjaman
    $('#status_peminjaman').change(function(){
        var status = this.value;
        if(status == 'TARUNA'){
            $('#hideForm1').hide();
            $('.form-input-disabled').prop('readonly', true);
            var nik_notar = $('[name="nik_notar"]').val();
            if(nik_notar != ''){
                _checkDataTarunaDosen(nik_notar, status)
            }
        }else if(status == 'DOSEN'){
            $('#hideForm1').hide();
            $('.form-input-disabled').prop('readonly', true);
            var nik_notar = $('[name="nik_notar"]').val();
            if(nik_notar != ''){
                _checkDataTarunaDosen(nik_notar, status)
            }
        }else{
            $('#hideForm1').show();
            $('.form-input-disabled').prop('readonly', false);
            $('#notFoundData').html(``), $('#notFoundData').hide();
        }
    });
    // on write form nik or nim 
    $('[name="nik_notar"]').on('input', function() {
        var status =  $('#status_peminjaman').val();
        var nik_notar = this.value;
        if(status == 'TARUNA' || status == 'DOSEN'){
            _checkDataTarunaDosen(nik_notar, status)
        }
    });
});
//Load Datatables 
const _loadDataPeminjaman = () => {
    table = $('#dt-peminjaman').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_peminjaman_alat",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            "data"  : function ( data ) {
                data.tgl_start= $('#filterDt-startDate').val();
                data.tgl_end= $('#filterDt-endDate').val();
            }
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
            { data: 'tanggal_peminjaman', name: 'tanggal_peminjaman'},
            { data: 'status_peminjaman', name: 'status_peminjaman'},
            { data: 'nama_peminjam', name: 'nama_peminjam'},
            { data: 'telepon', name: 'telepon'},
            { data: 'no_peminjaman', name: 'no_peminjaman'},
            { data: 'jumlah_alat', name: 'jumlah_alat'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "5%", "targets": 1, "className": "align-top","visible": false},
            { "width": "10%", "targets": 2, "className": "align-top" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "align-top text-center" },
            { "width": "10%", "targets": 6, "className": "align-top text-center" },
            { "width": "10%", "targets": 7, "className": "align-top text-center" },
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
            $("#dt-peminjaman_length select").selectpicker(),
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
            var api = this.api();
			var rows = api.rows({
				page: 'current'
			}).nodes();
			var last = null;
			api.column(1, {
				page: 'current'
			}).data().each(function (group, i) {
				if (last !== group) {
					$(rows).eq(i).before(
						'<tr class="align-middle "><td class="bg-secondary" colspan="7"><b><i class="mdi mdi-calendar-range me-2"></i> ' + group + '</b></td></tr>'
					);
					last = group;
				}
			});
        },
    });
    $("#dt-peminjaman").css("width", "100%");
}
// handle check data taruna and dosen
const _checkDataTarunaDosen = (nik_notar, status) => {
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/check_data_taruna_dosen',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            nik_notar, status,
        },
        success: function (data) {
            if(data.status==true){
                $('#hideForm1').show();
                $('#notFoundData').html(``).hide();
                $(".form-input-disabled").val('');
                $('[name="nama_peminjam"]').val(data.nama_peminjam);
                $('[name="telepon"]').val(data.telepon);
            }else{
                $('#hideForm1').hide();
                $('#notFoundData').html(`
                    <div class="alert alert-light d-flex align-items-center p-5">
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-danger"><i>Data tidak ditemukan...</i></h4>
                        </div>
                    </div>
                `).show();
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log("load data is error!");
        },
    });
}
/*************************
    Custom Select Laboratorium
*************************/
function loadSelectpicker_laboratorium() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getlaboratorium",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].nama_laboratorium + '</option>';
            }
            $('#fid_lab').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
/*************************
    Custom Select Status Peminjaman
*************************/
function loadSelectpicker_statuspeminjaman() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getstatuspeminjaman",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].status + '</option>';
            }
            $('#status_peminjaman').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_peminjaman') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }else if(card=='form_alat'){
        location.reload(true);
    }else if(card=='form_pengembalian'){
        $('#card-PengembalianSrc').hide();
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset(),$('#status_peminjaman').selectpicker('val', ''), $('#fid_lab').selectpicker('val', '');
        $('[name="id"]').val("");
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
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Peminjaman</h3>`
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
        url: BASE_URL+ '/app_admin/ajax/peminjaman_alat_edit',
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

                //form peminjaman
                $('select[name=status_peminjaman]').val(data.row.status_peminjaman),
                $('.selectpicker').selectpicker('refresh');
                $('select[name=fid_lab]').val(data.row.fid_lab),
                $('.selectpicker').selectpicker('refresh');
                $('#nik_notar').val(data.row.nik_notar);
                $('#nama_peminjam').val(data.row.nama_peminjam);
                $('#telepon').val(data.row.telepon);
                $('#prodi_instansi').val(data.row.prodi_instansi);
                // check data dosen or taruna
                _checkDataTarunaDosen(data.row.nik_notar, data.row.status_peminjaman);
                // tanggal peminjaman
                $("#tgl_peminjaman").flatpickr({defaultDate: data.tgl_peminjaman, dateFormat: "d/m/Y", minDate: data.tgl_peminjaman});
                // tanggal pengembalian
                $("#tgl_pengembalian").flatpickr({defaultDate: data.tgl_pengembalian,dateFormat: "d/m/Y", minDate: data.tgl_pengembalian});
                // close and open card
                $("#card-form .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Data Peminjaman Alat</h3>`
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
    var fid_lab = $('#fid_lab');
    var status_peminjaman = $('#status_peminjaman');
    var nik_notar = $('#nik_notar');
    var nama_peminjam = $('#nama_peminjam');
    var telepon = $('#telepon');
    var prodi_instansi = $('#prodi_instansi');
    var tgl_peminjaman = $('#tgl_peminjaman');
    var tgl_pengembalian = $('#tgl_pengembalian');
    
    if (fid_lab.val() == '') {
        toastr.error('Laboratorium masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        fid_lab.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (status_peminjaman.val() == '') {
        toastr.error('Status peminjam masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        status_peminjaman.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }
    if (status_peminjaman.val() == 'DOSEN' || status_peminjaman.val() == 'TARUNA') {        
        if (nama_peminjam.val() == '') {
            toastr.error('Nama peminjam masih kosong pastikan untuk memasukkan nik atau notar yang terdata pada sistem...', 'Uuppss!', {"progressBar": true, "timeOut": 2500});
            nama_peminjam.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }
    }else{
        if (nama_peminjam.val() == '') {
            toastr.error('Nama peminjam masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            nama_peminjam.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }
    }
    if (telepon.val() == '') {
        toastr.error('Telepon masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        telepon.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (prodi_instansi.val() == '') {
        toastr.error('Prodi atau instansi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        prodi_instansi.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (tgl_peminjaman.val() == '') {
        toastr.error('Tanggal peminjaman masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        tgl_peminjaman.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (tgl_pengembalian.val() == '') {
        toastr.error('Tanggal pengembalian masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        tgl_pengembalian.focus();
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
            let formData = new FormData($("#form-data")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/peminjaman_alat_save";
            if(save_method == 'update_data') {
                ajax_url = BASE_URL+ "/app_admin/ajax/peminjaman_alat_update";
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
                        var message = 'Data peminjaman berhasil diperbarui'
                        if(save_method == 'add_data'){
                            var message = 'Data peminjaman baru berhasil ditambahkan'
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_peminjaman'), _loadDataPeminjaman(), _kelolaAlatPinjaman(data.idp_peminjaman, data.fid_lab);
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
const _viewAlatPinjaman = (idp) => {
    let target = document.querySelector("#card-data"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/load_modal_alat_pinjaman',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('#sectionModalAlatPinjaman').html(data.alatPinjaman);
                $('#modalViewAlatPinjaman .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900"><i class="bi bi-cart-plus fs-2 text-gray-900 me-2"></i>Alat Pinjaman | '+ data.namaPeminjam +' ('+ data.noPeminjaman +')</h3>');
                $('#modalViewAlatPinjaman').modal('show');
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

// ===>> THIS BELLOW FOR HANDLE PEMINJAMAN ALAT <<===//
var table2;var alatarray=[];var fid_header_pinjaman;
// Class definition
var KTListPeminjamanAlat = function () {
    // Header pinjaman
    var handleHeaderPeminjaman = (idp_pinjaman) => {
        fid_header_pinjaman = idp_pinjaman;
        $.ajax({
            url: BASE_URL+ '/app_admin/ajax/load_header_pinjaman',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            dataType: 'JSON',
            data: {
                idp_pinjaman
            },
            success: function (data) {
                $('#detailHeaderPinjaman').html(data.output);

                var alatSelected = data.dtIdAlat;
                alatSelected.forEach((alat) => {
                    alatarray.push(alat.fid_alat_peraga);
                });
                $('#card-data').hide();
                $('#card-alatSrc').show();
                if(alatSelected.length > 0){
                    const toolbarSelected = document.querySelector('[data-kt-docs-table-toolbar="selected"]');
                    const selectedCount = document.querySelector('[data-kt-docs-table-select="selected_count"]');
                    // selectedCount.innerHTML = alatSelected.length;
                    toolbarSelected.classList.remove('d-none');
                }

            }, error: function (jqXHR, textStatus, errorThrown) {
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
    // Private functions
    var initDatatable = function (idp_pinjaman, idp_lab) {
        $.ajax({
            url: BASE_URL+ "/app_admin/ajax/load_alat_pinjaman",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'POST',
            dataType: 'JSON',
            data: {
                idp_pinjaman,idp_lab
            },
            success: function (data) {
                $('#dt_peminjaman_alat').html(data);
            }, complete: function(){
                initCheckboxes();
                $('#datatableAlat').DataTable({
                    "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                    oLanguage: {
                        sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
                        sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
                        sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
                        sInfoFiltered: "",
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
                    fnDrawCallback: function (settings, display) {
                        $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
                        //Custom Table
                        $("#datatableAlat_length select").selectpicker(),
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
                
            }, error: function (jqXHR, textStatus, errorThrown) {
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
    // Init toggle toolbar
    var initCheckboxes = function () {
        // Select all checkboxes
        const container = document.querySelector('#datatableAlat');
        const checkboxes = container.querySelectorAll('[type="checkbox"]');
        // Toggle delete selected toolbar
        checkboxes.forEach(checkbox => {
            // Checkbox on click event
            checkbox.addEventListener('change', function () {
                var valueCheckbox = parseInt(checkbox.value);
                if (this.checked) {
                    // push to array category
                    alatarray.push(valueCheckbox);
                }else{
                    // push delete from array caregory
                    const deleteKaetegory = alatarray.indexOf(valueCheckbox); 
                    if (deleteKaetegory !== -1) {
                        alatarray.splice(deleteKaetegory, valueCheckbox);
                    }
                }
                console.log(alatarray);
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });
    }
    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const container = document.querySelector('#datatableAlat');
        const toolbarBase = document.querySelector('[data-kt-docs-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-docs-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-docs-table-select="selected_count"]');

        // Select refreshed checkbox DOM elements
        const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            // selectedCount.innerHTML = count;
            // toolbarBase.classList.add('d-none');
            // toolbarSelected.classList.remove('d-none');
        } else {
            // toolbarBase.classList.remove('d-none');
            // toolbarSelected.classList.add('d-none');
        }
    }
    // Custom Select Master Laboratorium
    var loadSelectpicker_laboratorium = function() {
        $.ajax({
            url: BASE_URL+ "/select/ajax_getlaboratorium",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var output = '';
                var i;
                for (i = 0; i < data.length; i++) {
                    output += '<option value="' + data[i].id + '">' + data[i].nama_laboratorium + '</option>';
                }
                $('#filterLaboratorium').html(output).selectpicker('refresh').selectpicker('val', '');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    }
    // Custom Select Master Lokasi
    var loadSelectpicker_lokasi = function() {
        $.ajax({
            url: BASE_URL+ "/select/ajax_getlokasi",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var output = '';
                var i;
                for (i = 0; i < data.length; i++) {
                    output += '<option value="' + data[i].id + '">' + data[i].nama_lokasi + '</option>';
                }
                $('#filterLokasi').html(output).selectpicker('refresh').selectpicker('val', '');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    }
    // Public methods
    return {
        init: function (idp_pinjaman, idp_lab) {
            handleHeaderPeminjaman(idp_pinjaman);
            initDatatable(idp_pinjaman, idp_lab);
            loadSelectpicker_laboratorium();
            loadSelectpicker_lokasi();
        }
    }
}();
function _kelolaAlatPinjaman(idp_pinjaman, idp_lab){
    KTListPeminjamanAlat.init(idp_pinjaman, idp_lab);
}
// approve checked checkbox
$('#btn-approve-alat').click(function(){
    if (alatarray.length < 1) {
        Swal.fire({title: "Ooops!",html: 'Alat masih kosong, checklist alat yang ingin dipinjam!',icon: "warning",allowOutsideClick: false,});
    }else{
        let target = document.querySelector("#card-alatSrc"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
        blockUi.block(), blockUi.destroy();
        $.ajax({
            url: BASE_URL+ '/app_admin/ajax/check_alat_approve',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'POST',
            dataType: 'JSON',
            data: {
                alatarray, fid_header_pinjaman
            },
            success: function (data) {
                blockUi.release(), blockUi.destroy();
                $('[name="array_alat_pinjaman"]').val(alatarray);
                $('[name="fid_peminjaman"]').val(fid_header_pinjaman);
                
                $('#dt-approveAlat').html(data.outputAlatPinjaman);
                $('.inputmax6').mask('0000000000');

                $('#modalApproveAlat .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900"><i class="bi bi-cart-plus fs-2 text-gray-900 me-2"></i> Tentukan Jumlah Alat Pinjaman (<span class="text-info">'+ data.jmlhAlat +'</span>)</h3>');
                $('#modalApproveAlat').modal('show');
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
});
// save alat pinjaman
$('#btn-save-alat').click(function(){
    $("#btn-save-alat").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let formData = new FormData($("#form-savealat")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/alat_pinjaman_save";
    $.ajax({
        url: ajax_url,
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            $('#btn-save-alat').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            if (data.status == true) {
                Swal.fire({
                    title: "Success!",
                    text: "Peminjaman berhasil disimpan...",
                    icon: "success",
                    allowOutsideClick: false,
                }).then(function (result) {
                    location.reload(true);
                });
            } else {
                if(data.pesan_code=='format_inputan') {   
                    Swal.fire({title: "Ooops!",html: data.pesan_error[0],icon: "warning",allowOutsideClick: false,});
                } else {
                    Swal.fire({title: "Ooops!",html: data.pesan_error,icon: "warning",allowOutsideClick: false,});
                }
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-save-alat').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            Swal.fire({title: "Ooops!",text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",icon: "error",allowOutsideClick: false,});
        }
    });
});

// ===>> THIS BELLOW FOR HANDLE PENGEMBALIAN ALAT PINJAMAN <<===//
// Class definition
var KTPengembalianAlat = function () {
    // Header pinjaman
    var _handlePengembalianAlat = (idp_pinjaman) => {
        $.ajax({
            url: BASE_URL+ '/app_admin/ajax/load_pengembalian_alat_detail',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            dataType: 'JSON',
            data: {
                idp_pinjaman
            },
            success: function (data) {
                $('#detailHeaderPinjaman2').html(data.headerPeminjaman);
                $('#listAlatPinjaman').html(data.alatPinjaman);
                $('.btn-konfirmasiPengembalianSrc').html(data.btnKOnfirmasi);
                $('#listAlatPinjaman').magnificPopup({
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
                $('#card-data').hide();
                $('#card-PengembalianSrc').show();
            }, error: function (jqXHR, textStatus, errorThrown) {
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
    // Public methods
    return {
        init: function (idp_pinjaman) {
            _handlePengembalianAlat(idp_pinjaman);
        }
    }
}();
// handle confirm pengembalian alat pinjaman
function _condirmPengembalianAlat(idp_pinjaman){
    $("#btn-confirmPengembalian").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Memproses data...').attr("disabled", true);
    Swal.fire({
        title: "Konfirmasi Pengembalian",
        text: 'Alat ini sudah dikembalikan?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-PengembalianSrc"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();

            $.ajax({
                url: BASE_URL+ "/app_admin/ajax/confirm_pengembalian_alat",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                dataType: 'JSON',
                data: {
                    idp_pinjaman
                },success: function (data) {
                    $('#btn-confirmPengembalian').html('<i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: "Konfirmasi pengembalian alat peraga sukses dilakukan...",
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_pengembalian'), _loadDataPeminjaman();
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: "Proses data error, Periksa koneksi internet anda lalu coba kembali.",
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-confirmPengembalian').html('<i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian').attr('disabled', false);
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
            $('#btn-confirmPengembalian').html('<i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian').attr('disabled', false);
        }
    });
}
// handle pengembalian alat pinjaman
function _kelolaPengembalian(idp_pinjaman){
    KTPengembalianAlat.init(idp_pinjaman);
}
