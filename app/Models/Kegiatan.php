<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatans';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peserta()
    {
        return $this->belongsToMany(Peserta::class, 'sertifikats');
    }

    public function sertifikat()
    {
        return $this->belongsToMany(Sertifikat::class, 'sertifikats');
    }
}
