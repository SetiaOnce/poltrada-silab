<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiLab extends Model
{
    public $table = 'silab_lokasi_lab';
    
    public $timestamps = false;
    
    protected $guarded = [
        'id'
    ];
    
    use HasFactory;
}
