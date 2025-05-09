<div class="card">
    <div class="card-header">
        <h4 class="fw-bold">Tambah Partisipan</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('sertifikat.store') }}" method="post">
            @csrf
            <input type="text" name="kegiatan_id" value="{{ $kegiatan->id }}" hidden>
            <div class="form-group">
                <div class="select2-input">
                    @if ($kegiatan->kategori->title == 'pelatihan')
                        <select id="basic" name="peserta_id" class="form-control">
                            <option value="">-pilih peserta-</option>
                            @foreach ($dataPesertas as $peserta)
                                @if (old('peserta_id') == $peserta['peserta_id'])
                                    <option value="{{ $peserta['peserta_id'] }}" selected>
                                        {{ $peserta['peserta_nama'] }}
                                    </option>
                                @else
                                    <option value="{{ $peserta['peserta_id'] }}">
                                        {{ $peserta['peserta_nama'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    @endif

                    @if ($kegiatan->kategori->title == 'bimtek')
                        <select id="basic" name="orang_id" class="form-control">
                            <option value="">-pilih peserta-</option>
                            @foreach ($dataPesertas as $orang)
                                @if (old('orang_id') == $orang->id)
                                    <option value="{{ $orang->id }}" selected>
                                        {{ $orang->nama }}
                                    </option>
                                @else
                                    <option value="{{ $orang->id }}">
                                        {{ $orang->nama }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    @endif

                    @if ($kegiatan->kategori->title == 'pkl')
                        <select id="basic" name="siswa_id" class="form-control">
                            <option value="">-pilih peserta-</option>
                            @foreach ($dataPesertas as $peserta)
                                @if (old('siswa_id') == $peserta->id)
                                    <option value="{{ $peserta->id }}" selected>
                                        {{ $peserta->nama }}
                                    </option>
                                @else
                                    <option value="{{ $peserta->id }}">
                                        {{ $peserta->nama }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    @endif
                </div>
                @error('peserta_id')
                    <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                @enderror

                @error('orang_id')
                    <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                @enderror

                @error('siswa_id')
                    <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-plus"></i>
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>
