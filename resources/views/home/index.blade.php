@extends('home.app')
@section('title', 'Beranda')

@section('content')
    <main id="main">

        <!-- Hero Section - Home Page -->
        <section id="hero" class="hero">

            <img src="{{ asset('assets2') }}/img/hero-bg.jpg" alt="" data-aos="fade-in">

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h2 data-aos="fade-up" data-aos-delay="100">SETKON</h2>
                        <p data-aos="fade-up" data-aos-delay="200">
                            Sistem Informasi Sertifikat Elektronik
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <form action="{{ route('home.sertifikat.find') }}" class="sign-up-form d-flex" data-aos="fade-up"
                            data-aos-delay="100">
                            @csrf
                            <input type="text" class="form-control" name="code" placeholder="Cari kode sertifikat">
                            <input type="submit" class="btn btn-primary" value="Search">
                        </form>
                    </div>
                    <div class="col-lg-12 fixed-bottom text-center mb-4">
                        <p style="font-size: 14px;">&copy; <i>Copyright</i> <strong class="px-1">UPTD BPPSDMP
                                {{ date('Y') }}</strong>
                            <i>All Rights
                                Reserved</i>
                        </p>
                        <div class="credits" style="font-size: 14px;">
                            <i>Made With</i><a href="https://github.com/wandoy37/sertifikat-elektronik"> Muhammad
                                Riswandi</a>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- End Hero Section -->

    </main>
@endsection
