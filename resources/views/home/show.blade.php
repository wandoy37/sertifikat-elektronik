@php
    // ============= Get Detail Peserta by API
    $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$kegiatan->peserta_id}";
    $response = file_get_contents($url);
    $peserta = json_decode($response, true);
    // ============= END Get Detail Peserta by API
@endphp

@extends('home.app')
@section('title', $peserta[0]['peserta_nama'])

@section('content')
    <main id="main">

        <!-- Hero Section - Home Page -->
        <section id="hero" class="hero">

            <img src="{{ asset('assets2') }}/img/hero-bg.jpg" alt="" data-aos="fade-in">

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody class="text-center">
                                        <tr>
                                            <th scope="row" style="font-size: 26px;">{{ $peserta[0]['peserta_nama'] }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size: 16px;">Telah mengikuti pelatihan
                                                <b>"</b><b>{{ $kegiatan->judul_kegiatan }}</b><b>"</b> yang diselenggarakan
                                                oleh Unit Pelaksana Teknis Dinas (UPTD) Balai Penyuluhan dan Pengembangan
                                                Sumber Daya Manusia Pertanian (BPPSDMP) Provinsi Kalimantan Timur mulai
                                                tanggal
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMMM Y') }}
                                                s.d.
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') }}
                                                dengan jumlah {{ $kegiatan->total_jam_kegiatan }} jam berlatih.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <a href="{{ route('home.sertifikat.download', $sertifikat->id) }}"
                                                    class="btn btn-danger" style="border-radius: 15px;">Download
                                                    E-Sertifikat</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- End Hero Section -->

    </main>
@endsection
