<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\DataAlatPeraga;
use App\Models\PemeriksaanAlat;
use App\Models\PerawatanAlat;
use App\Models\ProfileApp;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;
use Milon\Barcode\DNS2D;

class DataAlatPeragaController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Data Alat Peraga';   
        return view('backend.admin.data_alat_peraga', $data);
    }
    public function data(Request $request)
    {
        $query = DataAlatPeraga::orderBy('fid_lab', 'DESC');
        if($request->input('filter') == 5){
            $query = DataAlatPeraga::whereNot('status', 100);
        }else{
            $query = DataAlatPeraga::whereStatus($request->filter);
        }
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('laboratorium', function ($row) {
                return $row->lab->nama_laboratorium;
            })
            ->editColumn('nama_alat_peraga', function ($row) {
                return $row->nama_alat_peraga.' (<strong>'.$row->lokasi->nama_lokasi.'</strong>)';
            })
            ->editColumn('satuan', function ($row) {
                return $row->satuan->satuan;
            })
            ->editColumn('perawatan', function ($row) {
                $getRow = PerawatanAlat::whereFidAlatPeraga($row->id)->orderByDesc('tgl_perawatan')->first();
                if(!empty($getRow)){
                    $output = '<span class="badge badge-primary cursor-pointer" data-bs-toggle="tooltip" title="Klik untuk melihat detail!" onclick="_detailPerawatan('."'".$getRow->id."'".');">'.date('d-m-Y', strtotime($getRow->tgl_perawatan)).'</span>';
                }else{
                    $output = '<span class="badge badge-light"><i>Tidak Ada Perawatan</i></span>';
                }
                return $output;
            })
            ->editColumn('pemeriksaan', function ($row) {
                $getRow = PemeriksaanAlat::whereFidAlatPeraga($row->id)->orderByDesc('tgl_pemeriksaan')->first();
                if(!empty($getRow)){
                    $output = '<span class="badge badge-primary cursor-pointer" data-bs-toggle="tooltip" title="Klik untuk melihat detail!" onclick="_detailPemeriksaan('."'".$getRow->id."'".');">'.date('d-m-Y', strtotime($getRow->tgl_pemeriksaan)).'</span>';
                }else{
                    $output = '<span class="badge badge-light"><i>Tidak Ada Pemeriksaan</i></span>';
                }
                return $output;
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
            ->editColumn('status', function ($row) {
                if($row->status == 0) {
                    $activeCustom = '<button type="button" class="btn btn-sm btn-danger font-weight-bold mb-1" data-toggle="tooltip" title="Alat Tidak Aktif (DRAFT), Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".',  '."'status'".');"><i class="fas fa-toggle-off"></i></button>';
                } else if($row->status == 1){
                    $activeCustom = '<button type="button" class="btn btn-sm btn-info font-weight-bold mb-1" data-toggle="tooltip" title="Alat Aktif (PUBLIK), Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".',  '."'status'".');"><i class="fas fa-toggle-on"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-sm btn-warning font-weight-bold mb-1" data-toggle="tooltip" title="Data sampah!" disabled><i class="mdi mdi-delete-variant"></i></button>';
                }
                return $activeCustom;
            })
            ->addColumn('action', function($row){
                $btn = '<button type="button" class="btn btn-icon btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                $btn = $btn.'<a href="javascript:void(0);"class="btn btn-icon btn-sm btn-info mb-1 ms-1" data-bs-toggle="tooltip" title="Cetak barcode!" onclick="_cetakBarcode('."'".$row->id."'".');"><i class="bi bi-upc-scan"></i></a>';
                if($row->status == 100){
                    $btn =  $btn.'<button type="button" class="d-flex align-items-center btn btn-block btn-sm btn-info font-weight-bold mb-1"  data-bs-toggle="tooltip" title="Kembalikan data alat!" onclick="_updateStatus('."'".$row->id."'".', '."'1'".',  '."'restore'".');"><i class="mdi mdi-restore"></i> Restore</button>';
                }else{
                    $btn = $btn.'<button type="button" class="btn btn-icon btn-sm btn-warning font-weight-bold mb-1"  data-bs-toggle="tooltip" title="Buang data alat ke tempat sampah!" onclick="_updateStatus('."'".$row->id."'".', '."'100'".',  '."'sampah'".');"><i class="mdi mdi-delete-outline"></i></button>';
                }
                return $btn;
            })
            ->rawColumns(['laboratorium','nama_alat_peraga', 'foto', 'satuan', 'perawatan', 'pemeriksaan', 'status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'kode_alat_peraga' => 'required|unique:silab_data_alat_peraga,kode_alat_peraga',
            'nama_alat_peraga' => 'required|max:120',
            'jumlah' => 'required',
            'fid_satuan' => 'required',
            'kondisi' => 'required|max:250',
            'fid_lab' => 'required',
            'fid_lokasi' => 'required',
            'foto' => 'required|mimes:png,jpg,jpeg|max:2048',
            'keterangan' => 'required|max:250',
        ],[
            'kode_alat_peraga.required' => 'Kode alat peraga masih kosong.',
            'kode_alat_peraga.unique' => 'Gagal memproses data, Kode Alat Peraga yang sama sudah terdata pada sistem ini.',
            'nama_alat_peraga.required' => 'Nama alat peraga masih kosong.',
            'nama_alat_peraga.max' => 'Nama alat peraga tidak lebih dari 120 karakter.',
            'jumlah.required' => 'Jumlah masih kosong.',
            'fid_satuan.required' => 'Satuan masih kosong.',
            'kondisi.required' => 'Kondisi alat peraga masih kosong.',
            'kondisi.max' => 'Kondisi alat peraga tidak lebih dari 120 karakter.',
            'fid_lab.required' => 'Laboratorium masih kosong.',
            'fid_lokasi.required' => 'Lokasi masih kosong.',            
            'foto.max' => 'Foto tidak lebih dari 2MB.',
            'foto.mimes' => 'Foto berekstensi jpg jepg png.',
            'keterangan.required' => 'Keterangan masih kosong.',
            'keterangan.max' => 'Keterangan tidak lebih dari 250 karakter.',

        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            // save image to directory
            $mainImage = $request->file('foto');
            $ImageName = strtolower(trim(preg_replace('/[^a-zA-Z]/', '', $request->input('nama_alat_peraga')))).'-'.strtolower(md5(Shortcut::random_strings(15))). '.' . $mainImage->extension();
            Image::make($mainImage)->save(public_path('dist/img/alat-peraga/'.$ImageName));

            DataAlatPeraga::create([
                'kode_alat_peraga' => $request->input('kode_alat_peraga'),
                'nama_alat_peraga' => $request->input('nama_alat_peraga'),
                'jumlah' => $request->input('jumlah'),
                'fid_satuan' => $request->input('fid_satuan'),
                'kondisi' => $request->input('kondisi'),
                'fid_lab' => $request->input('fid_lab'),
                'fid_lokasi' => $request->input('fid_lokasi'),
                'foto' => $ImageName,
                'keterangan' => $request->input('keterangan'),
                'user_add' => session()->get('nama'),
                'created_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $alatPeraga = DataAlatPeraga::where('id', $request->idp)->first();
        $urlFoto = asset('dist/img/alat-peraga/'.$alatPeraga->foto); 
        return response()->json([
            'status' => TRUE,
            'row' =>$alatPeraga,
            'url_foto' =>$urlFoto,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('id');
        $status = 0;
        if($request->input('status')){
            $status = 1;
        }
        $errors					= [];
        $validator = Validator::make($request->all(), [
            'kode_alat_peraga' => 'required',
            'nama_alat_peraga' => 'required|max:120',
            'jumlah' => 'required',
            'fid_satuan' => 'required',
            'kondisi' => 'required|max:250',
            'fid_lab' => 'required',
            'fid_lokasi' => 'required',
            'keterangan' => 'required|max:250',
        ],[
            'kode_alat_peraga.required' => 'Kode alat peraga masih kosong.',
            'nama_alat_peraga.required' => 'Nama alat peraga masih kosong.',
            'nama_alat_peraga.max' => 'Nama alat peraga tidak lebih dari 120 karakter.',
            'jumlah.required' => 'Jumlah masih kosong.',
            'fid_satuan.required' => 'Satuan masih kosong.',
            'kondisi.required' => 'Kondisi alat peraga masih kosong.',
            'kondisi.max' => 'Kondisi alat peraga tidak lebih dari 120 karakter.',
            'fid_lab.required' => 'Laboratorium masih kosong.',
            'fid_lokasi.required' => 'Lokasi masih kosong.',            
            'keterangan.required' => 'Keterangan masih kosong.',
            'keterangan.max' => 'Keterangan tidak lebih dari 250 karakter.',

        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $chekExistKode = DataAlatPeraga::whereNotIn('kode_alat_peraga', [DataAlatPeraga::whereId($data_id)->first()->kode_alat_peraga])->where('kode_alat_peraga', $request->kode_alat_peraga)->first();
            if(!empty($chekExistKode)){
                return response()->json(['kode_available' => true]);
            }
            if($request->file('foto')){
                $validator = Validator::make($request->all(), [
                    'foto' => 'required|mimes:png,jpg,jpeg|max:2048',
                ],[
                    'foto.max' => 'Foto tidak lebih dari 2MB.',
                    'foto.mimes' => 'Foto berekstensi jpg jepg png.',
                ]);
                if($validator->fails()){
                    foreach ($validator->errors()->getMessages() as $item) {
                        $errors[] = $item;
                    }
                    $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
                    return response()->json($output);
                } else {
                    // save image to directory
                    $mainImage = $request->file('foto');
                    $ImageName = strtolower(trim(preg_replace('/[^a-zA-Z]/', '', $request->input('nama_alat_peraga')))).'-'.strtolower(md5(Shortcut::random_strings(15))). '.' . $mainImage->extension();
                    Image::make($mainImage)->save(public_path('dist/img/alat-peraga/'.$ImageName));
                    DataAlatPeraga::where('id', $data_id)->update([
                        'foto' => $ImageName,
                    ]);
                }   
            }
            
            DataAlatPeraga::where('id', $data_id)->update([
                'kode_alat_peraga' => $request->input('kode_alat_peraga'),
                'nama_alat_peraga' => $request->input('nama_alat_peraga'),
                'jumlah' => $request->input('jumlah'),
                'fid_satuan' => $request->input('fid_satuan'),
                'kondisi' => $request->input('kondisi'),
                'fid_lab' => $request->input('fid_lab'),
                'fid_lokasi' => $request->input('fid_lokasi'),
                'keterangan' => $request->input('keterangan'),
                'status' => $status,
                'user_updated' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }

        return response()->json($output);
    }
    public function detaiPerawatan(Request $request)
    {
        $getRow = PerawatanAlat::where('id', $request->idp)->first();
        $getRow->foto_url = asset('dist/img/perawatan/'.$getRow->foto);
        $getRow->tgl_perawatan = date('d/m/Y', strtotime($getRow->tgl_perawatan)); 
        return response()->json([
            'status' => TRUE,
            'row' =>$getRow,
        ]);
    }
    public function detaiPemeriksaan(Request $request)
    {
        $getRow = PemeriksaanAlat::where('id', $request->idp)->first();
        $getRow->foto_url = asset('dist/img/pemeriksaan/'.$getRow->foto);
        $getRow->tgl_pemeriksaan = date('d/m/Y', strtotime($getRow->tgl_pemeriksaan)); 
        return response()->json([
            'status' => TRUE,
            'row' =>$getRow,
        ]);
    }
    public function updateStatus(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp');
        $value = $request->input('value');
        $message = 'Data alat dinonaktifkan';
        if($value == 1){
            $message = 'Data alat diaktfikan';
        }else if($value == 100){
            $message = 'Alat telah dipindahkan ke tempat sampah';
        }
        DataAlatPeraga::where('id', $data_id)->update([
            'status' => $value,
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => TRUE,
            'message' => $message
        ]);
    }

    public function barcode($idp, $ukuran)
    {
        // ukuran 1 = kecil
        // ukuran 2 = besar
        $alat = DataAlatPeraga::where('id', $idp)->first();
        $data['profileApp'] = ProfileApp::where('id', 1)->first();
        $data['alat'] = $alat;
        $data['ukuran'] = $ukuran;
        return view('backend.admin.print_barcode', $data);
    }
}