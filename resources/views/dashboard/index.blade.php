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
                                    <span class="badge badge-info">{{ count($pesertas) }}</span>
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

        @php
            $penandatanganPengguna = $penandatangans->where('user_id', Auth::user()->id)->first();
        @endphp

        {{-- @if ($penandatangan = $penandatangans->where('user_id', Auth::user()->id)->isEmpty()) --}}
        @if (is_null($penandatanganPengguna))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger" role="alert">
                        Data diri Anda belum lengkap, <a href="{{ route('user.edit', Auth::user()->username) }}"
                            class="alert-link">Lengkapi Biodata</a>. Silakan
                        lengkapi data diri Anda untuk dapat menandatangani sertifikat !!!
                    </div>
                </div>
            </div>
        @endif

        @if (empty($penandatanganPengguna->passphrase))
            <div class="alert alert-danger" role="alert">
                Passphrase Anda belum dibuat, <a href="{{ route('user.edit', Auth::user()->username) }}"
                    class="alert-link">Buat Passphrase Sekarang</a>.
                Silakan buat Passphrase Anda untuk dapat menandatangani sertifikat !!!
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
