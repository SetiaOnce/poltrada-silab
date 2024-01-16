<?php

namespace App\Http\Controllers\Backend\Common;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataAlatPeraga;
use App\Models\PemeriksaanAlat;
use App\Models\PerawatanAlat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class LaporanAlatPeragaController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Laporan Alat Peraga';   
        return view('backend.common.laporan_alat_peraga', $data);
    }
    public function data(Request $request)
    {
        $query = DataAlatPeraga::orderBy('fid_lab', 'DESC');
        if($request->filter_lokasi){
            $query->whereFidLokasi($request->filter_lokasi);
        }if($request->filter_laboratorium){
            $query->whereFidLab($request->filter_laboratorium);
        }if($request->filter_lokasi AND $request->filter_laboratorium){
            $query->whereFidLab($request->filter_laboratorium)->whereFidLab($request->filter_laboratorium);
        }
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('laboratorium', function ($row) {
                return $row->lab->nama_laboratorium;
            })
            ->editColumn('nama_alat_peraga', function ($row) {
                return $row->nama_alat_peraga.' (<strong>'.$row->lokasi->nama_lokasi.'</strong>)';
            })
            ->editColumn('foto', function ($row) {
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
                    <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$file_image.'" />
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <span class="badge badge-dark"><i class="las la-search fs-4 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
            })
            ->addColumn('satuan', function($row){
                return $row->satuan->satuan;
            })
            ->addColumn('detail', function($row){
                return '<button type="button" data-bs-toggle="tooltip" title="Lihat detail alat peraga!" onclick="_detailAlatPeraga('.$row->id.')" class="btn btn-icon btn-sm btn-success me-2"><i class="fas fa-th-list fs-3"></i></button>';
            })
            ->rawColumns(['laboratorium','nama_alat_peraga', 'foto', 'satuan', 'detail'])
            ->make(true);
    }
    protected function detailInformasiAlat($alatPeraga)
    {
        $output = '
            <!--begin::Title-->
            <h3 class="text-gray-900 fw-bolder mb-3">
                <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Detail Informasi Alat Peraga
            </h3>
            <!--end::Title-->
            <div class="row ">
                <table class="table table-rounded table-row-bordered border">
                    <tbody>
                        <tr>
                            <td style="width: 30px">Laboratorium</td>
                            <td style="width: 400px">'.$alatPeraga->lab->nama_laboratorium.'</td>
                        </tr>
                        <tr>
                            <td style="width: 30px">Kode Alat</td>
                            <td style="width: 400px">'.$alatPeraga->kode_alat_peraga.'</td>
                        </tr>
                        <tr>
                            <td style="width: 30px">Nama Alat</td>
                            <td style="width: 400px">'.$alatPeraga->nama_alat_peraga.'</td>
                        </tr>
                        <tr>
                            <td style="width: 30px">Jumlah</td>
                            <td style="width: 400px">'.$alatPeraga->jumlah.'</td>
                        </tr>
                        <tr>
                            <td style="width: 30px">Satuan</td>
                            <td style="width: 400px">'.$alatPeraga->satuan->satuan.'</td>
                        </tr>
                        <tr>
                            <td style="width: 30px">Lokasi</td>
                            <td style="width: 400px">'.$alatPeraga->lokasi->nama_lokasi.'</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        ';
        return $output;
    }
    protected function dataPerawatan($dataPerawatan)
    {
        $output = '';
        $output .= '
            <!--begin::Title-->
            <h3 class="text-gray-900 fw-bolder mb-3">
                <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Data Perawatan
            </h3>
            <!--end::Title-->
            <div class="row">
                <div class="table-responsive">
                    <table id="dt-perawatan" class="table table-rounded table-hover align-middle table-row-bordered border gy-5 gs-7">
                        <thead class="fw-bolder text-uppercase bg-light">
                            <tr class="fw-bold fs-8 text-gray-800 ">
                                <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No</th>
                                <th  class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Perawatan</th>
                                <th  class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                                <th  class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                            </tr>
                        </thead>
                        <tbody>
        ';
        foreach($dataPerawatan as $no => $row){
            $no = $no+1;
            $file_image = $row->foto;
            if($file_image==''){
                $url_file = asset('dist/img/default-placeholder.png');
            } else {
                if (!file_exists(public_path(). '/dist/img/perawatan/'.$file_image)){
                    $url_file = asset('dist/img/default-placeholder.png');
                    $file_image = NULL;
                }else{
                    $url_file = url('dist/img/perawatan/'.$file_image);
                }
            }
            $fileCustom = '<a class="d-block overlay w-100 image-popup" href="'.$url_file.'" title="'.$file_image.'">
                <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$file_image.'" />
                <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                    <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                </div>    
            </a>';
            $output .= '
                <tr>
                    <td align="center">'.$no.'</td>
                    <td>'.date('d-m-Y', strtotime($row->tgl_perawatan)).'</td>
                    <td>'.$row->keterangan.'</td>
                    <td align="center">'.$fileCustom.'</td>
                </tr>
            ';
        }
        $output .= '
                    </tbody>
                </table>
            </div>
        </div>
        ';
        return $output;
    }
    protected function dataPemeriksaan($dataPemeriksaan)
    {
        $output = '';
        $output .= '
            <!--begin::Title-->
            <h3 class="text-gray-900 fw-bolder mb-3">
                <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Data Pemeriksaan
            </h3>
            <!--end::Title-->
            <div class="row">
                <div class="table-responsive">
                    <table id="dt-pemeriksaan" class="table table-rounded table-hover align-middle table-row-bordered border gy-5 gs-7">
                        <thead class="fw-bolder text-uppercase bg-light">
                            <tr class="fw-bold fs-8 text-gray-800 ">
                                <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No</th>
                                <th  class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Pemeriksaan</th>
                                <th  class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                                <th  class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                            </tr>
                        </thead>
                        <tbody>
        ';
        foreach($dataPemeriksaan as $no => $row){
            $no = $no+1;
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
            $output .= '
                <tr>
                    <td align="center">'.$no.'</td>
                    <td>'.date('d-m-Y', strtotime($row->tgl_pemeriksaan)).'</td>
                    <td>'.$row->keterangan.'</td>
                    <td align="center">'.$fileCustom.'</td>
                </tr>
            ';
        }
        $output .= '
                    </tbody>
                </table>
            </div>
        </div>
        ';
        return $output;
    }
    public function detailAlat(Request $request)
    {
        $alatPeraga = DataAlatPeraga::whereId($request->idp)->first();
        $dtPerawatan = PerawatanAlat::whereFidAlatPeraga($alatPeraga->id)->get();
        $dtPemeriksaan = PemeriksaanAlat::whereFidAlatPeraga($alatPeraga->id)->get();
        $response = array(
            'status' => TRUE,
            'detailInformasiAlat' => $this->detailInformasiAlat($alatPeraga),
            'dataPerawatan' => $this->dataPerawatan($dtPerawatan), 
            'dataPemeriksaan' => $this->dataPemeriksaan($dtPemeriksaan),
        );
        return response()->json($response);
    }
}
