@extends('dashboard.layouts.app')
@section('title', 'Edit Orang')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Orang</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-user"></i>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="fw-bold">Edit Data Orang</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orang.update', $orang->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group form-group-default">
                                <label><b class="text-danger">*</b>Nama</label>
                                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap..."
                                    value="{{ old('nama', $orang->nama) }}" required>
                            </div>
                            @error('nama')
                                <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                            @enderror
                            <div class="form-group form-group-default">
                                <label>Nomor Identitas Kependudukan (NIK)</label>
                                <input type="text" class="form-control" name="nik"
                                    placeholder="Nomor Identitas Kependudukan (NIK)" value="{{ old('nik', $orang->nik) }}"
                                    required>
                                @error('nik')
                                    <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="text-right mt-3 mb-3">
                                <button class="btn btn-success">Update</button>
                                <a href="{{ route('orang.index') }}" class="btn btn-danger">Kembali</a>
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
