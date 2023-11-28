<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\AkademikMahasiswa;
use App\Models\DataAlatPeraga;
use App\Models\DetailPinjaman;
use App\Models\GroupPegawai;
use App\Models\Peminjaman;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Peminjaman';
        
        return view('backend.admin.peminjaman', $data);
    }
    public function data(Request $request)
    {
        $query = Peminjaman::orderBy('tgl_peminjaman', 'DESC')->whereStatus(0);
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('tanggal_peminjaman', function ($row) {
                return Shortcut::tanggal($row->tgl_peminjaman);
            })
            ->editColumn('jumlah_alat', function ($row) {
                $totalAlat = DetailPinjaman::whereFidPeminjaman($row->id)->count();
                $output = '
                    <a  href="javascript:void(0);" onclick="_viewAlatPinjaman('."'".$row->id."'".')" class="btn btn-sm btn-icon btn-secondary" data-bs-toggle="tooltip" title="Lihat alat yang dipinjam!">'.$totalAlat.'</a>
                ';
                return $output;
            })
            ->addColumn('action', function($row){
                $btnEdit = '<button type="button" class="btn btn-icon btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                $btnKelolaBuku = '<button type="button" class="btn btn-icon btn-sm btn-warning mb-1 ms-1" data-bs-toggle="tooltip" title="Kelola alat pinjaman!" onclick="_kelolaAlatPinjaman('."'".$row->id."'".','."'".$row->fid_lab."'".');"><i class="bi bi-cart-plus fs-3"></i></button>';
                $btnPengembalian = '<button type="button" class="btn btn-icon btn-sm btn-success mb-1 ms-1" data-bs-toggle="tooltip" title="Kelola pengembalian alat pinjaman!" onclick="_kelolaPengembalian('."'".$row->id."'".');"><i class="bi bi-arrow-down-square-fill fs-3"></i></button>';
                $btnPrint = '';
                if (!empty(DetailPinjaman::whereFidPeminjaman($row->id)->first()) > 0) {
                    $btnPrint = '<a href="'.url('app_admin/peminjaman_alat_print_pdf/'.$row->id).'" class="btn btn-icon btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Print surat peminjaman!"><i class="bi bi-file-earmark-pdf-fill fs-3"></i></a>';
                }
                return $btnEdit.$btnKelolaBuku.$btnPengembalian.$btnPrint;
            })
            ->rawColumns(['jumlah_alat', 'tanggal_peminjaman', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_lab' => 'required',
            'status_peminjaman' => 'required',
            'nik_notar' => 'required',
            'nama_peminjam' => 'required|max:120',
            'telepon' => 'required|max:15',
            'prodi_instansi' => 'required|max:120',
            'tgl_peminjaman' => 'required',
            'tgl_pengembalian' => 'required',
        ],[
            'fid_lab.required' => 'Laboratorium masih kosong...',
            'status_peminjaman.required' => 'Status peminjam masih kosong...',
            'nik_notar.required' => 'Nik atau notar masih kosong...',
            'nama_peminjam.required' => 'Nama peminjam masih kosong...',
            'nama_peminjam.max' => 'Nama peminjam tidak lebih dari 120 karakter...',
            'prodi_instansi.required' => 'Prodi atau instansi masih kosong...',
            'prodi_instansi.max' => 'Prodi atau instansi tidak lebih dari 120 karakter...',
            'telepon.required' => 'Telepon masih kosong...',
            'telepon.max' => 'Telepon tidak lebih dari 15 digit...',
            'tgl_peminjaman.required' => 'Tanggal peminjaman masih kosong...',
            'tgl_pengembalian.required' => 'Tanggal pengembalian masih kosong...',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            // for create no pinjaman
            $noPinjaman = preg_replace('/[\.\s:-]/', '', date("Y-m-d H:i:s"));
            // parse date
            $tanggalPinjam=Carbon::createFromFormat('d/m/Y', $request->input('tgl_peminjaman'))->format('Y-m-d'); 
            $tanggalKembali=Carbon::createFromFormat('d/m/Y', $request->input('tgl_pengembalian'))->format('Y-m-d'); 

            $peminjaman = Peminjaman::create([
                'fid_lab' => $request->input('fid_lab'),
                'status_peminjaman' => $request->input('status_peminjaman'),
                'nik_notar' => $request->input('nik_notar'),
                'nama_peminjam' => $request->input('nama_peminjam'),
                'telepon' => $request->input('telepon'),
                'prodi_instansi' => $request->input('prodi_instansi'),
                'tgl_peminjaman' => $tanggalPinjam,
                'tgl_pengembalian' => $tanggalKembali,
                'no_peminjaman' => $noPinjaman,
                'user_add' => session()->get('nama'),
                'created_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE, 'idp_peminjaman' => $peminjaman->id, 'fid_lab' => $peminjaman->fid_lab);
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $data_id = $request->input('idp');
        $DataPinjaman = Peminjaman::where('id', $data_id)->first();
        // parse date
        $tanggal_pinjam=Carbon::createFromFormat('Y-m-d', $DataPinjaman->tgl_peminjaman)->format('d/m/Y'); 
        $tanggal_kembali=Carbon::createFromFormat('Y-m-d', $DataPinjaman->tgl_pengembalian)->format('d/m/Y'); 
        
        return response()->json([
            'status' => TRUE,
            'row' =>$DataPinjaman,
            'tgl_peminjaman' =>$tanggal_pinjam,
            'tgl_pengembalian' =>$tanggal_kembali,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('id');

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_lab' => 'required',
            'status_peminjaman' => 'required',
            'nik_notar' => 'required',
            'nama_peminjam' => 'required|max:120',
            'telepon' => 'required|max:15',
            'prodi_instansi' => 'required|max:120',
            'tgl_peminjaman' => 'required',
            'tgl_pengembalian' => 'required',
        ],[
            'fid_lab.required' => 'Laboratorium masih kosong...',
            'status_peminjaman.required' => 'Status peminjam masih kosong...',
            'nik_notar.required' => 'Nik atau notar masih kosong...',
            'nama_peminjam.required' => 'Nama peminjam masih kosong...',
            'nama_peminjam.max' => 'Nama peminjam tidak lebih dari 120 karakter...',
            'prodi_instansi.required' => 'Prodi atau instansi masih kosong...',
            'prodi_instansi.max' => 'Prodi atau instansi tidak lebih dari 120 karakter...',
            'telepon.required' => 'Telepon masih kosong...',
            'telepon.max' => 'Telepon tidak lebih dari 15 digit...',
            'tgl_peminjaman.required' => 'Tanggal peminjaman masih kosong...',
            'tgl_pengembalian.required' => 'Tanggal pengembalian masih kosong...',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            // parse date
            $tanggalPinjam=Carbon::createFromFormat('d/m/Y', $request->input('tgl_peminjaman'))->format('Y-m-d'); 
            $tanggalKembali=Carbon::createFromFormat('d/m/Y', $request->input('tgl_pengembalian'))->format('Y-m-d'); 
            
            Peminjaman::where('id', $data_id)->update([
                'fid_lab' => $request->input('fid_lab'),
                'status_peminjaman' => $request->input('status_peminjaman'),
                'nik_notar' => $request->input('nik_notar'),
                'nama_peminjam' => $request->input('nama_peminjam'),
                'telepon' => $request->input('telepon'),
                'prodi_instansi' => $request->input('prodi_instansi'),
                'tgl_peminjaman' => $tanggalPinjam,
                'tgl_pengembalian' => $tanggalKembali,
                'user_updated' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE, 'idp_peminjaman' => $data_id, 'fid_lab' => $request->fid_lab);
        }

        return response()->json($output);
    }
    public function modalAlatPinjaman(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp');
        $headerPinjaman = Peminjaman::where('id', $data_id)->first();

        // load alat yang dipinjam
        $dtAlat = DetailPinjaman::where('fid_peminjaman', $data_id)->get();
        
        $outputAlatPinjaman = '';
        $outputAlatPinjaman .= '';
        foreach($dtAlat as $row){
            $file_image = $row->alat->foto;
            if($file_image==''){
                $url_file = asset('dist/img/default-placeholder.png');
            } else {
                if (!file_exists(public_path(). '/dist/img/alat-peraga/'.$file_image)){
                    $url_file = asset('dist/img/default-placeholder.png');
                    $file_image = NULL;
                }else{
                    $url_file = url('dist/img/alat-peraga/'.$file_image);
                }
            }

            $outputAlatPinjaman .= '
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <!--begin::Card-->
                <div class="card card-custom shadow rounded-top mb-5">
                    <div class="card-body p-0">
                        <!--begin::Image-->
                        <div class="overlay">
                            <div class="overlay-wrapper rounded bg-light text-center">
                                <img src="'.$url_file.'" alt="'.$file_image.'" class="rounded-top w-100" style="height: 225px;" />
                            </div>
                        </div>
                        <!--end::Image-->
                        <!--begin::Details-->
                        <div class="text-center mt-5 mb-md-0 mb-lg-5 mb-md-0 mb-lg-5 mb-lg-0 mb-5 d-flex flex-column" style="height: 62px;">
                            <span class="fw-bolder">'.$row->alat->nama_alat_peraga.'</span>
                            <span class="fw-bolder">'.$row->alat->kode_alat_peraga.'</span>
                            <span class="fw-bolder text-primary">( '.$row->jumlah.' )</span>
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            ';
        }
        $outputAlatPinjaman .= '';
        if(count($dtAlat) < 1){
            $outputAlatPinjaman = '<div class="col-lg-12 text-center"><i>Tidak ada alat peraga yang dipilih... </i></div>';
        }
        
        $response = array(
            'status' => TRUE,
            'alatPinjaman' => $outputAlatPinjaman, 
            'namaPeminjam' => $headerPinjaman->nama_peminjam,
            'noPeminjaman' => $headerPinjaman->no_peminjaman,
        );
        return response()->json($response);
    }
    public function checkTarunaDosen (Request $request)
    {
        if($request->status == 'TARUNA'){
            $query = AkademikMahasiswa::whereNim($request->nik_notar)->first();
            if(!empty($query)){
                $response = ['status' => true,'nama_peminjam' => $query->nama, 'telepon' => $query->telp, ];
            }else{
                $response = ['status' => false];
            }
        }else{
            $query = GroupPegawai::whereNik($request->nik_notar)->first();
            if(!empty($query)){
                $response = ['status' => true,'nama_peminjam' => $query->nama, 'telepon' => $query->telp];
            }else{
                $response = ['status' => false];
            }
        }
        return response()->json($response);
    }

    
    // // ===>> THIS BELLOW FOR SELECT ALAT PINJAMAN <<=== //
    public function loadHeaderPinjaman(Request $request)
    {
        $headerPinjaman = Peminjaman::whereId($request->idp_pinjaman)->first();
        $dtIdAlat = DetailPinjaman::whereFidPeminjaman($request->idp_pinjaman)->get(['fid_alat_peraga']);
        $output = '
        <div class="border border-dashed border-primary rounded min-w-80px py-3 px-4 mx-2">
            <div class="row d-flex justify-content-between">
                <div class="col-md-6">
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Laboratorium</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->lab->nama_laboratorium.'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Status Peminjam</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->status_peminjaman.'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Nama Peminjam</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->nama_peminjam.'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Nik/Notar</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->nik_notar.'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <div class="col-md-6">
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Nomor Peminjaman</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->no_peminjaman.'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Tanggal Peminjaman</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.Shortcut::tanggalLower($headerPinjaman->tgl_peminjaman).'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row mb-4">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-semibold text-muted">Tanggal Pengembalian</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">                    
                            <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.Shortcut::tanggalLower($headerPinjaman->tgl_pengembalian).'</span>                
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
            </div>
        </div>
        ';
        return response()->json(['output' => $output, 'dtIdAlat' => $dtIdAlat]);
    }
    public function alatPinjaman(Request $request)
    {
        $idp_pinjaman = $request->idp_pinjaman;
        $query = DataAlatPeraga::whereFidLab($request->idp_lab)->orderBy('id', 'DESC');
        if($request->filter_lokasi){
            $query->whereFidLokasi($request->filter_lokasi);
        }if($request->filter_laboratorium){
            $query->whereFidLab($request->filter_laboratorium);
        }if($request->filter_lokasi AND $request->filter_laboratorium){
            $query->whereFidLab($request->filter_laboratorium)->whereFidLab($request->filter_laboratorium);
        }
        $data = $query->get();

        $output = '';
        $output .= '';
        foreach($data as $row){
            // for checkbox
            $getDetailAlat = DetailPinjaman::whereFidPeminjaman($idp_pinjaman)->whereFidAlatPeraga($row->id)->first();
            if(!empty($getDetailAlat)){
                $checkbox = '
                    <div class="form-check form-check-custom form-check-sm">
                        <input class="form-check-input checkbox" type="checkbox" checked value="'.$row->id.'" id="check-'.$row->id.'"/>
                        <label class="form-check-label align-middle" for="check-'.$row->id.'">   
                            <h6 class="text-gray-600 fw-semibold">'.$row->nama_alat_peraga.'</h6>
                        </label>
                    </div>
                ';
            }else{
                $checkbox = '
                    <div class="form-check form-check-custom form-check-sm">
                        <input class="form-check-input checkbox" type="checkbox" value="'.$row->id.'" id="check-'.$row->id.'"/>
                        <label class="form-check-label align-middle" for="check-'.$row->id.'">   
                            <h6 class="text-gray-600 fw-semibold">'.$row->nama_alat_peraga.'</h6>
                        </label>
                    </div>
                ';
            }

            // for image
            $file_image = $row->foto;
            if($file_image==''){
                $url_file = asset('dist/img/default-placeholder.png');
            } else {
                if (!file_exists(public_path(). '/dist/img/alat-peraga/'.$file_image)){
                    $url_file = asset('dist/img/default-placeholder.png');
                    $file_image = NULL;
                }else{
                    $url_file = url('dist/img/alat-peraga/'.$file_image);
                }
            }
            $fileCustom = '<a class="d-block overlay w-100 image-popup" href="'.$url_file.'" title="'.$file_image.'">
                <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$file_image.'"/>
                <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                    <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                </div>    
            </a>';
            
            // for output
            $output .= '
                <tr>
                    <td width="20" align="center">
                    '.$checkbox.'
                    </td>
                    <td width="10" align="center">'.$row->kode_alat_peraga.'</td>
                    <td width="30">'.$row->lab->nama_laboratorium.' ( '.$row->lokasi->nama_lokasi.' )'.'</td>
                    <td width="30" align="center">'.$fileCustom.'</td>
                </tr>
            ';
        }
        $output .= '';
        return response()->json($output);
    }
    public function checkApproveAlat(Request $request)
    {
        $fidHeaderPinjaman = $request->fid_header_pinjaman;
        $fidAlatArray = $request->alatarray;

        $dtAlatPeraga = DataAlatPeraga::whereIn('id', $fidAlatArray)->get();
        
        $outputAlatPinjaman = '';
        $outputAlatPinjaman .= '';
        foreach($dtAlatPeraga as $row){
            // check if exist on detail peminjaman
            $check = DetailPinjaman::whereFidPeminjaman($fidHeaderPinjaman)->whereFidAlatPeraga($row->id)->first();
            $value = 1;
            if (!empty($check)) {$value = $check->jumlah;}

            $outputAlatPinjaman .= '
                <tr>
                    <td width="40">'.$row->nama_alat_peraga.'</td>
                    <td width="30" align="center">'.$row->kode_alat_peraga.'</td>
                    <td width="30" align="center">
                        <input type="text" name="jmlh_alat[]" class="form-control input-sm inputmax6" value="'.$value.'">
                    </td>
                </tr>
            ';
        }
        $outputAlatPinjaman .= '';

        // when data alat pinjaman is empty
        if(count($dtAlatPeraga) < 1){
            $outputAlatPinjaman = '<div class="col-lg-12 text-center"><i>Tidak ada alat yang dipilih... </i></div>';
        }
        
        $response = array(
            'status' => TRUE,
            'outputAlatPinjaman' => $outputAlatPinjaman,
            'jmlhAlat' => count($dtAlatPeraga),
        );
        return response()->json($response);

    }
    public function alatPinjamanSave(Request $request)
    {
        $fidAlatArray = explode(',', $request->input('array_alat_pinjaman'));
        $jmlhAlat = $request->input('jmlh_alat');

        // delete first on detail peminjaman
        DetailPinjaman::whereFidPeminjaman($request->fid_peminjaman)->delete();
        // looping save peminjaman on table
        for ($i=0; $i < count($fidAlatArray); $i++) { 
            $jmlh = $jmlhAlat[$i];
            $idp_alat = $fidAlatArray[$i];
            DetailPinjaman::create([
                'fid_peminjaman' => $request->fid_peminjaman,
                'fid_alat_peraga' => $idp_alat,
                'jumlah' => $jmlh,
            ]);
        }
        
        $output = array('status' => TRUE);
        return response()->json($output);
    }

    // ===>> THIS BELLOW FOR PENGEMBALIAN ALAT PINJAMAN <<=== //
    public function loadPengembalianBukuDetail(Request $request)
    {
        $idp_pinjaman = $request->input('idp_pinjaman');
        $headerPinjaman = Peminjaman::where('id', $request->idp_pinjaman)->first();
        $outputHeaderPeminjaman = '
            <div class="border border-dashed border-primary rounded min-w-80px py-3 px-4 mx-2">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6">
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Laboratorium</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->lab->nama_laboratorium.'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Status Peminjam</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->status_peminjaman.'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Nama Peminjam</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->nama_peminjam.'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Nik/Notar</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->nik_notar.'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <div class="col-md-6">
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Nomor Peminjaman</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.$headerPinjaman->no_peminjaman.'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Tanggal Peminjaman</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.Shortcut::tanggalLower($headerPinjaman->tgl_peminjaman).'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Tanggal Pengembalian</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">                    
                                <span class="fw-bold fs-6 text-gray-800"><span class="me-2">: </span>'.Shortcut::tanggalLower($headerPinjaman->tgl_pengembalian).'</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                </div>
            </div>
        ';

        // load alat yang dipinjam
        $dtAlat = DetailPinjaman::whereFidPeminjaman($request->idp_pinjaman)->get();
        
        $outputAlatPinjaman = '';
        $outputAlatPinjaman .= '';
        foreach($dtAlat as $row){
            $satuan = Satuan::whereId($row->alat->fid_satuan)->first();
            $file_image = $row->alat->foto;
            if($file_image==''){
                $url_file = asset('dist/img/default-placeholder.png');
            } else {
                if (!file_exists(public_path(). '/dist/img/alat-peraga/'.$file_image)){
                    $url_file = asset('dist/img/default-placeholder.png');
                    $file_image = NULL;
                }else{
                    $url_file = url('dist/img/alat-peraga/'.$file_image);
                }
            }

            $outputAlatPinjaman .= '
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <!--begin::Card-->
                <div class="card card-custom shadow rounded-top mb-5">
                    <div class="card-body p-0">
                        <!--begin::Image-->
                        <div class="overlay">
                            <div class="overlay-wrapper rounded bg-light text-center">
                                <img src="'.$url_file.'" alt="'.$file_image.'" class="rounded-top w-100" style="height: 225px;" />
                            </div>
                        </div>
                        <!--end::Image-->
                        <!--begin::Details-->
                        <div class="text-center mt-5 mb-md-0 mb-lg-5 mb-md-0 mb-lg-5 mb-lg-0 mb-5 d-flex flex-column" style="height: 62px;">
                            <span class="fw-bolder"><b>Kode</b>:'.$row->alat->kode_alat_peraga.'</span>
                            <span class="fw-bolder"><b>Alat</b>:'.$row->alat->nama_alat_peraga.'</span>
                            <span class="fw-bolder text-primary">( '.$row->jumlah.' '.$satuan->satuan.')</span>
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            ';
        }
        $outputAlatPinjaman .= '';
        if(count($dtAlat) < 1){
            $outputAlatPinjaman = '<div class="col-lg-12 text-center"><i>Tidak ada alat peraga yang dipilih... </i></div>';
        }

        $btnKOnfirmasi = '<button type="button" id="btn-confirmPengembalian" onclick="_condirmPengembalianAlat('.$idp_pinjaman.')" class="btn btn-sm btn-success me-2"><i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian</button>';

        $response = [
            'headerPeminjaman' => $outputHeaderPeminjaman, 
            'alatPinjaman' => $outputAlatPinjaman, 
            'btnKOnfirmasi' => $btnKOnfirmasi
        ];
        return response()->json($response);
    }
    public function confirmPengembalian(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");
        Peminjaman::whereId($request->idp_pinjaman)->update([
            'status' => 1,
            'user_updated' => session()->get('nama'),
            'updated_at' => Carbon::now()
        ]);
        $output = array('status' => TRUE);
        return response()->json($output);
    }

    public function cetakPdf($idp_peminjaman)
    {
        $perminjaman = Peminjaman::whereId($idp_peminjaman)->first();
        $dtPeminjaman = DetailPinjaman::whereFidPeminjaman($perminjaman->id)->get();

        $data = [
            'peminjaman' => $perminjaman,
            'dtPeminjaman' => $dtPeminjaman,
        ];
        
        $pdf = PDF::loadView('export.cetak_surat_peminjaman',$data);
        return $pdf->download($perminjaman->no_peminjaman.'.pdf');
    }
}