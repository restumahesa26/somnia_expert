<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyakitGejala extends Model
{
    protected $table = 'penyakit_gejala';
    protected $fillable = ['penyakit_id', 'gejala_id', 'bobot'];

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
