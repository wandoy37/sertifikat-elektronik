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
                                    <select id="basic" name="peserta_id" class="form-control">
                                        <option value="">-pilih peserta-</option>
                                        @foreach ($pesertas as $peserta)
                                            @if (old('peserta_id') == $peserta->id)
                                                <option value="{{ $peserta->id }}" selected>{{ $peserta->nama }}
                                                </option>
                                            @else
                                                <option value="{{ $peserta->id }}">{{ $peserta->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
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

            {{-- List Peserta --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Daftar Peserta</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($sertifikats as $sertifikat)
                                        <tr>
                                            <td class="text-center" width="25px;">{{ $counter++ }}</td>
                                            <td>{{ $sertifikat->nama_peserta }}</td>
                                            <td class="form-inline d-flex justify-content-center">
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
