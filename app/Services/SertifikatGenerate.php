<?php

namespace App\Services;

use App\Mail\NotifySertifikat;
use App\Models\Sertifikat;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SertifikatGenerate
{
    public function prosesSingleGenerate($sertifikat)
    {
        // ============= Get Detail Peserta by API
        $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$sertifikat->peserta_id}";
        $response = file_get_contents($url);
        $peserta = json_decode($response, true);
        // ============= END Get Detail Peserta by API

        $templatePath = public_path('uploads/template/' . $sertifikat->template_sertifikat);
        $templateSize = getimagesize($templatePath); // Mendapatkan dimensi template PDF

        $pdf = new FPDI();
        $pdf->AddPage('L', 'A4');
        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1); // Ambil halaman pertama dari template PDF

        // Gunakan halaman template sebagai latar belakang
        $pdf->useTemplate($templateId);

        // Mengatur margin dalam satuan milimeter (mm)
        $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
        $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

        // Set font dan ukuran
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 10);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Pemprov Desc
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 63);
        $pdf->SetX(45);
        $pdf->Cell(0, 10, 'Pemerintah Provinsi Kalimantan Timur berdasarkan Peraturan Menteri Pertanian Republik Indonesia Nomor', 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 69);
        $pdf->SetX(45);
        $pdf->Cell(0, 10, '37/PERMENTAN/SM.120/8/2018 tentang Pedoman Pelatihan Pertanian, menyatakan bahwa :', 0, 0, 'L');
        $pdf->SetX(12.6);

        // Informasi Peserta
        if ($sertifikat->kategori_kegiatan == 'pelatihan') {
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 80);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 85.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_nip'], 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 91.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_tempat_lahir'] . ', ' . Carbon::parse($peserta[0]['peserta_tanggal_lahir'])->isoFormat('D MMMM Y'), 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 97.2);
            $pdf->SetX(150);

            // Mengambil nilai pangkat_golongan dari array $peserta
            $pesertaPangkatGolongan = $peserta[0]['peserta_pangkat_golongan'];

            // Pemisahan string sebelum dan setelah tanda titik
            list($beforeDot, $afterDot) = explode('.', $pesertaPangkatGolongan, 2);

            // Mengubah huruf pertama sebelum tanda titik menjadi huruf kapital
            $beforeDotCapitalized = strtoupper($beforeDot);

            // Gabungkan kembali string
            $pesertaPangkatGolonganFormatted = $beforeDotCapitalized . '.' . strtolower($afterDot);

            // Menambahkan kondisi
            if ($pesertaPangkatGolongan == 'ii.a') {
                $pdf->Cell(0, 10, 'Pengatur Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'ii.b') {
                $pdf->Cell(0, 10, 'Pengatur Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'ii.c') {
                $pdf->Cell(0, 10, 'Pengatur / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'ii.d') {
                $pdf->Cell(0, 10, 'Pengatur Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.a') {
                $pdf->Cell(0, 10, 'Penata Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.b') {
                $pdf->Cell(0, 10, 'Penata Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.c') {
                $pdf->Cell(0, 10, 'Penata / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.d') {
                $pdf->Cell(0, 10, 'Penata Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iv.a') {
                $pdf->Cell(0, 10, 'Pembina / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iv.b') {
                $pdf->Cell(0, 10, 'Pembina Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iv.c') {
                $pdf->Cell(0, 10, 'Pembina Utama Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'non-asn') {
                $pdf->Cell(0, 10, 'Non ASN', 0, 0, 'L');
            }

            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 103.1);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_jabatan'], 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 109);
            $pdf->SetX(150);
            // Mengambil nilai instansi dari array $peserta
            $pesertaInstansi = $peserta[0]['peserta_instansi'];
            // Pecah string menjadi array kata
            $words = explode(' ', $pesertaInstansi);
            // Ambil kata pertama dan ubah menjadi uppercase
            $firstWord = strtoupper($words[0]);
            // Ambil kata-kata selanjutnya dan ubah menjadi kapital
            $nextWords = array_map('ucfirst', array_slice($words, 1));
            // Gabungkan kembali kata-kata menjadi string
            $instansiFormatted = $firstWord . ' ' . implode(' ', $nextWords);
            $pdf->Cell(0, 10, $instansiFormatted, 0, 0, 'L');
            $pdf->SetX(12.6);
        } else {
            $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
            $pdf->SetFont("Lobster-Regular", "", 28);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 93);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'C');
            $pdf->SetX(12.6);
        }

        // Telah Mengikuti
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 128);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, $sertifikat->judul_kegiatan . ' yang diselenggarakan', 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 133);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, 'oleh UPTD Balai Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian Provinsi Kalimantan Timur', 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 138);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D MMMM') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $sertifikat->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 160);
        $pdf->SetX(45);
        $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 47, 155, 20, 0, 'PNG');
        $pdf->SetX(12.6);

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 145.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'Samarinda, ' . Carbon::parse($sertifikat->tanggal_penandatanganan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 150);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $sertifikat->jabatan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "UB", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 170);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $sertifikat->nama_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 175);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $sertifikat->pangkat_golongan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 180);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'NIP. ' . $sertifikat->nip_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Output PDF
        $pdf->Output('sertifikat' . Str::slug($peserta[0]['peserta_nama']), 'I');

        exit;
    }

    public function prosesAllGenerate($sertifikats)
    {
        $pdf = new Fpdi();

        // Loop untuk membuat sertifikat dalam jumlah banyak
        foreach ($sertifikats as $key => $sertifikat) {
            // ============= Get Detail Peserta by API
            $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$sertifikat->peserta_id}";
            $response = file_get_contents($url);
            $peserta = json_decode($response, true);
            // ============= END Get Detail Peserta by API

            // Tambahkan halaman baru dari template sertifikat
            $pdf->AddPage('L', 'A4');
            $pdf->setSourceFile(public_path('uploads/template/' . $sertifikat->template_sertifikat));
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx);

            // Mengatur margin dalam satuan milimeter (mm)
            $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
            $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

            // Set font dan ukuran
            $pdf->SetFont('Arial', 'B', 16);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 10);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
            $pdf->SetX(12.6);

            // Pemprov Desc
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 63);
            $pdf->SetX(45);
            $pdf->Cell(0, 10, 'Pemerintah Provinsi Kalimantan Timur berdasarkan Peraturan Menteri Pertanian Republik Indonesia Nomor', 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 69);
            $pdf->SetX(45);
            $pdf->Cell(0, 10, '37/PERMENTAN/SM.120/8/2018 tentang Pedoman Pelatihan Pertanian, menyatakan bahwa :', 0, 0, 'L');
            $pdf->SetX(12.6);

            // Informasi Peserta
            if ($sertifikat->kategori_kegiatan == 'pelatihan') {
                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 80);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 85.5);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_nip'], 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 91.5);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_tempat_lahir'] . ', ' . Carbon::parse($peserta[0]['peserta_tanggal_lahir'])->isoFormat('D MMMM Y'), 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 97.2);
                $pdf->SetX(150);

                // Mengambil nilai pangkat_golongan dari array $peserta
                $pesertaPangkatGolongan = $peserta[0]['peserta_pangkat_golongan'];

                // Pemisahan string sebelum dan setelah tanda titik
                list($beforeDot, $afterDot) = explode('.', $pesertaPangkatGolongan, 2);

                // Mengubah huruf pertama sebelum tanda titik menjadi huruf kapital
                $beforeDotCapitalized = strtoupper($beforeDot);

                // Gabungkan kembali string
                $pesertaPangkatGolonganFormatted = $beforeDotCapitalized . '.' . strtolower($afterDot);

                // Menambahkan kondisi
                if ($pesertaPangkatGolongan == 'ii.a') {
                    $pdf->Cell(0, 10, 'Pengatur Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'ii.b') {
                    $pdf->Cell(0, 10, 'Pengatur Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'ii.c') {
                    $pdf->Cell(0, 10, 'Pengatur / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'ii.d') {
                    $pdf->Cell(0, 10, 'Pengatur Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.a') {
                    $pdf->Cell(0, 10, 'Penata Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.b') {
                    $pdf->Cell(0, 10, 'Penata Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.c') {
                    $pdf->Cell(0, 10, 'Penata / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.d') {
                    $pdf->Cell(0, 10, 'Penata Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iv.a') {
                    $pdf->Cell(0, 10, 'Pembina / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iv.b') {
                    $pdf->Cell(0, 10, 'Pembina Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iv.c') {
                    $pdf->Cell(0, 10, 'Pembina Utama Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'non-asn') {
                    $pdf->Cell(0, 10, 'Non ASN', 0, 0, 'L');
                }

                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 103.1);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_jabatan'], 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 109);
                $pdf->SetX(150);
                // Mengambil nilai instansi dari array $peserta
                $pesertaInstansi = $peserta[0]['peserta_instansi'];
                // Pecah string menjadi array kata
                $words = explode(' ', $pesertaInstansi);
                // Ambil kata pertama dan ubah menjadi uppercase
                $firstWord = strtoupper($words[0]);
                // Ambil kata-kata selanjutnya dan ubah menjadi kapital
                $nextWords = array_map('ucfirst', array_slice($words, 1));
                // Gabungkan kembali kata-kata menjadi string
                $instansiFormatted = $firstWord . ' ' . implode(' ', $nextWords);
                $pdf->Cell(0, 10, $instansiFormatted, 0, 0, 'L');
                $pdf->SetX(12.6);
            } else {
                $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
                $pdf->SetFont("Lobster-Regular", "", 28);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 93);
                $pdf->SetX(10.5);
                $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'C');
                $pdf->SetX(12.6);
            }

            // Telah Mengikuti
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 128);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, $sertifikat->judul_kegiatan . ' yang diselenggarakan', 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 133);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, 'oleh UPTD Balai Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian Provinsi Kalimantan Timur', 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 138);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D MMMM') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $sertifikat->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
            $pdf->SetX(12.6);
            // Buat QR Code
            QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 160);
            $pdf->SetX(45);
            $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 47, 155, 20, 0, 'PNG');
            $pdf->SetX(12.6);

            // Penandatangan
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 145.5);
            $pdf->SetX(170);
            $pdf->Cell(0, 10, 'Samarinda, ' . Carbon::parse($sertifikat->tanggal_penandatanganan)->isoFormat('D MMMM Y'), 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 150);
            $pdf->SetX(170);
            $pdf->Cell(0, 10, $sertifikat->jabatan_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "UB", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 170);
            $pdf->SetX(170);
            $pdf->Cell(0, 10, $sertifikat->nama_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 175);
            $pdf->SetX(170);
            $pdf->Cell(0, 10, $sertifikat->pangkat_golongan_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 180);
            $pdf->SetX(170);
            $pdf->Cell(0, 10, 'NIP. ' . $sertifikat->nip_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);
        }

        // Simpan
        $outputFilePath = public_path("sertifikat/kegiatan/" . 'doc-sertifikat-kegiatan_' . Str::slug($sertifikat->judul_kegiatan, '-') . '.' . 'pdf');
        $pdf->Output($outputFilePath, 'I');

        $pdf->Output('sertifikat_pada_kegiatan_' . Str::slug($sertifikat->judul_kegiatan, '-') . '.' . 'pdf', 'I');
    }

    public function prosesSingleTerbit($sertifikat)
    {
        // ============= Get Detail Peserta by API
        $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$sertifikat->peserta_id}";
        $response = file_get_contents($url);
        $peserta = json_decode($response, true);
        // ============= END Get Detail Peserta by API

        $templatePath = public_path('uploads/template/' . $sertifikat->template_sertifikat);
        $templateSize = getimagesize($templatePath); // Mendapatkan dimensi template PDF

        $pdf = new FPDI();
        $pdf->AddPage('L', 'A4');
        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1); // Ambil halaman pertama dari template PDF

        // Gunakan halaman template sebagai latar belakang
        $pdf->useTemplate($templateId);

        // Mengatur margin dalam satuan milimeter (mm)
        $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
        $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

        // Set font dan ukuran
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 10);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Pemprov Desc
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 63);
        $pdf->SetX(45);
        $pdf->Cell(0, 10, 'Pemerintah Provinsi Kalimantan Timur berdasarkan Peraturan Menteri Pertanian Republik Indonesia Nomor', 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 69);
        $pdf->SetX(45);
        $pdf->Cell(0, 10, '37/PERMENTAN/SM.120/8/2018 tentang Pedoman Pelatihan Pertanian, menyatakan bahwa :', 0, 0, 'L');
        $pdf->SetX(12.6);

        // Informasi Peserta
        if ($sertifikat->kategori_kegiatan == 'pelatihan') {
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 80);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 85.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_nip'], 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 91.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_tempat_lahir'] . ', ' . Carbon::parse($peserta[0]['peserta_tanggal_lahir'])->isoFormat('D MMMM Y'), 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 97.2);
            $pdf->SetX(150);

            // Mengambil nilai pangkat_golongan dari array $peserta
            $pesertaPangkatGolongan = $peserta[0]['peserta_pangkat_golongan'];

            // Pemisahan string sebelum dan setelah tanda titik
            list($beforeDot, $afterDot) = explode('.', $pesertaPangkatGolongan, 2);

            // Mengubah huruf pertama sebelum tanda titik menjadi huruf kapital
            $beforeDotCapitalized = strtoupper($beforeDot);

            // Gabungkan kembali string
            $pesertaPangkatGolonganFormatted = $beforeDotCapitalized . '.' . strtolower($afterDot);

            // Menambahkan kondisi
            if ($pesertaPangkatGolongan == 'ii.a') {
                $pdf->Cell(0, 10, 'Pengatur Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'ii.b') {
                $pdf->Cell(0, 10, 'Pengatur Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'ii.c') {
                $pdf->Cell(0, 10, 'Pengatur / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'ii.d') {
                $pdf->Cell(0, 10, 'Pengatur Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.a') {
                $pdf->Cell(0, 10, 'Penata Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.b') {
                $pdf->Cell(0, 10, 'Penata Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.c') {
                $pdf->Cell(0, 10, 'Penata / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iii.d') {
                $pdf->Cell(0, 10, 'Penata Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iv.a') {
                $pdf->Cell(0, 10, 'Pembina / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iv.b') {
                $pdf->Cell(0, 10, 'Pembina Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'iv.c') {
                $pdf->Cell(0, 10, 'Pembina Utama Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
            } elseif ($pesertaPangkatGolongan == 'non-asn') {
                $pdf->Cell(0, 10, 'Non ASN', 0, 0, 'L');
            }

            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 103.1);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $peserta[0]['peserta_jabatan'], 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 109);
            $pdf->SetX(150);
            // Mengambil nilai instansi dari array $peserta
            $pesertaInstansi = $peserta[0]['peserta_instansi'];
            // Pecah string menjadi array kata
            $words = explode(' ', $pesertaInstansi);
            // Ambil kata pertama dan ubah menjadi uppercase
            $firstWord = strtoupper($words[0]);
            // Ambil kata-kata selanjutnya dan ubah menjadi kapital
            $nextWords = array_map('ucfirst', array_slice($words, 1));
            // Gabungkan kembali kata-kata menjadi string
            $instansiFormatted = $firstWord . ' ' . implode(' ', $nextWords);
            $pdf->Cell(0, 10, $instansiFormatted, 0, 0, 'L');
            $pdf->SetX(12.6);
        } else {
            $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
            $pdf->SetFont("Lobster-Regular", "", 28);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 93);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'C');
            $pdf->SetX(12.6);
        }

        // Telah Mengikuti
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 128);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, $sertifikat->judul_kegiatan . ' yang diselenggarakan', 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 133);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, 'oleh UPTD Balai Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian Provinsi Kalimantan Timur', 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 138);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D MMMM') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $sertifikat->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 160);
        $pdf->SetX(45);
        $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 47, 155, 20, 0, 'PNG');
        $pdf->SetX(12.6);

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 145.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'Samarinda, ' . Carbon::parse($sertifikat->tanggal_penandatanganan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 150);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $sertifikat->jabatan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "UB", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 170);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $sertifikat->nama_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 175);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $sertifikat->pangkat_golongan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 180);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'NIP. ' . $sertifikat->nip_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Tempel Tandatangan dan Stempel
        $imagePath = public_path('/uploads/tanda_tangan_stempel/' . $sertifikat->tanda_tangan_stempel);
        $pdf->Image($imagePath, 190, 145, 50, 50); // Sesuaikan ukuran dan posisi gambar

        // Update Status Sertifikat
        $sertifikatStatus = Sertifikat::find($sertifikat->id);
        $sertifikatStatus->update([
            'status' => 'terbit',
            'tanggal_terbit' => date('d-m-Y'),
        ]);
        // Output PDF
        $pdf->Output('sertifikat' . Str::slug($peserta[0]['peserta_nama']), 'I');
        exit;
    }

    public function prosesAllPartsGenerate($sertifikats)
    {
        $pdf = new Fpdi();

        // Loop untuk membuat sertifikat dalam jumlah banyak
        foreach ($sertifikats as $key => $sertifikat) {
            // ============= Get Detail Peserta by API
            $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$sertifikat->peserta_id}";
            $response = file_get_contents($url);
            $peserta = json_decode($response, true);
            // ============= END Get Detail Peserta by API

            // Tambahkan halaman baru dari template sertifikat
            $pdf->AddPage('L', 'A4');
            $pdf->setSourceFile(public_path('uploads/template/template_blank.pdf'));
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx);

            // Mengatur margin dalam satuan milimeter (mm)
            $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
            $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

            // Set font dan ukuran
            $pdf->SetFont('Arial', 'B', 16);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 10);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
            $pdf->SetX(12.6);

            // Informasi Peserta
            if ($sertifikat->kategori_kegiatan == 'pelatihan') {
                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 80);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 85.5);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_nip'], 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 91.5);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_tempat_lahir'] . ', ' . Carbon::parse($peserta[0]['peserta_tanggal_lahir'])->isoFormat('D MMMM Y'), 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 97.2);
                $pdf->SetX(150);

                // Mengambil nilai pangkat_golongan dari array $peserta
                $pesertaPangkatGolongan = $peserta[0]['peserta_pangkat_golongan'];

                // Pemisahan string sebelum dan setelah tanda titik
                list($beforeDot, $afterDot) = explode('.', $pesertaPangkatGolongan, 2);

                // Mengubah huruf pertama sebelum tanda titik menjadi huruf kapital
                $beforeDotCapitalized = strtoupper($beforeDot);

                // Gabungkan kembali string
                $pesertaPangkatGolonganFormatted = $beforeDotCapitalized . '.' . strtolower($afterDot);

                // Menambahkan kondisi
                if ($pesertaPangkatGolongan == 'ii.a') {
                    $pdf->Cell(0, 10, 'Pengatur Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'ii.b') {
                    $pdf->Cell(0, 10, 'Pengatur Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'ii.c') {
                    $pdf->Cell(0, 10, 'Pengatur / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'ii.d') {
                    $pdf->Cell(0, 10, 'Pengatur Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.a') {
                    $pdf->Cell(0, 10, 'Penata Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.b') {
                    $pdf->Cell(0, 10, 'Penata Muda Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.c') {
                    $pdf->Cell(0, 10, 'Penata / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iii.d') {
                    $pdf->Cell(0, 10, 'Penata Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iv.a') {
                    $pdf->Cell(0, 10, 'Pembina / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iv.b') {
                    $pdf->Cell(0, 10, 'Pembina Tk. I / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'iv.c') {
                    $pdf->Cell(0, 10, 'Pembina Utama Muda / ' . $pesertaPangkatGolonganFormatted, 0, 0, 'L');
                } elseif ($pesertaPangkatGolongan == 'non-asn') {
                    $pdf->Cell(0, 10, 'Non ASN', 0, 0, 'L');
                }

                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 103.1);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $peserta[0]['peserta_jabatan'], 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 109);
                $pdf->SetX(150);
                // Mengambil nilai instansi dari array $peserta
                $pesertaInstansi = $peserta[0]['peserta_instansi'];
                // Pecah string menjadi array kata
                $words = explode(' ', $pesertaInstansi);
                // Ambil kata pertama dan ubah menjadi uppercase
                $firstWord = strtoupper($words[0]);
                // Ambil kata-kata selanjutnya dan ubah menjadi kapital
                $nextWords = array_map('ucfirst', array_slice($words, 1));
                // Gabungkan kembali kata-kata menjadi string
                $instansiFormatted = $firstWord . ' ' . implode(' ', $nextWords);
                $pdf->Cell(0, 10, $instansiFormatted, 0, 0, 'L');
                $pdf->SetX(12.6);
            } else {
                $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
                $pdf->SetFont("Lobster-Regular", "", 28);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 93);
                $pdf->SetX(10.5);
                $pdf->Cell(0, 10, $peserta[0]['peserta_nama'], 0, 0, 'C');
                $pdf->SetX(12.6);
            }

            // Buat QR Code
            QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 160);
            $pdf->SetX(45);
            $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 47, 155, 20, 0, 'PNG');
            $pdf->SetX(12.6);
        }

        // Simpan

        $pdf->Output('sertifikat_pada_kegiatan_' . Str::slug($sertifikat->judul_kegiatan, '-') . '.' . 'pdf', 'I');
    }

    // Sertifikat Siswa
    public function prosesSingleGenerateSiswa($sertifikat)
    {
        // Get Data Siswa
        $siswa = DB::table('siswas')
            ->where('id', '=', $sertifikat->siswa_id)
            ->first();
        // End Get Data Siswa

        $templatePath = public_path('uploads/template/' . $sertifikat->template_sertifikat);
        $templateSize = getimagesize($templatePath); // Mendapatkan dimensi template PDF

        $pdf = new FPDI();
        $pdf->AddPage('L', 'A4');
        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1); // Ambil halaman pertama dari template PDF

        // Gunakan halaman template sebagai latar belakang
        $pdf->useTemplate($templateId);

        // Mengatur margin dalam satuan milimeter (mm)
        $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
        $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

        // Set font dan ukuran
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 35);
        $pdf->SetX(20.5);
        $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / DPTPH-IX / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Informasi Peserta
        $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
        $pdf->SetFont("Lobster-Regular", "", 28);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 102.5);
        $pdf->SetX(20.5);
        $pdf->Cell(0, 0, ucwords($siswa->nama), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 110.5);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->tempat_lahir . ', ' . Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y'), 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 117.2);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->nomor_induk_siswa, 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 123);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->jurusan, 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 128.5);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->sekolah, 0, 0, 'L');
        $pdf->SetX(12.6);


        // Telah Mengikuti
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 137);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 0, 'Telah Melaksanakan ' . $sertifikat->judul_kegiatan . ', Terhitung Mulai Tanggal' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D MMMM') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 142);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 0, 'di UPTD Balai Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian (BPPSDMP)', 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 147);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 0, 'Provinsi Kalimantan Timur, Jln. Toyib Hadiwijaya Sempaja Samarinda.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
        $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 20, 170, 20, 0, 'PNG');

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 155);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, 'Samarinda, ' . Carbon::parse($sertifikat->tanggal_penandatanganan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 160);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, $sertifikat->jabatan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "UB", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 181);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, $sertifikat->nama_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 185.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, $sertifikat->pangkat_golongan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 190);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, 'NIP. ' . $sertifikat->nip_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Output PDF
        $pdf->Output('sertifikat' . $siswa->nama, 'I');

        exit;
    }

    // Terbit Sertifikat Siswa
    public function prosesSingleTerbitSiswa($sertifikat)
    {
        // Get Data Siswa
        $siswa = DB::table('siswas')
            ->where('id', '=', $sertifikat->siswa_id)
            ->first();
        // End Get Data Siswa

        $templatePath = public_path('uploads/template/' . $sertifikat->template_sertifikat);
        $templateSize = getimagesize($templatePath); // Mendapatkan dimensi template PDF

        $pdf = new FPDI();
        $pdf->AddPage('L', 'A4');
        $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1); // Ambil halaman pertama dari template PDF

        // Gunakan halaman template sebagai latar belakang
        $pdf->useTemplate($templateId);

        // Mengatur margin dalam satuan milimeter (mm)
        $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
        $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

        // Set font dan ukuran
        $pdf->SetFont('Arial', 'B', 16);

        // Nomor Sertifikat
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 35);
        $pdf->SetX(20.5);
        $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / DPTPH-IX / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Informasi Peserta
        $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
        $pdf->SetFont("Lobster-Regular", "", 28);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 102.5);
        $pdf->SetX(20.5);
        $pdf->Cell(0, 0, ucwords($siswa->nama), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 110.5);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->tempat_lahir . ', ' . Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y'), 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 117.2);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->nomor_induk_siswa, 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 123);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->jurusan, 0, 0, 'L');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 128.5);
        $pdf->SetX(148);
        $pdf->Cell(0, 3, $siswa->sekolah, 0, 0, 'L');
        $pdf->SetX(12.6);


        // Telah Mengikuti
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 137);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 0, 'Telah Melaksanakan ' . $sertifikat->judul_kegiatan . ', Terhitung Mulai Tanggal' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D MMMM') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 142);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 0, 'di UPTD Balai Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian (BPPSDMP)', 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 147);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 0, 'Provinsi Kalimantan Timur, Jln. Toyib Hadiwijaya Sempaja Samarinda.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
        $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 20, 170, 20, 0, 'PNG');

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 155);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, 'Samarinda, ' . Carbon::parse($sertifikat->tanggal_penandatanganan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 160);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, $sertifikat->jabatan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "UB", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 181);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, $sertifikat->nama_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 185.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, $sertifikat->pangkat_golongan_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 190);
        $pdf->SetX(170);
        $pdf->Cell(0, 0, 'NIP. ' . $sertifikat->nip_penandatangan, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Tempel Tandatangan dan Stempel
        $imagePath = public_path('/uploads/tanda_tangan_stempel/' . $sertifikat->tanda_tangan_stempel);
        $pdf->Image($imagePath, 190, 145, 50, 50); // Sesuaikan ukuran dan posisi gambar

        // Update Status Sertifikat
        $sertifikatStatus = Sertifikat::find($sertifikat->id);
        $sertifikatStatus->update([
            'status' => 'terbit',
            'tanggal_terbit' => date('d-m-Y'),
        ]);
        // Output PDF
        $pdf->Output('sertifikat' . Str::slug($siswa->nama), 'I');
        exit;
    }

    // Cetak all Sertifikat Siswa
    public function prosesAllGenerateSiswa($sertifikats)
    {
        $pdf = new Fpdi();

        // Loop untuk membuat sertifikat dalam jumlah banyak
        foreach ($sertifikats as $key => $sertifikat) {
            // Get Data Siswa
            $siswa = DB::table('siswas')
                ->where('id', '=', $sertifikat->siswa_id)
                ->first();
            // End Get Data Siswa

            // Tambahkan halaman baru dari template sertifikat
            $pdf->AddPage('L', 'A4');
            $pdf->setSourceFile(public_path('uploads/template/' . $sertifikat->template_sertifikat));
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx);

            // Mengatur margin dalam satuan milimeter (mm)
            $pdf->SetMargins(20, 20, 20); // Kiri, atas, kanan
            $pdf->SetAutoPageBreak(true, 20); // Mengatur auto page break dengan margin bawah 20 mm

            // Set font dan ukuran
            $pdf->SetFont('Arial', 'B', 16);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 35);
            $pdf->SetX(20.5);
            $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->nomor_sertifikat . ' / DPTPH-IX / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
            $pdf->SetX(12.6);

            // Informasi Peserta
            $pdf->AddFont('Lobster-Regular', '', 'Lobster-Regular.php');
            $pdf->SetFont("Lobster-Regular", "", 28);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 102.5);
            $pdf->SetX(20.5);
            $pdf->Cell(0, 0, ucwords($siswa->nama), 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 110.5);
            $pdf->SetX(148);
            $pdf->Cell(0, 3, $siswa->tempat_lahir . ', ' . Carbon::parse($siswa->tanggal_lahir)->isoFormat('D MMMM Y'), 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 117.2);
            $pdf->SetX(148);
            $pdf->Cell(0, 3, $siswa->nomor_induk_siswa, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 123);
            $pdf->SetX(148);
            $pdf->Cell(0, 3, $siswa->jurusan, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 128.5);
            $pdf->SetX(148);
            $pdf->Cell(0, 3, $siswa->sekolah, 0, 0, 'L');
            $pdf->SetX(12.6);

            // Telah Mengikuti
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 137);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 0, 'Telah Melaksanakan ' . $sertifikat->judul_kegiatan . ', Terhitung Mulai Tanggal' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D MMMM') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y'), 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 142);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 0, 'di UPTD Balai Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian (BPPSDMP)', 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 147);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 0, 'Provinsi Kalimantan Timur, Jln. Toyib Hadiwijaya Sempaja Samarinda.', 0, 0, 'C');
            $pdf->SetX(12.6);

            // Buat QR Code
            QrCode::Format('png')->merge(asset('assets2/img/logo-bppsdmp.png'), .3, true)->errorCorrection('M')->generate(route('home.show', $sertifikat->verified_code), public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png');
            $pdf->Image(public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png', 20, 170, 20, 0, 'PNG');

            // Penandatangan
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 155);
            $pdf->SetX(170);
            $pdf->Cell(0, 0, 'Samarinda, ' . Carbon::parse($sertifikat->tanggal_penandatanganan,)->isoFormat('D MMMM Y'), 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 160);
            $pdf->SetX(170);
            $pdf->Cell(0, 0, $sertifikat->jabatan_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "UB", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 181);
            $pdf->SetX(170);
            $pdf->Cell(0, 0, $sertifikat->nama_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 185.5);
            $pdf->SetX(170);
            $pdf->Cell(0, 0, $sertifikat->pangkat_golongan_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 190);
            $pdf->SetX(170);
            $pdf->Cell(0, 0, 'NIP. ' . $sertifikat->nip_penandatangan, 0, 0, 'C');
            $pdf->SetX(12.6);
        }

        // Simpan
        $outputFilePath = public_path("sertifikat/kegiatan/" . 'doc-sertifikat-kegiatan_' . Str::slug($sertifikat->judul_kegiatan, '-') . '.' . 'pdf');
        $pdf->Output($outputFilePath, 'I');

        $pdf->Output('sertifikat_pada_kegiatan_' . Str::slug($sertifikat->judul_kegiatan, '-') . '.' . 'pdf', 'I');
    }
}
