@extends('dashboard.layouts.app')
@section('title', 'Buat Orang Baru')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Orang</h4>
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
                        <h3 class="fw-bold">Lengkapi Data Orang</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orang.store') }}" method="POST">
                            @csrf
                            <div class="form-group form-group-default">
                                <label><b class="text-danger">*</b>Nama</label>
                                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap..."
                                    required>
                            </div>
                            @error('nama')
                                <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                            @enderror
                            <div class="form-group form-group-default">
                                <label>Nomor Identitas Kependudukan (NIK)</label>
                                <input type="text" class="form-control" name="nik"
                                    placeholder="Nomor Identitas Kependudukan (NIK)" value="{{ old('nik') }}" required>
                                @error('nik')
                                    <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="text-right mt-3 mb-3">
                                <button class="btn btn-success">Simpan</button>
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