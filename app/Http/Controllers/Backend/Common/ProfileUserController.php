<?php

namespace App\Http\Controllers\Backend\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProfileUserController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Profile Saya';
        
        return view('backend.common.profile_user', $data);
    }
}
