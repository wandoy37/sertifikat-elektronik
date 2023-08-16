@extends('dashboard.layouts.app')
@section('title', 'Edit Penandatangan')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Penandatangan</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-file-signature"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('penandatangan.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Penandatangan</h3>
                    </div>
                    <form action="{{ route('penandatangan.update', $penandatangan->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama</label>
                                <input id="nama" type="text" class="form-control @error('nama') has-error @enderror"
                                    name="nama" placeholder="Nama" value="{{ old('nama', $penandatangan->nama) }}">
                                @error('nama')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>NIP</label>
                                <input id="nip" type="text" class="form-control" name="nip" placeholder="NIP"
                                    value="{{ old('nip', $penandatangan->nip) }}">
                                @error('nip')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Pangkat/Golongan</label>
                                <input id="pangkat_golongan" type="text" class="form-control" name="pangkat_golongan"
                                    placeholder="Pangkat/Golongan"
                                    value="{{ old('nip', $penandatangan->pangkat_golongan) }}">
                                @error('pangkat_golongan', $penandatangan->pangkat_golongan)
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>jabatan</label>
                                <input id="jabatan" type="type" name="jabatan" class="form-control"
                                    placeholder="jabatan" value="{{ old('jabatan', $penandatangan->jabatan) }}">
                            </div>
                            <div class="form-group">
                                <label>Tanda Tangan & Stempel</label>
                                <input id="tanda_tangan_stempel" type="file" class="form-control"
                                    name="tanda_tangan_stempel" value="{{ old('tanda_tangan_stempel') }}">
                                @error('tanda_tangan_stempel')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-action float-right mb-3">
                                <button type="submit" class="btn btn-primary btn-rounded btn-login">
                                    <i class="fas fa-sync"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-header">
                        <i>Current Signature</i>
                    </div>
                    <div class="card-body">
                        <img src="{{ asset('uploads/tanda_tangan_stempel/' . $penandatangan->tanda_tangan_stempel) }}"
                            class="img-fluid" width="50%" alt="">
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
