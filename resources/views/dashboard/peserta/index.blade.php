@extends('dashboard.layouts.app')
@section('title', 'Peserta')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Peserta</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-users"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>jenis_kelamin</th>
                                        <th>No. Identitas</th>
                                        <th>Pangkat/Golongan</th>
                                        <th>Jabatan</th>
                                        <th>Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pesertas as $peserta)
                                        <tr>
                                            <td>{{ $peserta['peserta_nama'] }}</td>
                                            <td>{{ $peserta['peserta_jenis_kelamin'] ?? '-' }}</td>
                                            <td>
                                                <span>NIK : {{ $peserta['peserta_nik'] ?? '-' }}</span>
                                                <br>
                                                <span>NIP : {{ $peserta['peserta_nip'] ?? '-' }}</span>
                                            </td>
                                            <td>{{ $peserta['peserta_pangkat_golongan'] }}</td>
                                            <td>{{ $peserta['peserta_jabatan'] }}</td>
                                            <td>{{ $peserta['peserta_instansi'] }}</td>
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
