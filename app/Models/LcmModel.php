<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LcmModel extends Model
{
    use HasFactory;

    protected $table = 'lcm_models';
    protected $fillable = ['model_name'];
}
