@extends('dashboard.layouts.app')
@section('title', 'Tambah Kegiatan')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Kegiatan</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-podcast"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Buat Kegiatan Baru</h3>
                    </div>
                    <form action="{{ route('kegiatan.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text fw-bold">Kode Kegiatan</span>
                                            </div>
                                            <input type="text" class="form-control" name="kode_kegiatan"
                                                value="{{ old('kode_kegiatan') }}">
                                        </div>
                                        @error('kode_kegiatan')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold">Judul Kegiatan</label>
                                        <input type="text" class="form-control" name="judul_kegiatan"
                                            value="{{ old('judul_kegiatan') }}">
                                        @error('judul_kegiatan')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold">Kategori Kegiatan</label>
                                        <div class="select2-input">
                                            <select id="basic" name="kategori_id" class="form-control">
                                                <option value="">-pilih kategori-</option>
                                                @foreach ($kategories as $kategori)
                                                    @if (old('kategori_id') == $kategori->id)
                                                        <option value="{{ $kategori->id }}" selected>{{ $kategori->title }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $kategori->id }}">{{ $kategori->title }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('kategori_id')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold">Penandatangan Kegiatan</label>
                                        <div class="select2-input">
                                            <select id="basic2" name="penandatangan_id" class="form-control">
                                                <option value="">-pilih penandatanga-</option>
                                                @foreach ($penandatangans as $penandatangan)
                                                    @if (old('penandatangan_id') == $penandatangan->id)
                                                        <option value="{{ $penandatangan->id }}" selected>
                                                            {{ $penandatangan->nama }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $penandatangan->id }}">
                                                            {{ $penandatangan->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('penandatangan_id')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tahun Kegiatan</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tahun" name="tahun_kegiatan"
                                                value="{{ old('tahun_kegiatan') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('tahun_kegiatan')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Tanggal Mulai</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="tanggal_mulai_kegiatan"
                                                        name="tanggal_mulai_kegiatan"
                                                        value="{{ old('tanggal_mulai_kegiatan') }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('tanggal_mulai_kegiatan')
                                                    <strong class="text-danger"
                                                        style="font-size: 10px;">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Tanggal Berakhir</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="tanggal_akhir_kegiatan"
                                                        name="tanggal_akhir_kegiatan"
                                                        value="{{ old('tanggal_akhir_kegiatan') }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('tanggal_akhir_kegiatan')
                                                    <strong class="text-danger"
                                                        style="font-size: 10px;">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold">Total Jam</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" name="total_jam_kegiatan"
                                                value="{{ old('total_jam_kegiatan') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">Jam</span>
                                            </div>
                                        </div>
                                        @error('total_jam_kegiatan')
                                            <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-action float-right mb-3">
                                <button type="submit" class="btn btn-primary btn-rounded btn-login">
                                    <i class="fas fa-plus"></i>
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $('#tahun').datetimepicker({
                format: 'YYYY',
            });

            // Select2
            $('#basic').select2({
                theme: "bootstrap"
            });

            $('#basic2').select2({
                theme: "bootstrap"
            });

            // tgl awal
            $('#tanggal_mulai_kegiatan').datetimepicker({
                format: 'DD-MM-YYYY',
            });

            $('#tanggal_akhir_kegiatan').datetimepicker({
                format: 'DD-MM-YYYY',
            });
        </script>
    @endpush
@endsection
