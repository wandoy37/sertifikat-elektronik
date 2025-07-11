@extends('dashboard.layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Pengguna</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-user"></i>
                    </a>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById('success-alert').style.display = 'none';
                        }, 6000); // Hilang setelah 3 detik
                    </script>
                </div>
            </div>
        @endif

        <div class="row">
            @if (Auth::user()->role == 'admin')
                <div class="col-lg-12">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-primary mb-4">
                        <i class="fas fa-undo"></i>
                        Kembali
                    </a>
                </div>
            @endif
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fw-bold">PENGGUNA</h4>
                    </div>
                    <form action="{{ route('user.update', $user->username) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input id="username" type="text"
                                    class="form-control @error('username') has-error @enderror" name="username"
                                    placeholder="username" value="{{ old('username', $user->username) }}" readonly>
                                @error('username')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>email</label>
                                <input id="email" type="email" class="form-control" name="email" placeholder="email"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input id="password" type="password" class="form-control" name="password"
                                    placeholder="password">
                                @error('password')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>password Confirmation</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control" placeholder="Password Confirmation">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-action float-right mb-3">
                                <button type="submit" class="btn btn-primary btn-rounded btn-login">
                                    <i class="fas fa-sync"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                @if ($penandatangan = $penandatangans->where('user_id', Auth::user()->id)->first())
                    <div class="card">
                        <div class="card-header">
                            <h4 class="fw-bold">BIODATA</h4>
                        </div>
                        <form action="{{ route('penandatangan.update', $penandatangan->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="text" name="user_id" value="{{ $penandatangan->user_id }}" hidden>
                                    <label>Nama</label>
                                    <input id="nama" type="text"
                                        class="form-control @error('nama') has-error @enderror" name="nama"
                                        placeholder="Nama" value="{{ old('nama', $penandatangan->nama) }}">
                                    @error('nama')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input id="nip" type="text" class="form-control" name="nip"
                                        placeholder="NIP" value="{{ old('nip', $penandatangan->nip) }}">
                                    @error('nip')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Pangkat/Golongan</label>
                                    <input id="pangkat_golongan" type="text" class="form-control" name="pangkat_golongan"
                                        placeholder="Pangkat/Golongan"
                                        value="{{ old('nip', $penandatangan->pangkat_golongan) }}">
                                    @error('pangkat_golongan', $penandatangan->pangkat_golongan)
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>jabatan</label>
                                    <input id="jabatan" type="type" name="jabatan" class="form-control"
                                        placeholder="jabatan" value="{{ old('jabatan', $penandatangan->jabatan) }}">
                                </div>
                                <div class="form-group">
                                    <label>Passphrase</label>
                                    <input id="passphrase" type="password" class="form-control" name="passphrase"
                                        placeholder="passphrase">
                                    @error('passphrase')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Passphrase Confirmation</label>
                                    <input id="passphrase_confirmation" type="password" name="passphrase_confirmation"
                                        class="form-control" placeholder="passphrase Confirmation">
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="form-action float-right mb-3">
                                    <button type="submit" class="btn btn-primary btn-rounded btn-login">
                                        <i class="fas fa-sync"></i>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>


        {{-- <div class="col-lg-6">
                    <div class="card text-center">
                        <div class="card-header">
                            <i>Current Signature</i>
                        </div>
                        <div class="card-body">
                            <img src="{{ asset('uploads/tanda_tangan_stempel/' . $penandatangan->tanda_tangan_stempel) }}"
                                class="img-fluid" width="50%" alt="">
                        </div>
                    </div>
                </div> --}}



    </div>

    @push('scripts')
        <script>
            $('#datepicker').datetimepicker({
                format: 'MM/DD/YYYY',
            });
        </script>
    @endpush
@endsection
