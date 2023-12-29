<?php

namespace Database\Seeders;

use App\Models\Penandatangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenandatanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Penandatangan::create([
            'nama' => 'Tri Ida Kartini, SP., MP',
            'nip' => '197404212001122005',
            'pangkat_golongan' => 'Pembina / IV.a',
            'jabatan' => 'Kepala UPTD BPPSDMP',
            'tanda_tangan_stempel' => 'ttd_tri_ida.png'
        ]);

        Penandatangan::create([
            'nama' => 'Ir. Hj. Rini Susilawati, M.Si',
            'nip' => '196810071994032009',
            'pangkat_golongan' => 'Pembina Tk I / IV.b',
            'jabatan' => 'Plh. Kepala Dinas',
            'tanda_tangan_stempel' => 'ttd_rini.png'
        ]);

        Penandatangan::create([
            'nama' => 'Ir. Siti Farisyah Yana, M.Si',
            'nip' => '196905161993012001',
            'pangkat_golongan' => 'Pembina Utama Muda / IV.c',
            'jabatan' => 'Kepala Dinas',
            'tanda_tangan_stempel' => 'ttd_yana.png'
        ]);
    }
}
