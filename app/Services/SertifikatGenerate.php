<?php

namespace App\Services;

use App\Models\Sertifikat;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class SertifikatGenerate
{
    public function prosesSingleGenerate($sertifikat)
    {
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
        $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->id . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
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
        if ($sertifikat->kategori_kegiatan == 'Pelatihan') {
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 80);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->nama_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 85.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->nomor_identitas_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 91.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->tempat_lahir_peserta . ', ' . Carbon::parse($sertifikat->tanggal_lahir_peserta)->isoFormat('D MMMM Y'), 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 97.2);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->pangkat_golongan_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 103.1);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->jabatan_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 109);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->instansi_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);
        } else {
            $pdf->SetFont("helvetica", "", 28);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 93);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, $sertifikat->nama_peserta, 0, 0, 'C');
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
        $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $sertifikat->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        QrCode::Format('png')->generate(route('home.show', $sertifikat->id), public_path() . '/qrcode/' . 'qr_sertifikat_' . $sertifikat->id . '.' . 'png');
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 160);
        $pdf->SetX(45);
        $pdf->Image(public_path() . '/qrcode/' . 'qr_sertifikat_' . $sertifikat->id . '.' . 'png', 47, 155, 20, 0, 'PNG');
        $pdf->SetX(12.6);

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 145.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'Samarinda, ' . date('d, F Y'), 0, 0, 'C');
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
        $pdf->Output('sertifikat' . Str::slug($sertifikat->nama_peserta), 'I');

        exit;
    }

    public function prosesAllGenerate($sertifikats)
    {
        $pdf = new Fpdi();

        // Loop untuk membuat sertifikat dalam jumlah banyak
        foreach ($sertifikats as $key => $sertifikat) {
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
            $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->id . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
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
            if ($sertifikat->kategori_kegiatan == 'Pelatihan') {
                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 80);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $sertifikat->nama_peserta, 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 85.5);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $sertifikat->nomor_identitas_peserta, 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 91.5);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $sertifikat->tempat_lahir_peserta . ', ' . Carbon::parse($sertifikat->tanggal_lahir_peserta)->isoFormat('D MMMM Y'), 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 97.2);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $sertifikat->pangkat_golongan_peserta, 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 103.1);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $sertifikat->jabatan_peserta, 0, 0, 'L');
                $pdf->SetX(12.6);

                $pdf->SetFont("helvetica", "", 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 109);
                $pdf->SetX(150);
                $pdf->Cell(0, 10, $sertifikat->instansi_peserta, 0, 0, 'L');
                $pdf->SetX(12.6);
            } else {
                $pdf->SetFont("helvetica", "", 28);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetXY(0, 93);
                $pdf->SetX(10.5);
                $pdf->Cell(0, 10, $sertifikat->nama_peserta, 0, 0, 'C');
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
            $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $sertifikat->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
            $pdf->SetX(12.6);

            // Buat QR Code
            // Buat QR Code
            QrCode::Format('png')->generate(route('home.show', $sertifikat->id), public_path() . '/qrcode/' . 'qr_sertifikat_' . $sertifikat->id . '.' . 'png');
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 160);
            $pdf->SetX(45);
            $pdf->Image(public_path() . '/qrcode/' . 'qr_sertifikat_' . $sertifikat->id . '.' . 'png', 47, 155, 20, 0, 'PNG');
            $pdf->SetX(12.6);

            // Penandatangan
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 145.5);
            $pdf->SetX(170);
            $pdf->Cell(0, 10, 'Samarinda, ' . date('d, F Y'), 0, 0, 'C');
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
        $pdf->Output($outputFilePath, 'F');

        $pdf->Output('sertifikat_pada_kegiatan_' . Str::slug($sertifikat->judul_kegiatan, '-') . '.' . 'pdf', 'I');
    }

    public function prosesSingleTerbit($sertifikat)
    {
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
        $pdf->Cell(0, 103, 'Nomor : ' . $sertifikat->kode_kegiatan . ' / ' . $sertifikat->id . ' / BPPSDMP / ' . $sertifikat->tahun_kegiatan, 0, 0, 'C');
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
        if ($sertifikat->kategori_kegiatan == 'Pelatihan') {
            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 80);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->nama_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 85.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->nomor_identitas_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 91.5);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->tempat_lahir_peserta . ', ' . Carbon::parse($sertifikat->tanggal_lahir_peserta)->isoFormat('D MMMM Y'), 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 97.2);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->pangkat_golongan_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 103.1);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->jabatan_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            $pdf->SetFont("helvetica", "", 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 109);
            $pdf->SetX(150);
            $pdf->Cell(0, 10, $sertifikat->instansi_peserta, 0, 0, 'L');
            $pdf->SetX(12.6);

            // Foto Peserta
            if (!$sertifikat->foto_peserta == null) {
                $fotoPath = public_path('/foto_peserta/' . $sertifikat->foto_peserta);
                $pdf->Image($fotoPath, 67, 79, 30, 40); // Sesuaikan ukuran dan posisi gambar
            }
        } else {
            $pdf->SetFont("helvetica", "", 28);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(0, 93);
            $pdf->SetX(10.5);
            $pdf->Cell(0, 10, $sertifikat->nama_peserta, 0, 0, 'C');
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
        $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($sertifikat->tanggal_mulai_kegiatan)->isoFormat('D') . ' s.d. ' . Carbon::parse($sertifikat->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $sertifikat->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        QrCode::Format('png')->generate(route('home.show', $sertifikat->id), public_path() . '/qrcode/' . 'qr_sertifikat_' . $sertifikat->id . '.' . 'png');
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 160);
        $pdf->SetX(45);
        $pdf->Image(public_path() . '/qrcode/' . 'qr_sertifikat_' . $sertifikat->id . '.' . 'png', 47, 155, 20, 0, 'PNG');
        $pdf->SetX(12.6);

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 145.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'Samarinda, ' . date('d, F Y'), 0, 0, 'C');
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
        $outputFilePath = public_path("sertifikat/" . 'doc-sertifikat-' . $sertifikat->id . '.' . 'pdf');
        $pdf->Output($outputFilePath, 'F');
        $pdf->Output($outputFilePath, 'I');

        $pdf->Output($outputFilePath, 'I');
        exit;
    }
}
