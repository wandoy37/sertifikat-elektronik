@extends('dashboard.layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Narasumber</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('narasumber.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
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
                        <h3 class="fw-bold">Lengkapi Profil Narasumber</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('narasumber.update', $narasumber->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label><b class="text-danger">*</b>Nama</label>
                                        <input type="text" class="form-control" name="nama"
                                            placeholder="Nama Lengkap..." value="{{ old('nama', $narasumber->nama) }}"
                                            required>
                                    </div>
                                    @error('nama')
                                        <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label><b class="text-danger">*</b>NIP</label>
                                        <input type="text" class="form-control" name="nip" placeholder="NIP..."
                                            value="{{ old('nip', $narasumber->nip) }}" required>
                                        @error('nip')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Tempat Lahir</label>
                                        <input type="text" class="form-control" name="tempat_lahir"
                                            value="{{ old('tempat_lahir', $narasumber->tempat_lahir) }}"
                                            placeholder="Tempat Lahir...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Tanggal Lahir</label>
                                        <input type="text" class="form-control" id="datepicker" name="tanggal_lahir"
                                            value="{{ old('tanggal_lahir', $narasumber->tanggal_lahir) }}"
                                            placeholder="Tanggal Lahir">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Pangkat / Golongan</label>
                                        <input type="text" class="form-control" name="pangkat_golongan"
                                            value="{{ old('pangkat_golongan', $narasumber->pangkat_golongan) }}"
                                            placeholder="Pangkat / Golongan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Jabatan</label>
                                        <input type="text" class="form-control" name="jabatan"
                                            value="{{ old('jabatan', $narasumber->jabatan) }}" placeholder="Jabatan">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Instansi</label>
                                        <input type="text" class="form-control" name="instansi"
                                            value="{{ old('instansi', $narasumber->instansi) }}" placeholder="Instansi">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-3 mb-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-sync"></i>
                                    Update
                                </button>
                                <a href="{{ route('narasumber.index') }}" class="btn btn-danger">Kembali</a>
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
