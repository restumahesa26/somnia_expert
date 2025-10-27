<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    protected $table = 'konsultasi';
    protected $fillable = [
        'user_id',
        'nama_pasien',
        'umur',
        'jenis_kelamin',
        'is_admin_input',
        'gejala_terpilih',
        'hasil'
    ];

    protected $casts = [
        'gejala_terpilih' => 'array',
        'hasil' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
