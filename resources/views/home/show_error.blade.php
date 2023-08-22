@extends('home.app')
@section('title', 'Tidak ditemukan')

@section('content')
    <main id="main">

        <!-- Hero Section - Home Page -->
        <section id="hero" class="hero">

            <img src="{{ asset('assets2') }}/img/hero-bg.jpg" alt="" data-aos="fade-in">

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>{{ $pesan }} !</h2>
                        <a href="{{ route('home.index') }}">Kembali</a>
                    </div>
                </div>
            </div>

        </section>
        <!-- End Hero Section -->

    </main>
@endsection
