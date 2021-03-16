<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['model'];

    public function firmware()
    {
        return $this->belongsTo(Firmware::class, 'firmware', 'firmware');
    }
}
