@extends('dashboard.layouts.app')
@section('title', 'Digital Signature')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">
                <i class="fas fa-file-signature"></i>
                Digital Signature
            </h4>
        </div>

        {{-- Notify --}}
        <div id="success" data-flash="{{ session('success') }}"></div>
        <div id="fails" data-flash="{{ session('fails') }}"></div>
        {{-- ====== --}}

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
                                        <th>Nama Kegiatan</th>
                                        <th>Periode</th>
                                        <th>Kategori</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th width="18%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($kegiatans as $kegiatan)
                                        <tr>
                                            <td class="text-center">
                                                {{ $no++ }}
                                            </td>
                                            <td>{{ $kegiatan->judul_kegiatan }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMM') . ' s.d. ' . \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMM Y') }}
                                            </td>
                                            <td class="text-center text-uppercase">{{ $kegiatan->kategori->title }}</td>
                                            <td class="text-center">{{ $kegiatan->tahun_kegiatan }}</td>
                                            <td class="text-center text-capitalize fw-bold">
                                                <span
                                                    class="badge {{ $kegiatan->status == 'signed' ? 'badge-success' : 'badge-warning' }}">
                                                    {{ $kegiatan->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('digital.signature.detail', $kegiatan->id) }}"
                                                    class="btn btn-link btn-lg btn btn-secondary">
                                                    <i class="fas fa-file-signature"></i>
                                                </a>
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
