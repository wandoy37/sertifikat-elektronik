@extends('dashboard.layouts.app')
@section('title', 'Edit Kegiatan')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-chalkboard-teacher"></i>
                Edit Kegiatan
            </h4>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                <label class="fw-bold">Kode Kegiatan</label>
                                <input type="text" class="form-control" name="kode_kegiatan" value="000.9.4" readonly>
                            </div>
                            <div class="form-group @error('nama_kegiatan') has-error @enderror">
                                <label class="fw-bold">Nama Kegiatan</label>
                                <input type="text" class="form-control" name="nama_kegiatan"
                                    value="{{ old('nama_kegiatan', $kegiatan->judul_kegiatan) }}">
                            </div>
                            <div class="row @error('tanggal_mulai_kegiatan', 'tanggal_akhir_kegiatan') has-error @enderror">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <i>Tanggal Mulai</i>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tanggal_mulai_kegiatan"
                                                name="tanggal_mulai_kegiatan"
                                                value="{{ old('tanggal_mulai_kegiatan', $kegiatan->tanggal_mulai_kegiatan) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <i>Tanggal Berakhir</i>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tanggal_akhir_kegiatan"
                                                name="tanggal_akhir_kegiatan"
                                                value="{{ old('tanggal_akhir_kegiatan', $kegiatan->tanggal_akhir_kegiatan) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group @error('kategori_id') has-error @enderror">
                                <label class="fw-bold">Kategori Kegiatan</label>
                                <div class="select2-input">
                                    <select id="basic" name="kategori_id" class="form-control">
                                        @php
                                            $selectedKategori = old(
                                                'kategori_id',
                                                isset($kegiatan) ? $kegiatan->kategori_id : null,
                                            );
                                        @endphp
                                        <option value="">-pilih kategori-</option>
                                        @foreach ($kategories as $kategori)
                                            <option value="{{ $kategori->id }}"
                                                {{ $selectedKategori == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group @error('tahun_kegiatan') has-error @enderror">
                                <label>Tahun Kegiatan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tahun" name="tahun_kegiatan"
                                        value="{{ old('tahun_kegiatan', $kegiatan->tahun_kegiatan) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group @error('penyelenggara_kegiatan') has-error @enderror">
                                <label class="fw-bold">Penyelenggara Kegiatan</label>
                                <textarea name="penyelenggara_kegiatan" class="form-control" cols="30" rows="4">{{ $kegiatan->penyelenggara_kegiatan }}</textarea>
                            </div>
                            <div class="form-group @error('lokasi_kegiatan') has-error @enderror">
                                <label class="fw-bold">Lokasi Kegiatan</label>
                                <textarea name="lokasi_kegiatan" class="form-control" cols="30" rows="4">{{ $kegiatan->lokasi_kegiatan }}</textarea>
                            </div>
                            <div class="form-group @error('total_jam_kegiatan') has-error @enderror">
                                <label>Total Jam</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="total_jam_kegiatan"
                                        value="{{ old('total_jam_kegiatan', $kegiatan->total_jam_kegiatan) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group @error('penandatangan_id') has-error @enderror">
                                <label class="fw-bold">Penandatangan</label>
                                <div class="select2-input">
                                    @php
                                        $selectedPenandatangan = old(
                                            'penandatangan_id',
                                            $kegiatan->penandatangan_id ?? null,
                                        );
                                    @endphp
                                    <select id="basic2" name="penandatangan_id" class="form-control">
                                        <option value="">-pilih penandatangan-</option>
                                        @foreach ($penandatangans as $penandatangan)
                                            <option value="{{ $penandatangan->id }}"
                                                {{ $selectedPenandatangan == $penandatangan->id ? 'selected' : '' }}>
                                                {{ $penandatangan->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group @error('tanggal_penandatanganan') has-error @enderror">
                                <label>Tanggal Penandatangan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tanggal_penandatanganan"
                                        name="tanggal_penandatanganan"
                                        value="{{ old('tanggal_penandatanganan', $kegiatan->tanggal_penandatanganan) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group @error('tanggal_penandatanganan') has-error @enderror">
                                <label>Daftar Mata Pelatihan</label>
                                <input id="daftar_mata_pelatihan" type="file" class="form-control"
                                    name="daftar_mata_pelatihan" placeholder="daftar_mata_pelatihan">
                                @if (!empty($kegiatan->daftar_mata_pelatihan))
                                    <p>File lama: <a href="{{ asset($kegiatan->daftar_mata_pelatihan) }}"
                                            target="_blank">Lihat File</a></p>
                                @endif
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

            $('#tanggal_penandatanganan').datetimepicker({
                format: 'DD-MM-YYYY',
            });
        </script>
    @endpush
@endsection
