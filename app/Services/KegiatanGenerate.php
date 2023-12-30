<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KegiatanGenerate
{
    public function prosesKegiatanGenerate($kegiatan)
    {
        $templatePath = public_path('uploads/template/' . $kegiatan->kategori->template);
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
        // ===Skip===

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
        $pdf->Cell(0, 10, '37/PERMENTAN/SM.120/8/2018 Tentang Pedoman Pelatihan Pertanian, Menyatakan Bahwa :', 0, 0, 'L');
        $pdf->SetX(12.6);

        // Informasi Peserta
        // ===Skip===

        // Telah Mengikuti
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 128);
        $pdf->SetX(10.5);
        $pdf->Cell(0, 10, $kegiatan->judul_kegiatan . ' yang diselenggarakan', 0, 0, 'C');
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
        $pdf->Cell(0, 10, 'mulai tanggal ' . Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D') . ' s.d. ' . Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') . ' dengan jumlah ' . $kegiatan->total_jam_kegiatan . ' jam berlatih.', 0, 0, 'C');
        $pdf->SetX(12.6);

        // Buat QR Code
        // ===Skip===

        // Penandatangan
        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 145.5);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'Samarinda, ' . Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y'), 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 150);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $kegiatan->penandatangan->jabatan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "UB", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 170);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $kegiatan->penandatangan->nama, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 175);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, $kegiatan->penandatangan->pangkat_golongan, 0, 0, 'C');
        $pdf->SetX(12.6);

        $pdf->SetFont("helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(0, 180);
        $pdf->SetX(170);
        $pdf->Cell(0, 10, 'NIP. ' . $kegiatan->penandatangan->nip, 0, 0, 'C');
        $pdf->SetX(12.6);

        // Output PDF
        $pdf->Output('kegiatan' . Str::slug($kegiatan->judul_kegiatan), 'I');

        exit;
    }
}
