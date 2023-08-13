<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'sertifikats'); // Ganti 'kegiatan_sertifikat' dengan nama tabel pivot
    }

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'sertifikats'); // Ganti 'kegiatan_sertifikat' dengan nama tabel pivot
    }
}
