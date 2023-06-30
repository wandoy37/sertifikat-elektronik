@extends('dashboard.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Dashboard</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}">Dashboard</a>
                </li>
            </ul>
        </div>

        {{-- Notify --}}
        <div id="success" data-flash="{{ session('success') }}"></div>
        <div id="fails" data-flash="{{ session('fails') }}"></div>
        {{-- ====== --}}


        @if (Auth::user()->role == 'peserta')
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                            <div class="profile-picture">
                                <div class="avatar avatar-xl">
                                    <img src="../assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="user-profile text-center">
                                <div class="name">{{ Auth::user()->username }}</div>
                                <div class="job">{{ Auth::user()->email }}</div>
                                <div class="badge badge-primary">{{ Auth::user()->role }}</div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row user-stats text-center">
                                <div class="col">
                                    @if (Auth::user()->peserta == null)
                                        <a href="{{ route('peserta.create', Auth::user()->username) }}"
                                            class="btn btn-outline-primary">Lengkapi
                                            Profil</a>
                                    @else
                                        <a href="{{ route('peserta.show', Auth::user()->username) }}"
                                            class="btn btn-outline-primary">Lihat
                                            Profil</a>
                                        <a href="{{ route('peserta.edit', Auth::user()->username) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-pen"></i>
                                            Profil
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <a href="http://">
                                            <p class="card-category">Sertifikat</p>
                                            <h4 class="card-title">1303</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-12">
                    <h1>Selamat Datang role {{ Auth::user()->role }}</h1>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            // Notify
            var flash = $('#success').data('flash');
            if (flash) {
                $.notify({
                    // options
                    icon: 'fas fa-check',
                    title: 'Berhasil',
                    message: '{{ session('success') }}',
                }, {
                    // settings
                    type: 'success',
                });
            }
            var flash = $('#fails').data('flash');
            if (flash) {
                $.notify({
                    // options
                    icon: 'fas fa-ban',
                    title: 'Gagal',
                    message: '{{ session('fails') }}',
                }, {
                    // settings
                    type: 'danger',
                });
            }
        </script>
    @endpush
@endsection
