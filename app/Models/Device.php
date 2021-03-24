<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['device', 'position', 'status', 'model', 'firmware', 'version', 'description'];
    // protected $hidden = ['created_at', 'updated_at'];
}
