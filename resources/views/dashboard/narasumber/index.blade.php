@extends('dashboard.layouts.app')
@section('title', 'Peserta')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Narasumber</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('narasumber.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('narasumber.create') }}" class="btn btn-primary mb-4">
                    <i class="fas fa-plus"></i>
                    Narasumber
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Tempat/Tgl Lahir</th>
                                        <th>Pangkat Golongan</th>
                                        <th>Jabatan</th>
                                        <th>Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($narasumbers as $narasumber)
                                        <tr>
                                            <td>{{ $narasumber->nama }}</td>
                                            <td>{{ $narasumber->nip }}</td>
                                            <td>{{ $narasumber->tempat_lahir . ', ' . $narasumber->tanggal_lahir }}</td>
                                            <td>{{ $narasumber->pangkat_golongan }}</td>
                                            <td>{{ $narasumber->jabatan }}</td>
                                            <td>{{ $narasumber->instansi }}</td>
                                            <td class="form-inline">
                                                <a href="{{ route('narasumber.edit', $narasumber->id) }}"
                                                    class="text-primary">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form id="form-delete-{{ $narasumber->id }}"
                                                    action="{{ route('narasumber.delete', $narasumber->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $narasumber->id }} )">
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
