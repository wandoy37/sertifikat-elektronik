@extends('dashboard.layouts.app')
@section('title', 'Sertifikat')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Sertifikat</h4>
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

        @if (Auth::user()->role == 'admin')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Kegiatan</th>
                                            <th>Peserta</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($sertifikats as $sertifikat)
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $sertifikat->judul_kegiatan }}</td>
                                                <td>{{ $sertifikat->nama_peserta }}</td>
                                                <td class="form-inline d-flex justify-content-center">
                                                    <div class="btn-group" role="group"
                                                        aria-label="Basic outlined example">
                                                        @if ($sertifikat->status == 'belum terbit')
                                                            <a href="{{ route('sertifikat.peserta.terbitkan', $sertifikat->id) }}"
                                                                target="_blank"
                                                                class="btn btn-outline-primary btn-sm float-right">
                                                                <i class="fas fa-certificate"></i>
                                                                Terbitkan
                                                            </a>
                                                        @else
                                                            <a href="{{ route('home.show', $sertifikat->id) }}"
                                                                class="fw-bold text-primary mr-3 text-capitalize"
                                                                target="_blank">
                                                                <i class="fas fa-print"></i>
                                                                {{ $sertifikat->status }}
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('sertifikat.peserta.generate', $sertifikat->id) }}"
                                                            class="btn btn-info btn-sm" target="_blank">
                                                            <i class="fas fa-certificate"></i>
                                                            Cetak
                                                        </a>
                                                        <form id="form-delete-{{ $sertifikat->id }}"
                                                            action="{{ route('sertifikat.delete', $sertifikat->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="btnDelete( {{ $sertifikat->id }} )">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
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
        @else
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        @foreach ($sertifikats as $sertifikat)
                            <div class="col-lg-4">
                                <div class="card card-info">
                                    <div class="card-body skew-shadow">
                                        <h1 class="text-uppercase">
                                            <i class="fas fa-certificate"></i>
                                            <i>Certificate</i>
                                        </h1>
                                        <h5 class="op-1">{{ $sertifikat->judul_kegiatan }}</h5>
                                        <div class="mt-4">
                                            <a href="{{ route('home.sertifikat.download', $sertifikat->id) }}"
                                                class="btn btn-light btn-rounded">
                                                <i class="fas fa-print"></i>
                                                Cetak
                                            </a>
                                            <a href="{{ route('home.sertifikat.preview', $sertifikat->id) }}"
                                                class="text-link text-white ml-4" target="_blank">
                                                <i class="fas fa-solid fa-eye"></i>
                                                Preview
                                            </a>
                                        </div>
                                        <div class="pull-right">
                                            <small>
                                                <span class="fw-bold">
                                                    Terbit pada
                                                </span>
                                                <i>{{ \Carbon\Carbon::parse($sertifikat->tanggal_terbit)->format('d F Y') }}</i>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif



    </div>

    @push('scripts')
        <script>
            $('#basic-datatables').DataTable();

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
