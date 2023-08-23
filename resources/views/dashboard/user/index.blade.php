@extends('dashboard.layouts.app')
@section('title', 'Pengguna')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengguna</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-user"></i>
                    </a>
                </li>
                {{-- <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}">Dashboard</a>
                </li> --}}
            </ul>
        </div>

        {{-- Notify --}}
        <div id="success" data-flash="{{ session('success') }}"></div>
        <div id="fails" data-flash="{{ session('fails') }}"></div>
        {{-- ====== --}}

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('user.create') }}" class="btn btn-primary mb-4">
                    <i class="fas fa-plus"></i>
                    Pengguna
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
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>
                                                @if ($user->peserta == null)
                                                    @if ($user->role == 'peserta')
                                                        <a href="{{ route('peserta.create', $user->username) }}"
                                                            class="btn btn-outline-primary btn-sm">
                                                            Lengkapi Profil
                                                        </a>
                                                    @else
                                                        <span class="text-center">admin</span>
                                                    @endif
                                                @else
                                                    {{ $user->peserta->nama ?? '-' }}
                                                @endif
                                            </td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td class="form-inline">
                                                @if (Auth::user()->role == 'admin')
                                                    <a href="{{ route('user.edit', $user->username) }}"
                                                        class="text-primary">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    @if ($user->role == 'peserta')
                                                        <form id="form-delete-{{ $user->id }}"
                                                            action="{{ route('user.delete', $user->username) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <button type="button" class="btn btn-link text-danger"
                                                            onclick="btnDelete( {{ $user->id }} )">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span>aksi peserta</span>
                                                @endif
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
