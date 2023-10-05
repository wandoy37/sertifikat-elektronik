@extends('dashboard.layouts.app')
@section('title', 'Kegiatan')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Kegiatan</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-podcast"></i>
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
                <a href="{{ route('kegiatan.create') }}" class="btn btn-primary mb-4">
                    <i class="fas fa-plus"></i>
                    Kegiatan
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>Kode</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>Peserta</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatans as $kegiatan)
                                        <tr>
                                            <td class="text-center">{{ $kegiatan->kode_kegiatan }}</td>
                                            <td>{{ $kegiatan->judul_kegiatan }}</td>
                                            <td class="text-center">{{ $kegiatan->kategori->title }}</td>
                                            <td class="text-center">{{ $kegiatan->tahun_kegiatan }}</td>
                                            <td class="text-center">
                                                @if ($kegiatan->status == 'open')
                                                    <span class="text-success">{{ $kegiatan->status }}</span>
                                                @else
                                                    <span class="text-danger">{{ $kegiatan->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $kegiatan->sertifikats->count() }}</td>
                                            <td class="form-inline d-flex justify-content-center">
                                                <a href="{{ route('sertifikat.create.peserta', $kegiatan->id) }}"
                                                    class="btn btn-secondary btn-sm mr-4">
                                                    Peserta
                                                </a>
                                                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="text-primary">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form id="form-delete-{{ $kegiatan->id }}"
                                                    action="{{ route('kegiatan.delete', $kegiatan->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $kegiatan->id }} )">
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
