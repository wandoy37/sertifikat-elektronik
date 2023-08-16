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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="fw-bold">Lengkapi Profil Peserta</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('peserta.store', $user->username) }}" method="post">
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
                                        <label><b class="text-danger">*</b>Nomor Identitas</label>
                                        <input type="text" class="form-control" name="nomor_identitas"
                                            placeholder="Nomor Identitas..."
                                            value="{{ old('nomor_identitas', Auth::user()->username) }}" required>
                                        @error('nomor_identitas')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir"
                                            placeholder="Tempat Lahir...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Tanggal Lahir</label>
                                        <input type="text" class="form-control" id="datepicker" name="tanggal_lahir"
                                            placeholder="Tanggal Lahir">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control" id="gender" name="jenis_kelamin">
                                            <option>-pilih jenis kelamin-</option>
                                            <option value="pria">Pria</option>
                                            <option value="wanita">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group form-group-default">
                                        <label>Instansi</label>
                                        <input type="text" class="form-control" name="instansi" placeholder="Instansi">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 mb-1">
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Pangkat/Golongan</label>
                                        <input type="text" class="form-control" name="pangkat_golongan"
                                            placeholder="Pangkat/Golongan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Jabatan</label>
                                        <input type="text" class="form-control" name="jabatan" placeholder="Jabatan">
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
                format: 'MM/DD/YYYY',
            });
        </script>
    @endpush
@endsection
