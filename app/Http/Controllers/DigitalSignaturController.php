<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Penandatangan;
use App\Models\Sertifikat;
use App\Models\Siswa;
use GuzzleHttp\Client;
use App\Models\Narasumber;
use App\Models\Orang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class DigitalSignaturController extends Controller
{
    public function index()
    {
        // Dapatkan tahun saat ini
        $tahun_saat_ini = date('Y'); // 'Y' akan mengembalikan tahun dalam format 4 digit (contoh: 2025)
        $kategori_yang_diinginkan = [1, 2];

        // Ambil kegiatan yang tahun_kegiatan-nya sama dengan tahun saat ini
        $kegiatans = Kegiatan::where('tahun_kegiatan', $tahun_saat_ini)
            ->whereIn('kategori_id', $kategori_yang_diinginkan)
            ->latest() // Mengurutkan berdasarkan tanggal terbaru jika ada kolom created_at/updated_at
            ->get();
        $penandatangans = Penandatangan::all();
        return view('dashboard.digital_signature.index', compact('kegiatans', 'penandatangans'));
    }

    public function detail($id)
    {
        $kegiatan = Kegiatan::find($id);
        $penandatangans = Penandatangan::all();

        // If Peserta Kegiatan
        if ($kegiatan->kategori->title == 'pelatihan') {
            $client = new Client();
            $response = $client->get(env('SIMPELTAN_API_DATA_PESERTA'));
            $dataPesertas = json_decode($response->getBody(), true);
        }
        // If Bimtek Kegiatan
        if ($kegiatan->kategori->title == 'bimtek') {
            $dataPesertas = Orang::all();
        }
        // If PKL Kegiatan
        if ($kegiatan->kategori->title == 'pkl') {
            $dataPesertas = Siswa::all();
        }

        // Narasumber
        $narasumbers = Narasumber::all();

        $sertifikats = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'sertifikats.tanggal_terbit',
                'sertifikats.status',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
                'sertifikats.narasumber_id',
                'sertifikats.orang_id',
            )
            ->where('sertifikats.kegiatan_id', '=', $kegiatan->id)
            ->get();

        return view('dashboard.digital_signature.detail', compact('kegiatan', 'dataPesertas', 'narasumbers', 'sertifikats', 'penandatangans'));
    }

    public function signature(Request $request, $id)
    {
        $penandatangan = Penandatangan::where('user_id', $request->auth_id)->first();

        if (
            $penandatangan &&
            Hash::check($request->passphrase, $penandatangan->passphrase)
        ) { // Jika berhasil
            $kegiatan = Kegiatan::find($id);

            $kegiatan->status = 'signed';
            $kegiatan->save();

            $sertifikats = DB::table('sertifikats')
                ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
                ->select(
                    'sertifikats.id',
                    'sertifikats.tanggal_terbit',
                    'sertifikats.status'
                )
                ->where('sertifikats.kegiatan_id', '=', $kegiatan->id)
                ->get();

            // 4. update semua $sertifikats pada kolom tanggal_terbit dan status
            $currentDate = now();
            $updatedCount = 0;

            foreach ($sertifikats as $sertifikat) {
                $updated = DB::table('sertifikats')
                    ->where('id', $sertifikat->id)
                    ->update([
                        'tanggal_terbit' => $currentDate,
                        'status' => 'signed', // atau status yang sesuai
                    ]);

                if ($updated) {
                    $updatedCount++;
                }
            }
            return redirect()->back()->with('success', "Berhasil menandatangani {$updatedCount} sertifikat untuk kegiatan: {$kegiatan->judul_kegiatan}");
        } else {
            return redirect()->back()->with('error', 'Passphrase tidak valid');
        }
    }
}


// 1. Cek Passphrse yang di input jika sesuai lanjut ke point no 2
        // 2. $kegiatan = Kegiatan::find($id);
        // 3. cari sertifikat yang kegiatan_id saat ini
        // $sertifikats = DB::table('sertifikats')
        //     ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
        //     ->select(
        //         'sertifikats.id',
        //         'sertifikats.tanggal_terbit',
        //         'sertifikats.tanggal_status',
        //     )
        //     ->where('sertifikats.kegiatan_id', '=', $kegiatan->id)
        //     ->get();
        // 4. update semua $sertifikats pada kolom tanggal terbit dan status
