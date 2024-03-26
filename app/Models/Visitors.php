<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    public $table = 'silab_visitors';

    public $timestamps = false;
    
    protected $guarded = ['id'];
    
    use HasFactory;
}
