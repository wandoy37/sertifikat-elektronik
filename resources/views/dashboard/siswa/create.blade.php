@extends('dashboard.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Siswa</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-graduation-cap"></i>
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="fw-bold">Lengkapi Profil Siswa</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('siswa.store') }}" method="post">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label><b class="text-danger">*</b>Nama</label>
                                        <input type="text" class="form-control" name="nama"
                                            placeholder="Nama Lengkap..." required>
                                    </div>
                                    @error('nama')
                                        <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label><b class="text-danger">*</b>Nomor Identitas Siswa</label>
                                        <input type="text" class="form-control" name="nomor_induk_siswa"
                                            placeholder="Nomor Identitas Siswa / NISN..."
                                            value="{{ old('nomor_induk_siswa') }}" required>
                                        @error('nomor_induk_siswa')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir"
                                            placeholder="Tempat Lahir...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Tanggal Lahir</label>
                                        <input type="text" class="form-control" id="datepicker" name="tanggal_lahir"
                                            placeholder="Tanggal Lahir">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Jurusan</label>
                                        <input type="text" class="form-control" name="jurusan" placeholder="Jurusan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Sekolah</label>
                                        <input type="text" class="form-control" name="sekolah" placeholder="Sekolah">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-3 mb-3">
                                <button class="btn btn-success">Simpan</button>
                                <a href="{{ route('dashboard.index') }}" class="btn btn-danger">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $('#datepicker').datetimepicker({
                format: 'DD-MM-YYYY',
            });
        </script>
    @endpush
@endsection
