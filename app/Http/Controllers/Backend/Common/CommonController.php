<?php

namespace App\Http\Controllers\Backend\Common;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\BukuTamu;
use App\Models\DataAlatPeraga;
use App\Models\DetailPinjaman;
use App\Models\JadwalPraktek;
use App\Models\Peminjaman;
use App\Models\ProfileApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;


class CommonController extends Controller
{
    public function loadProfile()
    {
        $response = array(
            'status' => TRUE,
            'nama' => session()->get('nama'),
            'foto' => session()->get('foto'),
            'level' => session()->get('level'),
        );
        
        return response()->json($response);
    }

    public function loadProfileApp()
    {
        $profile_app = ProfileApp::where('id', 1)->first();
        $response = array(
            'status' => TRUE,
            'copyright' => $profile_app->copyright,
            'backend_logo' => asset('dist/img/logo/'.$profile_app->backend_logo),
            'backend_logo' => asset('dist/img/logo/'.$profile_app->backend_logo),
            'backend_logo_icon' => asset('dist/img/logo/'.$profile_app->backend_logo_icon),
        );
        return response()->json($response);
    }

    protected function upload_imgeditor(Request $request) {
        $errors					= [];
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:png,jpg,jpeg|max:1024',
        ],[
            'image.max' => 'File banner tidak lebih dari 1MB.',
            'image.mimes' => 'File banner berekstensi jpg jepg png.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $mainImage = $request->file('image');
            $filename = time() . 'custom-image.' . $mainImage->extension();
            Image::make($mainImage)->save(public_path('dist/img/summernote-img/'.$filename));
            $output = array(
                "status" => TRUE,
                "url_img" => url('dist/img/summernote-img/'.$filename),
            );
        }
        return response()->json($output);
    }

    public function loaduserProfile()
    {

        $response = array(
            'nama' => session()->get('nama'),
            'foto' => session()->get('foto'),
            'level' => session()->get('level'),
            'email' => session()->get('email'),
            'alamat' => session()->get('alamat'),
            'nik' => session()->get('nik'),
            'nip' => session()->get('nip'),
            'unit_kerja' => session()->get('unit_kerja'),
        );
        
        $output = '
        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
        <!--begin: Pic-->
        <div class="me-7 mb-4">
            <a href="'.$response['foto'].'" class="image-popup" title="'.$response['level'].'">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    <img src="'.$response['foto'].'" alt="user-image">
                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                </div>
            </a>
        </div>
        <!--end::Pic-->
        <!--begin::Info-->
        <div class="flex-grow-1">
            <!--begin::Title-->
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                <!--begin::User-->
                <div class="d-flex flex-column">
                    <!--begin::Name-->
                    <div class="d-flex align-items-center mb-2">
                        <a href="javascript:void(0);" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">'.$response['nama'].'</a>
                        <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" aria-label="Status user Aktif!" data-bs-original-title="Status user Aktif!"></a>
                        <span class="badge badge-light-success fw-bold ms-2 fs-8 py-1 px-3">'.$response['level'].'</span>
                    </div>
                    <!--end::Name-->
                </div>
                <!--end::User-->
            </div>
            <!--end::Title-->
            <!--begin::Detail User-->
            <div class="d-flex flex-row flex-column border border-gray-300 border-dashed rounded w-100 py-5 px-4 me-4 my-5">
                <!--begin::Row-->
                <div class="row mb-7">
                    <div class="col-lg-6">
                        <div class="w-100 mb-3">
                            <div class="fs-6 text-gray-400">Nama</div>
                            <div class="d-flex align-items-center">
                                <div class="fs-6 fw-bolder">'.$response['nama'].'</div>
                            </div>
                        </div>
                        <div class="w-100 mb-3">
                            <div class="fs-6 text-gray-400">NIK</div>
                            <div class="d-flex align-items-center">
                                <div class="fs-6 fw-bolder">'.$response['nik'].'</div>
                            </div>
                        </div>
                        <div class="w-100 mb-3">
                            <div class="fs-6 text-gray-400">NIP</div>
                            <div class="d-flex align-items-center">
                                <div class="fs-6 fw-bolder">'.$response['nip'].'</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="w-100 mb-3">
                            <div class="fs-6 text-gray-400">Email</div>
                            <div class="d-flex align-items-center">
                                <div class="fs-6 fw-bolder">'.$response['email'].'</div>
                            </div>
                        </div>
                        <div class="w-100 mb-3">
                            <div class="fs-6 text-gray-400">Unit Kerja</div>
                            <div class="d-flex align-items-center">
                                <div class="fs-6 fw-bolder">
                                '.$response['unit_kerja'].'
                                </div>
                            </div>
                        </div>
                        <div class="w-100 mb-3">
                            <div class="fs-6 text-gray-400">Alamat</div>
                            <div class="d-flex align-items-center">
                                <div class="fs-6 fw-bolder">'.$response['alamat'].'</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Detail User-->
        </div>
        <!--end::Info-->
        </div>
        ';

        return response()->json(['output' => $output]);
    }
    public function getDetailAlat(Request $request)
    {
        $dataAlat = DataAlatPeraga::whereId($request->idp)->first();
        $urlFoto = asset('dist/img/alat-peraga/'.$dataAlat->foto); 
        $response = array(
            'status' => TRUE,
            'row' => $dataAlat,
            'url_foto' => $urlFoto,
            'laboratorium' => $dataAlat->lab->nama_laboratorium,
            'lokasi' => $dataAlat->lokasi->nama_lokasi,
        );
        return response()->json($response);
    }
    public function loadWidgetDashboard(Request $request)
    {
        $jmlh_alatPeraga = DataAlatPeraga::where('status', 1)->count();
        $jmlh_kunjungan = BukuTamu::all()->count();
        $jmlh_kegiatanPraktek = 0;
        $jmlh_transaksi = Peminjaman::all()->count();
        $transaksi_jatuhTempo = Peminjaman::whereDate('tgl_pengembalian', '<=', date('Y-m-d'))->where('status', 0)->count();
        $response = [
            'level_user' => session()->get('key_level'),
            'url_banner' => asset('dist/img/background-dashboard.jpg'),
            'jmlh_alatPeraga' => $jmlh_alatPeraga,
            'jmlh_kunjungan' => $jmlh_kunjungan,
            'jmlh_kegiatanPraktek' => $jmlh_kegiatanPraktek,
            'jmlh_transaksi' => $jmlh_transaksi,
            'transaksi_jatuhTempo' => $transaksi_jatuhTempo,
        ];
        return response()->json($response);
    }
    public function loadTransaksiJatuhTempo(Request $request)
    {
        $response = [
            'status' => TRUE,
        ];
        return response()->json($response);
    }
    public function tableTransaksiJatuhTempo(Request $request)
    {
        $data = Peminjaman::whereDate('tgl_pengembalian', '<=', date('Y-m-d'))->where('status', 0)->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('jumlah_alat', function ($row) {
                return DetailPinjaman::whereFidPeminjaman($row->id)->count();
            })
            ->editColumn('tanggal_pinjam', function ($row) {
                return Shortcut::tanggalLower($row->tgl_peminjaman);
            })
            ->editColumn('tanggal_kembali', function ($row) {
                return Shortcut::tanggalLower($row->tgl_pengembalian);
            })
            ->rawColumns(['jumlah_alat', 'tanggal_pinjam', 'tanggal_kembali'])
            ->make(true);
    }
    public function trendPeminjamanAlat(Request $request)
    {
        $dateYear = date('Y');
        $xavisBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i=1; $i<=12; $i++) { 
            $amountData[] = Peminjaman::whereYear('tgl_peminjaman', $dateYear)->whereMonth('tgl_peminjaman', $i)->count();
        }
        $response = array(
            'xavisBulan' => $xavisBulan,
            'amountData' => $amountData,
        );
        return response()->json($response);
    }
    public function loadNotification(Request $request)
    {
        $jadwalPraktek = JadwalPraktek::whereStatus(0)->count();
        $response = array(
            'jadwal_praktek' => $jadwalPraktek,
        );
        return response()->json($response);
    }
}
