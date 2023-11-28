<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileLaboratorium;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileLaboratoriumController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Profile Laboratorium';
        
        return view('backend.admin.profile_laboratorium', $data);
    }

    public function loadProfileLaboratorium(Request $request)
    {
        $profile = ProfileLaboratorium::whereId(1)->first()->profile_laboratorium;
        $response = array(
            'status' => TRUE,
            'profile' => $profile,
        );
        return response()->json($response);
    }

    public function profileLaboratoriumUpdate(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");
        
        ProfileLaboratorium::where('id', 1)->update([
            'profile_laboratorium' => $request->profile_laboratorium,
            'user_updated' => session()->get('nama'),
            'updated_at' => Carbon::now()
        ]);
        $output = array("status" => TRUE);

        return response()->json($output);
    }
}
