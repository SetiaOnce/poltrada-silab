<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebijakanAplikasi extends Model
{
    public $table = 'perpustakaan_kebijakan_aplikasi';

    protected $guarded = [];
    
    use HasFactory;
}
