<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LcmInfo extends Model
{
    use HasFactory;

    protected $table = 'lcm_infos';
    protected $fillable = ['ip', 'lcm_model'];

    public function lcmmodel()
    {
        return $this->belongsTo(LcmModel::class, 'lcm_model', 'id');
    }
}
