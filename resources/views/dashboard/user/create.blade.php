@extends('dashboard.layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Pengguna</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-user"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('user.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Buat Pengguna Baru</h3>
                    </div>
                    <form action="{{ route('user.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input id="username" type="text"
                                    class="form-control @error('username') has-error @enderror" name="username"
                                    placeholder="username" value="{{ old('username') }}">
                                @error('username')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input id="email" type="text"
                                    class="form-control @error('email') has-error @enderror" name="email"
                                    placeholder="email" value="{{ old('email') }}">
                                @error('email')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            {{-- <div class="form-group">
                                <label>email</label>
                                <input id="email" type="text" class="form-control" name="email" placeholder="email"
                                    value="{{ old('email') }}">
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
                            </div> --}}
                        </div>
                        <div class="card-footer">
                            <div class="form-action float-right mb-3">
                                <button type="submit" class="btn btn-primary btn-rounded btn-login">
                                    <i class="fas fa-plus"></i>
                                    Daftar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $('#datepicker').datetimepicker({
                format: 'MM/DD/YYYY',
            });
        </script>
    @endpush
@endsection
