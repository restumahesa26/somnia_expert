<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gejala;

class Penyakit extends Model
{
    protected $table = 'penyakit';
    protected $fillable = [
        'kode_penyakit',
        'nama_penyakit',
        'deskripsi',
        'solusi'
    ];

    public function gejalas()
    {
        return $this->belongsToMany(Gejala::class, 'penyakit_gejala')
            ->withPivot('bobot')
            ->withTimestamps();
    }
}
