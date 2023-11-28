<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\KebijakanAplikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class KebijakanAplikasiController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Kebijakan Aplikasi';
        
        return view('backend.admin.kebijakan_aplikasi', $data);
    }

    public function loadKebijakanAplikasi()
    {
        $kebijakanApp = KebijakanAplikasi::where('id', 1)->first();
        $url_icon = asset('dist/img/icons/'.$kebijakanApp->icon_image); 
        $response = array(
            'status' => TRUE,
            'response' => $kebijakanApp,
            'url_icon' => $url_icon,
        );
        return response()->json($response);
    }

    public function kebijakanAplikasiUpdate(Request $request)
    {
		date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:250',
        ],[
            'judul.required' => 'Judul masih kosong.',
            'judul.max' => 'Judul tidak lebih dari 250 karakter.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            if($request->file('icon_image')){
                $mainImage = $request->file('icon_image');
                $filename = md5(Shortcut::random_strings(20)) . '.' . $mainImage->extension();
                Image::make($mainImage)->resize(300,300)->save(public_path('dist/img/icons/'.$filename));
                KebijakanAplikasi::where('id', 1)->update([
                    'icon_image' => $filename,
                ]);
            }
            KebijakanAplikasi::where('id', 1)->update([
                'judul' => $request->input('judul'),
                'isi_kebijakan' => $request->input('isi_kebijakan'),
                'user_updated' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
}
