@extends('dashboard.layouts.app')
@section('title', 'Tambah Peserta')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Peserta</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-certificate"></i>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Notify --}}
        <div id="success" data-flash="{{ session('success') }}"></div>
        <div id="fails" data-flash="{{ session('fails') }}"></div>
        {{-- ====== --}}

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>

            {{-- Section Tambah Peserta --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Tambah Peserta</h3>
                    </div>
                    <form action="{{ route('sertifikat.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text fw-bold">Kegiatan</span>
                                    </div>
                                    <input type="text" class="form-control" name="judul_kegiatan"
                                        value="{{ old('judul_kegiatan', $kegiatan->judul_kegiatan) }}">
                                    <input type="text" name="kegiatan_id" value="{{ $kegiatan->id }}" hidden>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="fw-bold">Peserta</label>
                                <div class="select2-input">
                                    @if ($kegiatan->kategori->title == 'pkl')
                                        <select id="basic" name="siswa_id" class="form-control">
                                            <option value="">-pilih peserta-</option>
                                            @foreach ($dataPeserta as $peserta)
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
                                    @else
                                        <select id="basic" name="peserta_id" class="form-control">
                                            <option value="">-pilih peserta-</option>
                                            @foreach ($dataPeserta as $peserta)
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
                                </div>
                                @error('peserta_id')
                                    <strong class="text-danger" style="font-size: 10px;">{{ $message }}</strong>
                                @enderror
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
            {{-- End Section Tambah Peserta --}}

            {{-- Section Narasumber --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center bd-highlight">
                        <h4>Narasumber</h4>
                    </div>
                    <div class="card-body">
                        {{-- Form Select2 Narasumber --}}
                        <form action="{{ route('sertifikat.narasumber.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="kegiatan_id" value="{{ $kegiatan->id }}" hidden>
                                <div class="select2-input">
                                    <select id="selectNarasumber" name="narasumber_id" class="form-control">
                                        <option value="">--Pilih Narasumber--</option>
                                        @foreach ($narasumbers as $narasumber)
                                            <option value="{{ $narasumber->id }}">{{ $narasumber->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-round float-right">
                                    <i class="fas fa=plus"></i>
                                    Narasumber
                                </button>
                            </div>
                        </form>
                        {{-- End Form Select2 Narasumber --}}
                        <div class="table-responsive">
                            <table class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nomor Sertifikat</th>
                                        <th>Nama</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    php
                                    @foreach ($sertifikats as $sertifikat)
                                        @if ($sertifikat->narasumber_id !== '-')
                                            <tr class="text-center">
                                                <td>{{ $sertifikat->nomor_sertifikat }}</td>
                                                <td>
                                                    @php
                                                        $narasumber = DB::table('narasumbers')
                                                            ->where('id', '=', $sertifikat->narasumber_id)
                                                            ->first();
                                                    @endphp
                                                    {{ $narasumber->nama }}
                                                </td>
                                                <td class="form-inline d-flex justify-content-center">
                                                    <a href="#" class="fw-bold text-primary mr-3 text-capitalize">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <form id="form-delete-{{ $sertifikat->id }}"
                                                        action="{{ route('sertifikat.peserta.delete', $sertifikat->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button" class="btn btn-link text-danger"
                                                        onclick="btnDelete( {{ $sertifikat->id }} )">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Section Narasumber --}}

            {{-- List Peserta --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <h4>Daftar Peserta</h4>
                        </div>
                        @if ($kegiatan->kategori->title !== 'pkl')
                            <div class="p-2 bd-highlight">
                                <a href="{{ route('sertifikat.all.parts.generate', $kegiatan->id) }}"
                                    class="btn btn-warning btn-sm ml-2" target="_blank">
                                    <i class="fas fa-print"></i>
                                    Cetak Sertifikat (parts)
                                </a>
                            </div>
                        @endif
                        <div class="p-2 bd-highlight">
                            <a href="{{ route('sertifikat.all.generate', $kegiatan->id) }}"
                                class="btn btn-info btn-sm ml-2" target="_blank">
                                <i class="fas fa-print"></i>
                                Cetak All Sertifikat
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nomor Sertifikat</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($sertifikats as $sertifikat)
                                        <tr class="text-center">
                                            <td class="text-center" width="25px;">{{ $counter++ }}</td>
                                            <td>{{ $sertifikat->nomor_sertifikat }}</td>
                                            <td>
                                                @if ($kegiatan->kategori->title == 'pkl')
                                                    @php
                                                        $siswa = DB::table('siswas')
                                                            ->where('id', '=', $sertifikat->siswa_id)
                                                            ->first();
                                                    @endphp
                                                    {{ $siswa->nama }}
                                                @else
                                                    @php
                                                        $peserta_id = $sertifikat->peserta_id;
                                                        $url = "http://simpeltan.test/api/data-peserta/{$sertifikat->peserta_id}";
                                                        $response = file_get_contents($url);
                                                        $data = json_decode($response, true);
                                                    @endphp
                                                    @if ($sertifikat->peserta_id !== '-')
                                                        {{ $data[0]['peserta_nama'] }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="form-inline d-flex justify-content-center">
                                                @if ($sertifikat->status == 'belum terbit')
                                                    <a href="{{ route('sertifikat.peserta.terbitkan', $sertifikat->id) }}"
                                                        target=”_blank” class="btn btn-outline-primary btn-sm float-right">
                                                        <i class="fas fa-certificate"></i>
                                                        Terbitkan
                                                    </a>
                                                @else
                                                    <a href="{{ route('home.show', $sertifikat->verified_code) }}"
                                                        class="fw-bold text-primary mr-3 text-capitalize" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                        {{ $sertifikat->status }}
                                                    </a>
                                                @endif
                                                <form id="form-delete-{{ $sertifikat->id }}"
                                                    action="{{ route('sertifikat.peserta.delete', $sertifikat->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $sertifikat->id }} )">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#basic-datatables').DataTable();
            $('#datepicker').datetimepicker({
                format: 'MM/DD/YYYY',
            });

            $('#basic').select2({
                theme: "bootstrap"
            });

            $('#selectNarasumber').select2({
                theme: "bootstrap"
            });

            // Notify
            var flash = $('#success').data('flash');
            if (flash) {
                $.notify({
                    // options
                    icon: 'fas fa-check',
                    title: 'Berhasil',
                    message: '{{ session('success') }}',
                }, {
                    // settings
                    type: 'success',
                });
            }
            var flash = $('#fails').data('flash');
            if (flash) {
                $.notify({
                    // options
                    icon: 'fas fa-ban',
                    title: 'Gagal',
                    message: '{{ session('fails') }}',
                }, {
                    // settings
                    type: 'danger',
                });
            }

            function btnDelete(id) {
                swal({
                    title: 'Apa anda yakin?',
                    text: "Data tidak dapat di kembalikan setelah ini !!!",
                    type: 'warning',
                    buttons: {
                        confirm: {
                            text: 'Ya, hapus sekarang',
                            className: 'btn btn-success'
                        },
                        cancel: {
                            visible: true,
                            className: 'btn btn-danger'
                        }
                    }
                }).then((Delete) => {
                    if (Delete) {
                        $('#form-delete-' + id).submit();
                    } else {
                        swal.close();
                    }
                });
            }
        </script>
    @endpush
@endsection
