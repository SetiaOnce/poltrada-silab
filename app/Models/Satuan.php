<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    public $table = 'silab_satuan';
    
    public $timestamps = false;
    
    protected $guarded = [
        'id'
    ];
    
    use HasFactory;
}
