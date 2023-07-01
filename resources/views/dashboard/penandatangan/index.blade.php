@extends('dashboard.layouts.app')
@section('title', 'Penandatangan')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Penandatangan</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-file-signature"></i>
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
                <a href="{{ route('penandatangan.create') }}" class="btn btn-primary mb-4">
                    <i class="fas fa-plus"></i>
                    Penandatangan
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
                                        <th>Nip</th>
                                        <th>Pangkat/Golongan</th>
                                        <th>Jabatan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penandatangans as $penandatangan)
                                        <tr>
                                            <td>{{ $penandatangan->nama }}</td>
                                            <td>{{ $penandatangan->nip }}</td>
                                            <td>{{ $penandatangan->pangkat_golongan }}</td>
                                            <td>{{ $penandatangan->jabatan }}</td>
                                            <td class="form-inline">
                                                <a href="{{ route('penandatangan.edit', $penandatangan->id) }}"
                                                    class="text-primary">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form id="form-delete-{{ $penandatangan->id }}"
                                                    action="{{ route('penandatangan.delete', $penandatangan->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $penandatangan->id }} )">
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
