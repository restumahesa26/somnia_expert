<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gangguan extends Model
{
    protected $table = 'gangguan';
    protected $fillable = ['kode_gangguan', 'nama_gangguan', 'deskripsi', 'solusi'];
}
