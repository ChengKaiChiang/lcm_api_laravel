<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LcmModel extends Model
{
    use HasFactory;

    protected $table = 'models';
    protected $fillable = ['model', 'firmware'];
    protected $hidden = ['created_at', 'updated_at'];

    public function firmware()
    {
        return $this->hasOne(Firmware::class, 'id', 'firmware');
    }
}
