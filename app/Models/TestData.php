<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    use HasFactory;

    protected $table = 'test_datas';
    protected $fillable = ['lcm_id', 'model', 'ntsc', 'gamma', 'brightness', 'temperature', 'level'];
    protected $hidden = ['created_at', 'updated_at'];

}
