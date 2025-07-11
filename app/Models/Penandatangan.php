<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penandatangan extends Model
{
    use HasFactory;

    protected $table = 'penandatangans';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
