<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firmware extends Model
{
    use HasFactory;

    protected $table = 'firmwares';
    protected $fillable = ['firmware', 'size', 'version', 'MD5'];
    protected $hidden = ['created_at', 'updated_at'];
}
