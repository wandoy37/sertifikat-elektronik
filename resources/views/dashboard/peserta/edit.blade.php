@extends('dashboard.layouts.app')
@section('title', 'Edit Profil')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Profil</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-users"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('peserta.update', $peserta->user->username) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b class="text-danger">*</b>Nama</label>
                                        <input type="text" class="form-control" name="nama"
                                            placeholder="Nama Lengkap..." value="{{ old('nama', $peserta->nama) }}"
                                            required>
                                        @error('nama')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><b class="text-danger">*</b>Nomor Identitas</label>
                                        <input type="text" class="form-control" name="nomor_identitas"
                                            placeholder="Nomor Identitas..." value="{{ $peserta->nomor_identitas }}">
                                        @error('nomor_identitas')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir"
                                            placeholder="Tempat Lahir..." value="{{ $peserta->tempat_lahir }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="text" class="form-control" id="datepicker" name="tanggal_lahir"
                                            placeholder="Tanggal Lahir" value="{{ $peserta->tanggal_lahir }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-control" id="gender" name="jenis_kelamin">
                                            <option>-pilih jenis kelamin-</option>
                                            <option
                                                {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'pria' ? 'selected' : '' }}
                                                value="pria">Pria</option>
                                            <option
                                                {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'wanita' ? 'selected' : '' }}
                                                value="wanita">Wanita</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Instansi</label>
                                        <input type="text" class="form-control" name="instansi" placeholder="Instansi"
                                            value="{{ $peserta->instansi }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 mb-1">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pangkat/Golongan</label>
                                        <input type="text" class="form-control" name="pangkat_golongan"
                                            placeholder="Pangkat/Golongan" value="{{ $peserta->pangkat_golongan }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <input type="text" class="form-control" name="jabatan" placeholder="Jabatan"
                                            value="{{ $peserta->jabatan }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-default">
                                        <label>Foto</label>
                                        <input type="file" class="form-control" name="foto">
                                        <label class="fw-bold mt-4">Foto saat ini</label>
                                        <img src="{{ asset('foto_peserta/' . $peserta->foto) }}" class="img-thumbnail"
                                            width="100%" alt="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <a href="{{ route('dashboard.index') }}" class="btn btn-danger">Kembali</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-sync"></i>
                                            Update
                                        </button>
                                    </div>
                                </div>
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
