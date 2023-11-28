<?php

namespace App\Http\Controllers\Backend\Common;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\PemeriksaanAlat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class LaporanPemeriksaanController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Laporan Pemeriksaan';
        
        return view('backend.common.laporan_pemeriksaan', $data);
    }
    public function data(Request $request)
    {
        
        $query = PemeriksaanAlat::orderBy('id', 'DESC');
        if($request->input('tgl_start') AND $request->input('tgl_end')){
            $tgl_start = Carbon::createFromFormat('d/m/Y', $request->input('tgl_start'))->format('Y-m-d');
            $tgl_end = Carbon::createFromFormat('d/m/Y', $request->input('tgl_end'))->format('Y-m-d');

            $query = $query->whereBetween('tgl_pemeriksaan', [$tgl_start, $tgl_end]);
        }
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('alat_peraga', function ($row) {
                return $row->alat->nama_alat_peraga.' ( '.$row->alat->kode_alat_peraga.' )';
            })
            ->editColumn('tanggal_pemeriksaan', function ($row) {
                return date('d-m-Y', strtotime($row->tgl_pemeriksaan));
            })
            ->editColumn('foto', function ($row) {
                $file_image = $row->foto;
                if($file_image==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/pemeriksaan/'.$file_image)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_image = NULL;
                    }else{
                        $url_file = url('dist/img/pemeriksaan/'.$file_image);
                    }
                }
                $fileCustom = '<a class="d-block overlay w-100 image-popup" href="'.$url_file.'" title="'.$file_image.'">
                    <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$file_image.'" />
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
            })
            ->rawColumns(['alat_peraga', 'tanggal_pemeriksaan', 'foto'])
            ->make(true);
    }
}
