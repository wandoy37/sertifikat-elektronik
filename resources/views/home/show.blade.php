@extends('home.app')
@if ($sertifikat->peserta_id !== '-')
    @php
        // ============= Get Detail Peserta by API
        $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$kegiatan->peserta_id}";
        $response = file_get_contents($url);
        $peserta = json_decode($response, true);
        // ============= END Get Detail Peserta by API
    @endphp
    @section('title', $peserta[0]['peserta_nama'])
@endif
@if ($sertifikat->orang_id !== '-')
    @php
        $orang = DB::table('orangs')
            ->where('id', '=', $sertifikat->orang_id)
            ->first();
    @endphp
    @section('title', $orang->nama)
@endif
@if ($sertifikat->siswa_id !== '-')
    @php
        $siswa = DB::table('siswas')
            ->where('id', '=', $sertifikat->siswa_id)
            ->first();
    @endphp
    @section('title', $siswa->nama)
@endif
@if ($sertifikat->narasumber_id !== '-')
    @php
        $narasumber = DB::table('narasumbers')
            ->where('id', '=', $sertifikat->narasumber_id)
            ->first();
    @endphp
    @section('title', $narasumber->nama)
@endif


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
                                            <th scope="row" style="font-size: 26px;">
                                                @if ($sertifikat->peserta_id !== '-')
                                                    {{ $peserta[0]['peserta_nama'] }}
                                                @endif
                                                @if ($sertifikat->orang_id !== '-')
                                                    {{ $orang->nama }}
                                                @endif
                                                @if ($sertifikat->siswa_id !== '-')
                                                    {{ $siswa->nama }}
                                                @endif
                                                @if ($sertifikat->narasumber_id !== '-')
                                                    {{ $narasumber->nama }}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>

                                            @if ($sertifikat->peserta_id !== '-')
                                                <td colspan="2" style="font-size: 16px;">Telah mengikuti pelatihan
                                                    <b>"</b><b>{{ $kegiatan->judul_kegiatan }}</b><b>"</b> yang
                                                    diselenggarakan
                                                    oleh Unit Pelaksana Teknis Dinas (UPTD) Balai Penyuluhan dan
                                                    Pengembangan
                                                    Sumber Daya Manusia Pertanian (BPPSDMP) Provinsi Kalimantan Timur mulai
                                                    tanggal
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMMM Y') }}
                                                    s.d.
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') }}
                                                    dengan jumlah {{ $kegiatan->total_jam_kegiatan }} jam berlatih.
                                                </td>
                                            @endif
                                            @if ($sertifikat->orang_id !== '-')
                                                <td colspan="2" style="font-size: 16px;">Telah mengikuti
                                                    <b>"</b><b>{{ $kegiatan->judul_kegiatan }}</b><b>"</b> yang
                                                    diselenggarakan
                                                    oleh Unit Pelaksana Teknis Dinas (UPTD) Balai Penyuluhan dan
                                                    Pengembangan
                                                    Sumber Daya Manusia Pertanian (BPPSDMP) Provinsi Kalimantan Timur mulai
                                                    tanggal
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMMM Y') }}
                                                    s.d.
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') }}
                                                    .
                                                </td>
                                            @endif
                                            @if ($sertifikat->siswa_id !== '-')
                                                <td colspan="2" style="font-size: 16px;">Telah Melaksanakan
                                                    <b>"</b><b>{{ $kegiatan->judul_kegiatan }}</b><b>"</b> terhitung mulai
                                                    tanggal
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMMM Y') }}
                                                    s.d.
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') }}
                                                    di Unit Pelaksana Teknis Dinas (UPTD) Balai Penyuluhan dan
                                                    Pengembangan
                                                    Sumber Daya Manusia Pertanian (BPPSDMP) Provinsi Kalimantan Timur
                                                </td>
                                            @endif
                                            @if ($sertifikat->narasumber_id !== '-')
                                                <td colspan="2" style="font-size: 16px;">Sebagai Pelatih Pada
                                                    <b>"</b><b>{{ $kegiatan->judul_kegiatan }}</b><b>"</b>
                                                    diselenggarakan pada tanggal
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMMM Y') }}
                                                    s.d.
                                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMMM Y') }}
                                                    di Unit Pelaksana Teknis Dinas (UPTD) Balai Penyuluhan dan
                                                    Pengembangan
                                                    Sumber Daya Manusia Pertanian (BPPSDMP) Provinsi Kalimantan Timur.
                                                </td>
                                            @endif

                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                @if ($sertifikat->narasumber_id !== '-')
                                                    <a href="{{ route('home.narasumber.download', $sertifikat->id) }}"
                                                        class="btn btn-danger" style="border-radius: 15px;">Download
                                                        E-Sertifikat</a>
                                                @else
                                                    <a href="{{ route('home.sertifikat.download', $sertifikat->id) }}"
                                                        class="btn btn-danger" style="border-radius: 15px;">Download
                                                        E-Sertifikat</a>
                                                @endif
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
