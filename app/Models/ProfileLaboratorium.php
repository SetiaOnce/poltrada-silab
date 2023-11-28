<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileLaboratorium extends Model
{
    public $table = 'silab_profile_laboratorium';

    public $timestamps = false;

    protected $guarded = ['id'];
    
    use HasFactory;
}
