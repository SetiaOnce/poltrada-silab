<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DetailPinjaman;
use App\Models\Peminjaman;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Peminjaman';   
        return view('backend.admin.peminjaman', $data);
    }
    public function data(Request $request)
    {
        $query = Peminjaman::orderBy('tgl_peminjaman', 'DESC')->whereIn('status', [0,1]);
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
            ->editColumn('approve', function ($row) {
                if($row->status == 0){
                    $output = '<span class="badge badge-light">VALIDASI</span>';
                }else if($row->status == 1){
                    $output = '<span class="badge badge-success">DITERIMA</span>';
                }else if($row->status == 3){
                    $output = '<span class="badge badge-danger">DITOLAK</span>';
                }
                return $output;
            })
            ->addColumn('action', function($row){
                $btnPengembalian = '';$btnPrint = '';$btnApprove = '';
                if($row->status == 0){
                    $btnApprove = '
                        <a  href="javascript:void(0);" onclick="_actionPermohonan('."'".$row->id."'".', '."'TOLAK'".', '."'".$row->nama_peminjam."'".', '."'".$row->no_peminjaman."'".')" class="btn btn-sm btn-danger mb-1" data-bs-toggle="tooltip" title="Tolak pengajuan peminjaman!">Tolak</a>
                        <a  href="javascript:void(0);" onclick="_actionPermohonan('."'".$row->id."'".', '."'TERIMA'".', '."'".$row->nama_peminjam."'".', '."'".$row->no_peminjaman."'".')" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Terima pengajuan peminjaman!">Terima</a>
                    ';
                }else if($row->status == 1){
                    $btnPengembalian = '<button type="button" class="btn btn-icon btn-sm btn-info mb-1 ms-1" data-bs-toggle="tooltip" title="Kelola pengembalian alat pinjaman!" onclick="_kelolaPengembalian('."'".$row->id."'".');"><i class="bi bi-arrow-down-square-fill fs-3"></i></button>';
                    $btnPrint = '';
                    if (!empty(DetailPinjaman::whereFidPeminjaman($row->id)->first()) > 0) {
                        $btnPrint = '<a href="'.url('app_admin/peminjaman_alat_print_pdf/'.$row->id).'" class="btn btn-icon btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Print surat peminjaman!"><i class="bi bi-file-earmark-pdf-fill fs-3"></i></a>';
                    }
                }
                return $btnApprove.$btnPengembalian.$btnPrint;
            })
            ->rawColumns(['jumlah_alat', 'tanggal_peminjaman', 'approve', 'action'])
            ->make(true);
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
    public function actionApprove(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'keterangan' => 'required|max:250',
        ],[
            'keterangan.required' => 'Catatan masih kosong...',
            'keterangan.max' => 'Catatan tidak lebih dari 250 karakter...',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            if($request->jenis_aksi == 'TERIMA'){
                $status = 1;
            }else{
                $status = 3;
            }
            Peminjaman::whereId($request->fid_peminjaman)->update([
                'keterangan' => $request->input('keterangan'),
                'status' => $status,
                'user_approved' => session()->get('nama'),
                'user_updated' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
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
            'status' => 2,
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