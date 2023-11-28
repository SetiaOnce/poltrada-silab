<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\PerawatanAlat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class PerawatanController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Data Perawatan Alat';
        
        return view('backend.admin.perawatan', $data);
    }
    public function data(Request $request)
    {
        $query = PerawatanAlat::orderBy('id', 'DESC');
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('alat_peraga', function ($row) {
                return $row->alat->nama_alat_peraga.' ( '.$row->alat->kode_alat_peraga.' )';
            })
            ->editColumn('tanggal_perawatan', function ($row) {
                return date('d-m-Y', strtotime($row->tgl_perawatan));
            })
            ->editColumn('foto', function ($row) {
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
                return $fileCustom;
            })
            ->addColumn('action', function($row){
                $btnEdit = '<button type="button" class="btn btn-icon btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                $btnDelete = '<button type="button" class="btn btn-icon btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteData('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['alat_peraga', 'tanggal_perawatan', 'foto', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_alat_peraga' => 'required',
            'tgl_perawatan' => 'required',
            'foto' => 'required|mimes:png,jpg,jpeg|max:2048',
            'keterangan' => 'required|max:250',
        ],[
            'fid_alat_peraga.required' => 'Alat peraga masih kosong.',
            'tgl_perawatan.required' => 'Tanggal perawatan masih kosong.',     
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
            $ImageName = md5(Shortcut::random_strings(15)). '-perawatan.' . $mainImage->extension();
            Image::make($mainImage)->save(public_path('dist/img/perawatan/'.$ImageName));
            $tanggalPerawatan=Carbon::createFromFormat('d/m/Y', $request->input('tgl_perawatan'))->format('Y-m-d'); 
            PerawatanAlat::create([
                'fid_alat_peraga' => $request->input('fid_alat_peraga'),
                'tgl_perawatan' => $tanggalPerawatan,
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
        $perawatan = PerawatanAlat::where('id', $request->idp)->first();
        $urlFoto = asset('dist/img/perawatan/'.$perawatan->foto); 
        return response()->json([
            'status' => TRUE,
            'row' =>$perawatan,
            'tgl_perawatan' => date('d/m/Y', strtotime($perawatan->tgl_perawatan)),
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
        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_alat_peraga' => 'required',
            'tgl_perawatan' => 'required',
            'keterangan' => 'required|max:250',
        ],[
            'fid_alat_peraga.required' => 'Alat peraga masih kosong.',
            'tgl_perawatan.required' => 'Tanggal perawatan masih kosong.',     
            'keterangan.required' => 'Keterangan masih kosong.',
            'keterangan.max' => 'Keterangan tidak lebih dari 250 karakter.',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
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
                    $ImageName = md5(Shortcut::random_strings(15)). '-perawatan.' . $mainImage->extension();
                    Image::make($mainImage)->save(public_path('dist/img/perawatan/'.$ImageName));
                    PerawatanAlat::where('id', $data_id)->update([
                        'foto' => $ImageName,
                    ]);
                }   
            }
            $tanggalPerawatan=Carbon::createFromFormat('d/m/Y', $request->input('tgl_perawatan'))->format('Y-m-d'); 
            
            PerawatanAlat::where('id', $data_id)->update([
                'fid_alat_peraga' => $request->input('fid_alat_peraga'),
                'tgl_perawatan' => $tanggalPerawatan,
                'keterangan' => $request->input('keterangan'),
                'user_updated' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }

        return response()->json($output);
    }
    public function destroy(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp');
        PerawatanAlat::where('id', $data_id)->delete();
        return response()->json(['status' => TRUE]);
    }
}
