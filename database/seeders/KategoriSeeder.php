<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kategori::create([
            'title' => 'bimtek',
            'slug' => 'bimtek',
            'template' => 'template_bimtek.pdf',
        ]);

        Kategori::create([
            'title' => 'pelatihan',
            'slug' => 'pelatihan',
            'template' => 'template_pelatihan.pdf',
        ]);
    }
}
