<?php

namespace App\Http\Controllers\Backend\Common;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DetailPinjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class LaporanPeminjamanController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Laporan Peminjaman';
        
        return view('backend.common.laporan_peminjaman', $data);
    }
    public function data(Request $request)
    {
        $query = Peminjaman::orderBy('tgl_peminjaman', 'DESC');
        if($request->input('tgl_start') AND $request->input('tgl_end')){
            $tgl_start = Carbon::createFromFormat('d/m/Y', $request->input('tgl_start'))->format('Y-m-d');
            $tgl_end = Carbon::createFromFormat('d/m/Y', $request->input('tgl_end'))->format('Y-m-d');

            $query = $query->whereBetween('tgl_peminjaman', [$tgl_start, $tgl_end]);
        }
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('tanggal_peminjaman', function ($row) {
                return Shortcut::tanggal($row->tgl_peminjaman);
            })
            ->editColumn('status', function ($row) {
                $status = '<span class="badge badge-warning">BELUM DIKEMBALIKAN</span>';
                if($row->status == 1){
                    $status = '<span class="badge badge-success">SUDAH DIKEMBALIKAN</span>';
                }
                return $status;
            })
            ->editColumn('jumlah_alat', function ($row) {
                $totalAlat = DetailPinjaman::whereFidPeminjaman($row->id)->count();
                $output = '
                    <a  href="javascript:void(0);" onclick="_viewAlatPinjaman('."'".$row->id."'".')" class="btn btn-sm btn-icon btn-secondary" data-bs-toggle="tooltip" title="Lihat alat yang dipinjam!">'.$totalAlat.'</a>
                ';
                return $output;
            })
            ->addColumn('action', function($row){
                $btnPrint = '<a href="'.url('app_admin/peminjaman_alat_print_pdf/'.$row->id).'" class="btn btn-icon btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Print surat peminjaman!"><i class="bi bi-file-earmark-pdf-fill fs-3"></i></a>';
                return $btnPrint;
            })
            ->rawColumns(['jumlah_alat','status', 'action'])
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
}
