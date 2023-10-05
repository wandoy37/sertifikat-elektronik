@php
    // ============= Get Detail Peserta by API
    $peserta_id = $sertifikat->peserta_id;
    $url = "http://simpeltan.test/api/data-peserta/{$sertifikat->peserta_id}";
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
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tbody class="text-center">
                                        <tr>
                                            <th scope="row" style="font-size: 32px;">{{ $peserta[0]['peserta_nama'] }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size: 12px;">Telah mengikuti pelatihan
                                                <b>"</b><b>{{ $sertifikat->judul_kegiatan }}</b><b>"</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <a href="{{ route('home.sertifikat.download', $sertifikat->id) }}"
                                                    class="btn btn-danger" style="border-radius: 15px;">Download
                                                    E-Sertifikat</a>
                                                <br class="my-4">
                                                <a href="{{ route('home.sertifikat.preview', $sertifikat->id) }}"
                                                    class="text-decoration-none" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                    Preview
                                                </a>
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
