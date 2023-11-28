<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaLaboratorium extends Model
{
    public $table = 'silab_nama_laboratorium';
    
    public $timestamps = false;
    
    protected $guarded = [
        'id'
    ];
    
    use HasFactory;
    public function prodi()
    {
    	return $this->belongsTo('App\Models\AkademikProdi', 'fid_prodi', 'id'); 
    } 
}
