<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPraktek extends Model
{
    public $table = 'silab_jadwal_praktek';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
    
    use HasFactory;

    public function lab()
    {
    	return $this->belongsTo('App\Models\NamaLaboratorium', 'fid_lab', 'id'); 
    }
}
