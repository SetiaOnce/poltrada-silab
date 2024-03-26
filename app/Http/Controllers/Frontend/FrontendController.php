<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\AkademikMahasiswa;
use App\Models\Banners;
use App\Models\BukuTamu;
use App\Models\DataAlatPeraga;
use App\Models\DetailPinjaman;
use App\Models\Faq;
use App\Models\GroupPegawai;
use App\Models\JadwalPraktek;
use App\Models\KebijakanAplikasi;
use App\Models\LinkTerkait;
use App\Models\NamaLaboratorium;
use App\Models\PemeriksaanAlat;
use App\Models\Peminjaman;
use App\Models\PerawatanAlat;
use App\Models\ProfileApp;
use App\Models\ProfileLaboratorium;
use App\Models\Visitors;
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
        Shortcut::logVisitor();
        return view('frontend.index', $data);
    }
    public function profile()
    {
        Shortcut::logVisitor();
        return view('frontend.profile');
    }
    public function faq()
    {
        Shortcut::logVisitor();
        return view('frontend.faq');
    }
    public function bukuTamu()
    {
        Shortcut::logVisitor();
        return view('frontend.buku_tamu');
    }
    public function peminjaman()
    {
        Shortcut::logVisitor();
        return view('frontend.peminjaman');
    }
    public function view($kode)
    {
        $data['alat'] = DataAlatPeraga::whereKodeAlatPeraga($kode)->first();
        if(!empty($data['alat'])){
            $data['dtPerawatan'] = PerawatanAlat::whereFidAlatPeraga($data['alat']['id'])->get();
            $data['dtPemeriksaan'] = PemeriksaanAlat::whereFidAlatPeraga($data['alat']['id'])->get();
            Shortcut::logVisitor();
            return view('frontend.detail_alat_peraga', $data);
        }else{
            abort(404);
        }
    }

    //=====>>This Bellow For Request Ajax Jquery<<===== //
    public function loadProfileApp()
    {
        $profile_app = ProfileApp::where('id', 1)->first();
        $visitors = Visitors::all()->count();
        $response = array(
            'status' => TRUE,
            'copyright' => $profile_app->copyright,
            'public_header' => asset('dist/img/logo/'.$profile_app->logo_header_public),
            'visitors' => $visitors,
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
    public function saveBiodataPeminjaman(Request $request)
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
            $data = [
                'fid_lab' => $request->input('fid_lab'),
                'status_peminjaman' => $request->input('status_peminjaman'),
                'nik_notar' => $request->input('nik_notar'),
                'nama_peminjam' => $request->input('nama_peminjam'),
                'telepon' => $request->input('telepon'),
                'prodi_instansi' => $request->input('prodi_instansi'),
                'tgl_peminjaman' => $tanggalPinjam,
                'tgl_pengembalian' => $tanggalKembali,
                'no_peminjaman' => $noPinjaman,
                'user_add' => $request->input('nama_peminjam'),
                'created_at' => Carbon::now()
            ];
            $peminjaman = Peminjaman::updateOrCreate(['id' => $request->idp], $data);
            $output = array("status" => TRUE, 'idp_peminjaman' => $peminjaman->id, 'fid_lab' => $request->fid_lab);
        }
        return response()->json($output);
    }
    public function alatPinjaman(Request $request)
    {
        $query = DataAlatPeraga::whereFidLab($request->idp_lab)->whereNot('jumlah', 0)->orderBy('id', 'DESC');
        $data = $query->get();

        $output = '';
        $output .= '';
        foreach($data as $row){
            $checkbox = '
                <div class="form-check form-check-custom form-check-sm">
                    <input class="form-check-input checkbox" type="checkbox" value="'.$row->id.'" id="check-'.$row->id.'"/>
                    <label class="form-check-label align-middle" for="check-'.$row->id.'">   
                        <h6 class="text-gray-600 fw-semibold">'.$row->nama_alat_peraga.'</h6>
                    </label>
                </div>
            ';
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
            $value = 1;

            $outputAlatPinjaman .= '
                <tr>
                    <td class="p-3" width="40">'.$row->nama_alat_peraga.'</td>
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

        // data response
        $peminjaman = Peminjaman::whereId($request->fid_peminjaman)->first();
        $peminjaman->nama_laboratorium = $peminjaman->lab->nama_laboratorium;
        $peminjaman->tgl_peminjaman = date('d-m-Y', strtotime($peminjaman->tgl_peminjaman));
        $peminjaman->tgl_pengembalian = date('d-m-Y', strtotime($peminjaman->tgl_pengembalian));
        $dataAlat = [];
        foreach(DetailPinjaman::whereFidPeminjaman($request->fid_peminjaman)->get() as $row){
            $dataAlat[] = [
                'nama_alat' => $row->alat->nama_alat_peraga,
                'kode_alat' => $row->alat->kode_alat_peraga,
                'jumlah' => $row->jumlah
            ];
        }
        $data= [
            'peminjaman' => $peminjaman,
            'dt_alat' => $dataAlat
        ];
        $output = array('status' => TRUE, 'row' => $data);
        return response()->json($output);
    }

    public function checkLogin()
    {
        if(isset($_COOKIE['pegawai_token']) && $_COOKIE['pegawai_token']) { 
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }
    }
    public function savePemeriksaanPerawatan(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");
        $errors					= [];
        if($request->type_action == 'PERAWATAN'){
            $validator = Validator::make($request->all(), [
                'fid_alat_peraga' => 'required',
                'tgl_perawatan' => 'required',
                'foto_perawatan' => 'required|mimes:png,jpg,jpeg|max:2048',
                'keterangan_perawatan' => 'required|max:250',
            ],[
                'fid_alat_peraga.required' => 'Alat peraga masih kosong.',
                'tgl_perawatan.required' => 'Tanggal perawatan masih kosong.',     
                'foto_perawatan.max' => 'Foto tidak lebih dari 2MB.',
                'foto_perawatan.mimes' => 'Foto berekstensi jpg jepg png.',
                'keterangan_perawatan.required' => 'Keterangan masih kosong.',
                'keterangan_perawatan.max' => 'Keterangan tidak lebih dari 250 karakter.',
            ]);
            if($validator->fails()){
                foreach ($validator->errors()->getMessages() as $item) {
                    $errors[] = $item;
                }
                $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
            } else {
                // save image to directory
                $mainImage = $request->file('foto_perawatan');
                $ImageName = md5(Shortcut::random_strings(15)). '-perawatan.' . $mainImage->extension();
                Image::make($mainImage)->save(public_path('dist/img/perawatan/'.$ImageName));
                $tanggalPerawatan=Carbon::createFromFormat('d/m/Y', $request->input('tgl_perawatan'))->format('Y-m-d'); 
                PerawatanAlat::create([
                    'fid_alat_peraga' => $request->input('fid_alat_peraga'),
                    'tgl_perawatan' => $tanggalPerawatan,
                    'foto' => $ImageName,
                    'keterangan' => $request->input('keterangan_perawatan'),
                    'user_add' => session()->get('nama'),
                    'created_at' => Carbon::now()
                ]);
                $output = array("status" => TRUE, 'message' => 'Berhasil menyimpan data perawatan...');
            }
            return response()->json($output);
        }else{
            $validator = Validator::make($request->all(), [
                'fid_alat_peraga' => 'required',
                'tgl_pemeriksaan' => 'required',
                'foto_pemeriksaan' => 'required|mimes:png,jpg,jpeg|max:2048',
                'keterangan_pemeriksaan' => 'required|max:250',
            ],[
                'fid_alat_peraga.required' => 'Alat peraga masih kosong.',
                'tgl_pemeriksaan.required' => 'Tanggal pemeriksaan masih kosong.',     
                'foto_pemeriksaan.max' => 'Foto tidak lebih dari 2MB.',
                'foto_pemeriksaan.mimes' => 'Foto berekstensi jpg jepg png.',
                'keterangan_pemeriksaan.required' => 'Keterangan masih kosong.',
                'keterangan_pemeriksaan.max' => 'Keterangan tidak lebih dari 250 karakter.',
            ]);
        
            if($validator->fails()){
                foreach ($validator->errors()->getMessages() as $item) {
                    $errors[] = $item;
                }
                $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
            } else {
                // save image to directory
                $mainImage = $request->file('foto_pemeriksaan');
                $ImageName = md5(Shortcut::random_strings(15)). '-pemeriksaan.' . $mainImage->extension();
                Image::make($mainImage)->save(public_path('dist/img/pemeriksaan/'.$ImageName));
                $tanggalPemeriksaan=Carbon::createFromFormat('d/m/Y', $request->input('tgl_pemeriksaan'))->format('Y-m-d'); 
    
                PemeriksaanAlat::create([
                    'fid_alat_peraga' => $request->input('fid_alat_peraga'),
                    'tgl_pemeriksaan' => $tanggalPemeriksaan,
                    'foto' => $ImageName,
                    'keterangan' => $request->input('keterangan_pemeriksaan'),
                    'user_add' => session()->get('nama'),
                    'created_at' => Carbon::now()
                ]);
                $output = array("status" => TRUE, 'message' => 'Berhasil menyimpan data pemeriksaan...');
            }
            return response()->json($output);
        }
    }
}
