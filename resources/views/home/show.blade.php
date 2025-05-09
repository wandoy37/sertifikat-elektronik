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
        $orang = DB::table('orangs')->where('id', '=', $sertifikat->orang_id)->first();
    @endphp
    @section('title', $orang->nama)
@endif
@if ($sertifikat->siswa_id !== '-')
    @php
        $siswa = DB::table('siswas')->where('id', '=', $sertifikat->siswa_id)->first();
    @endphp
    @section('title', $siswa->nama)
@endif
@if ($sertifikat->narasumber_id !== '-')
    @php
        $narasumber = DB::table('narasumbers')->where('id', '=', $sertifikat->narasumber_id)->first();
    @endphp
    @section('title', $narasumber->nama)
@endif


@section('content')
    <main id="main">

        <!-- Hero Section - Home Page -->
        <section id="hero" class="hero">

            <img src="{{ asset('assets2') }}/img/hero-bg.jpg" alt="" data-aos="fade-in">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h4 class="text-center">
                            UPTD BPPSDMP KALTIM
                        </h4>
                        <h5 class="text-center my-4">
                            NASKAH INI DIKELOLA DENGAN APLIKASI SISTEM INFORMASI SERTIFIKAT ELEKTRONIK
                        </h5>
                    </div>
                    @if ($sertifikat->status == 'belum terbit')
                        <div class="col-lg-8">
                            <div class="alert alert-light" role="alert">
                                Sertifikat ini sedang dalam proses penandatanganan elektronik, silahkan periksa
                                kembali pada waktu yang akan datang
                            </div>
                        </div>
                    @else
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <p class="text-dark">Naskah ini telah ditandatangani oleh :</p>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-hover">
                                        <tbody class="">
                                            <tr>
                                                <td width="25%">Nama</td>
                                                <td width="1%">:</td>
                                                <td>{{ $kegiatan->penandatangan_nama }}</td>
                                            </tr>
                                            <tr>
                                                <td width="25%">Jabatan</td>
                                                <td width="1%">:</td>
                                                <td>{{ $kegiatan->penandatangan_jabatan }}</td>
                                            </tr>
                                            <tr>
                                                <td width="25%">Intansi</td>
                                                <td width="1%">:</td>
                                                <td>Dinas Pangan, Tanaman Pangan dan Hortikultura Provinsi Kalimantan Timur
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%">Ditandatangani pada</td>
                                                <td width="1%">:</td>
                                                <td>
                                                    {{ \Carbon\Carbon::createFromFormat('d-m-Y', $kegiatan->tanggal_terbit)->translatedFormat('l, d F Y') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <td colspan="3" class="py-3">
                                                    <a href="{{ route('home.sertifikat.preview', $sertifikat->id) }}"
                                                        class="btn btn-danger" style="border-radius: 15px;">Lihat File
                                                        Digital</a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </section>
        <!-- End Hero Section -->

    </main>
@endsection
