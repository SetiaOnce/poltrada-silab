<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\BukuTamu;
use App\Models\DataAlatPeraga;
use App\Models\Faq;
use App\Models\GroupPegawai;
use App\Models\JadwalPraktek;
use App\Models\KebijakanAplikasi;
use App\Models\LinkTerkait;
use App\Models\NamaLaboratorium;
use App\Models\PemeriksaanAlat;
use App\Models\PerawatanAlat;
use App\Models\ProfileApp;
use App\Models\ProfileLaboratorium;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class FrontendController extends Controller
{
    public function index()
    {
        $data['dt_banners'] = Banners::orderBy('id', 'DESC')->where('status', 1)->get(); 
        $data['dt_linkTerkait'] = LinkTerkait::orderBy('id', 'DESC')->where('status', 1)->get(); 
        $data['dt_laboratorium'] = NamaLaboratorium::orderBy('id', 'DESC')->where('status', 1)->get(); 
        return view('frontend.index', $data);
    }
    public function profile()
    {
        return view('frontend.profile');
    }
    public function faq()
    {
        return view('frontend.faq');
    }
    public function bukuTamu()
    {
        return view('frontend.buku_tamu');
    }
    public function view($kode)
    {
        $data['alat'] = DataAlatPeraga::whereKodeAlatPeraga($kode)->first();
        if(!empty($data['alat'])){
            $data['dtPerawatan'] = PerawatanAlat::whereFidAlatPeraga($data['alat']['id'])->get();
            $data['dtPemeriksaan'] = PemeriksaanAlat::whereFidAlatPeraga($data['alat']['id'])->get();
            return view('frontend.detail_alat_peraga', $data);
        }else{
            abort(404);
        }
    }

    //=====>>This Bellow For Request Ajax Jquery<<===== //
    public function loadProfileApp()
    {
        $profile_app = ProfileApp::where('id', 1)->first();
        $response = array(
            'status' => TRUE,
            'copyright' => $profile_app->copyright,
            'public_header' => asset('dist/img/logo/'.$profile_app->logo_header_public),
        );
        return response()->json($response);
    }
    public function detailLaboratorium(Request $request)
    {
        $laboratorium = NamaLaboratorium::whereId($request->idp_lab)->first();
        $url_thumbnail = asset('dist/img/icons/'.$laboratorium->icon.'');
        $response = [
            'status' => true,
            'lab' => $laboratorium,
            'url_thumbnail' => $url_thumbnail,
            'prodi' => $laboratorium->prodi->nama_prodi
        ];
        return response()->json($response);
    }
    public function loadAlatPeraga(Request $request)
    {
        $query = DataAlatPeraga::orderBy('id', 'DESC')->whereFidLab($request->idp_lab);
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('satuan', function ($row) {
               return $row->satuan->satuan;
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
                        <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
            })
            ->rawColumns(['foto'])
            ->make(true);
    }
    public function loadKebijakanAplikasi()
    {
        $kebijakan = KebijakanAplikasi::where('id', 1)->first();
        $url_icon = asset('dist/img/icons/'.$kebijakan->icon_image); 
        $output = '
        <!--begin::Notice-->    
        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed min-w-lg-600px flex-shrink-0 p-6">
            <!--begin::Icon-->
            <a data-fslightbox="lightbox-basic" href="'.$url_icon.'">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative me-10">
                    <img src="'.$url_icon.'" alt="'.$kebijakan->icon_image.'">
                </div>
            </a>
            <!--end::Icon-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                <!--begin::Content-->
                <div class="mb-3 mb-md-0 fw-semibold">
                    <h4 class="text-gray-900 fw-bold">'.$kebijakan->judul.'</h4>
                    <div class="fs-6 text-gray-700 pe-7 text-justify">'.$kebijakan->isi_kebijakan.'</div>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->  
        </div>
        <!--end::Notice-->
        ';
        return response()->json($output);
    }
    public function loadProfile()
    {
        $profileLaboratorium = ProfileLaboratorium::whereId(1)->first()->profile_laboratorium;
        $response = array(
            'profileLaboratorium' => $profileLaboratorium,
        );
        return response()->json($response);
    }
    public function loadFaq()
    {
        $query = Faq::orderBy('id', 'DESC')->where('status', 1)->get();
        $output = '';
        $output .= '';
        foreach($query as $row){
            $output .= '
                <!--begin::Section-->
                <div class="m-0">
                    <!--begin::Heading-->
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0 collapsed" data-bs-toggle="collapse" data-bs-target="#'.$row->id.'" aria-expanded="false">
                        <!--begin::Icon-->
                        <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                            <i class="bi bi-dash-square toggle-on text-primary fs-1"></i>                
                            <i class="bi bi-plus-square toggle-off fs-1"><span class="path3"></span></i> 
                        </div>
                        <!--end::Icon-->
                        <!--begin::Title-->
                        <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">                           
                        '.$row->judul.'                            
                        </h4>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->  
                    <!--begin::Body-->
                    <div id="'.$row->id.'" class="fs-6 ms-1 collapse" style="">
                        <!--begin::Text-->
                        <div class="mb-4 text-gray-600 fw-semibold fs-6 ps-10 text-justify">
                            '.$row->isi.'                 
                        </div>
                        <!--end::Text-->
                    </div>                
                    <!--end::Content-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed"></div>
                    <!--end::Separator-->
                </div>
                <!--end::Section-->
            ';
        }
        $output .= '';
        return response()->json($output);
    }
    public function getDataPegawai(Request $request)
    {
        $getRow = GroupPegawai::whereNik($request->nik)->first();
        if(!empty($getRow)){
            $row = [
                'nama' => $getRow->nama,
                'telp' => $getRow->telp,
            ];
            $output = array('status' => true, 'row' => $row);
        }else{
            $output = array('status' => false, 'row' => '');
        }
        return response()->json($output);
    }
    public function saveJadwalPraktek(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_lab' => 'required',
            'nik_instruktur' => 'required',
            'nama_instruktur' => 'required|max:120',
            'no_wa' => 'required|max:15',
            'jmlh_peserta' => 'required|max:11',
            'judul_praktek' => 'required|max:250',
            'tanggal' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'keterangan' => 'required|max:250',
        ],[
            'fid_lab.required' => 'Laboratorium masih kosong.',
            'nik_instruktur.required' => 'NIK Instruktur masih kosong.',
            'nama_instruktur.required' => 'Nama instruktur masih kosong.',
            'nama_instruktur.max' => 'Nama instruktur tidak lebih dari 120 karakter.',
            'no_wa.required' => 'Nomor whatsapp masih kosong.',
            'no_wa.max' => 'Nomor whatsapp tidak lebih dari 15 digit.',
            'jmlh_peserta.required' => 'Jumlah peserta masih kosong.',
            'jmlh_peserta.max' => 'Jumlah peserta tidak lebih dari 11 digit.',
            'judul_praktek.required' => 'Judul praktek masih kosong.',
            'judul_praktek.max' => 'Judul praktek tidak lebih dari 250 karakter.',
            'tanggal.required' => 'Tanggal masih kosong.',
            'jam_awal.required' => 'Jam awal masih kosong.',
            'jam_akhir.required' => 'Jam akhir masih kosong.',
            'keterangan.required' => 'Keterangan masih kosong.',
            'keterangan.max' => 'Keterangan tidak lebih dari 250 karakter.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $tanggalPraktek = Carbon::createFromFormat('d/m/Y', $request->input('tanggal'))->format('Y-m-d'); 
            $jamPenggunaan = $request->input('jam_awal').'-'.$request->input('jam_akhir'); 

            JadwalPraktek::create([
                'fid_lab' => $request->input('fid_lab'),
                'nik_instruktur' => $request->input('nik_instruktur'),
                'nama_instruktur' => $request->input('nama_instruktur'),
                'no_wa' => $request->input('no_wa'),
                'judul_praktek' => $request->input('judul_praktek'),
                'tanggal' => $tanggalPraktek,
                'jam' => $jamPenggunaan,
                'jmlh_peserta' => $request->input('jmlh_peserta'),
                'ketereangan' => $request->input('ketereangan'),
                'created_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
    public function saveBukuTamu(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'required|max:120',
            'nama_kegiatan' => 'required|max:250',
            'tanggal_kunjungan' => 'required|max:30',
            'foto' => 'required|mimes:png,jpg,jpeg|max:2048',
        ],[
            'nama_instansi.required' => 'Nama instansi masih kosong.',
            'nama_instansi.max' => 'Nama instansi tidak lebih dari 120 karakter.',
            'nama_kegiatan.required' => 'Nama kegiatan masih kosong.',
            'nama_kegiatan.max' => 'Nama kegiatan tidak lebih dari 250 karakter.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan masih kosong.',
            'tanggal_kunjungan.max' => 'Tanggal kunjungan tidak lebih dari 30 digit.',
            'foto.required' => 'Foto kunjungan masih kosong.',
            'foto.max' => 'Foto kunjungan tidak lebih dari 2MB.',
            'foto.mimes' => 'Foto kunjungan berekstensi jpg jepg png.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $mainImage = $request->file('foto');
            $filename = md5(Shortcut::random_strings(20)) . '.' . $mainImage->extension();
            Image::make($mainImage)->save(public_path('dist/img/buku-tamu/'.$filename));
            $tanggalKunjungan=Carbon::createFromFormat('d/m/Y', $request->input('tanggal_kunjungan'))->format('Y-m-d'); 
            BukuTamu::create([
                'nama_instansi' => $request->input('nama_instansi'),
                'nama_kegiatan' => $request->input('nama_kegiatan'),
                'tanggal_kunjungan' => $tanggalKunjungan,
                'foto' => $filename,
                'created_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
}
