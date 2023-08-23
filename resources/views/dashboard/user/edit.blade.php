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
                    <form action="{{ route('user.update', $user->username) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input id="username" type="text"
                                    class="form-control @error('username') has-error @enderror" name="username"
                                    placeholder="username" value="{{ old('username', $user->username) }}">
                                @error('username')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            {{-- <div class="form-group">
                                <label>email</label>
                                <input id="email" type="email" class="form-control" name="email" placeholder="email"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div> --}}
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
