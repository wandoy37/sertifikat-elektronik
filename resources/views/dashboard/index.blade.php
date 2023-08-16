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
                                        <h3 class="text-muted">Sertifikat</h3>
                                        <h4 class="card-title">
                                            {{-- {{ $sertifikats->where('peserta_id', Auth::user()->peserta_id)->count() ?? '0' }} --}}
                                            @if (Auth::user()->peserta)
                                                {{ $sertifikats->where('peserta_id', Auth::user()->peserta->id)->where('status', 'terbit')->count() }}
                                            @else
                                                0
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <hr>
                    <h1 class="fw-bold">Daftar Kegiatan</h1>
                </div>

                <div class="col-lg-12">
                    <div class="row">
                        @foreach ($kegiatans->where('status', 'open')->all() as $kegiatan)
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card card-primary">
                                            <div class="card-body skew-shadow">
                                                <h1 class="text-uppercase">{{ $kegiatan->kategori->title }}</h1>
                                                <h5 class="op-1">{{ $kegiatan->judul_kegiatan }}</h5>
                                                <div class="mt-4">
                                                    @php
                                                        $userPeserta = Auth::user()->peserta;
                                                        $sertifikatExists = $sertifikats
                                                            ->where('kegiatan_id', $kegiatan->id)
                                                            ->where('peserta_id', optional($userPeserta)->id)
                                                            ->first();
                                                    @endphp
                                                    @if ($sertifikatExists == null)
                                                        @if (!Auth::user()->peserta == null)
                                                            <form action="{{ route('sertifikat.store') }}" method="post">
                                                                @csrf
                                                                <input type="text" name="kegiatan_id"
                                                                    value="{{ $kegiatan->id }}" hidden>
                                                                <input type="text" name="peserta_id"
                                                                    value="{{ optional($userPeserta)->id }}" hidden>
                                                                <button type="submit" class="btn btn-light btn-rounded">
                                                                    <i class="fas fa-plus"></i>
                                                                    Daftar
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <span class="text-success">
                                                            <i class="fas fa-check">
                                                                Terdaftar
                                                            </i>
                                                        </span>
                                                    @endif
                                                    <div class="pull-right">
                                                        <small>
                                                            <i>
                                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->format('d F') }}
                                                                s.d.
                                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->format('d F Y') }}
                                                            </i>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($kegiatans->where('status', 'open')->all() == null)
                            <div class="col-lg-12">
                                <h3>Belum ada kegiatan yang diselenggarakan</h3>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-users text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-title">Peserta</p>
                                        <span class="badge badge-info">{{ $pesertas->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-podcast text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-title">Kegiatan</p>
                                        <span class="badge badge-info">{{ $kegiatans->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-certificate text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-title">Sertifikat</p>
                                        <span class="badge badge-info">
                                            {{ $sertifikats->where('status', 'terbit')->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            $('#basic-datatables').DataTable();
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
