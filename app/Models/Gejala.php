<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Penyakit;

class Gejala extends Model
{
    protected $table = 'gejala';
    protected $fillable = ['kode_gejala', 'nama_gejala'];

    public function penyakits()
    {
        return $this->belongsToMany(Penyakit::class, 'penyakit_gejala')
            ->withPivot('bobot')
            ->withTimestamps();
    }
}
