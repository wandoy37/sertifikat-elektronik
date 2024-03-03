<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <div class="avatar"><img src="{{ asset('assets/img/profile.jpg') }}" alt="image profile"
                            class="avatar-img rounded-circle"></div>
                    {{-- @if (Auth::user()->peserta && Auth::user()->peserta->foto !== null)
                        <div class="avatar"><img src="{{ asset('foto_peserta/' . Auth::user()->peserta->foto) }}"
                                alt="image profile" class="avatar-img rounded-circle"></div>
                    @else
                        <div class="avatar"><img src="{{ asset('assets/img/profile.jpg') }}" alt="image profile"
                                class="avatar-img rounded-circle"></div>
                    @endif --}}
                </div>
                <div class="info">
                    <a href="{{ route('dashboard.index') }}">
                        <span class="">
                            {{ Auth::user()->username }}
                        </span>
                        <small class="text-dark">
                            <i class="fas fa-circle text-success"></i>
                            Online
                        </small>
                    </a>
                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">MENU NAVIGATION</h4>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if ((Auth::user()->role == 'admin') | (Auth::user()->role == 'oprator'))
                    {{-- <li class="nav-item {{ request()->segment(2) == 'pengguna' ? 'active' : '' }}">
                        <a href="{{ route('user.index') }}">
                            <i class="fas fa-user"></i>
                            <p>Pengguna</p>
                        </a>
                    </li> --}}
                    <li class="nav-item {{ request()->segment(2) == 'kegiatan' ? 'active' : '' }}">
                        <a href="{{ route('kegiatan.index') }}">
                            <i class="fas fa-podcast"></i>
                            <p>Kegiatan</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->segment(2) == 'kategori' ? 'active' : '' }}">
                        <a href="{{ route('kategori.index') }}">
                            <i class="fas fa-tag"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item {{ request()->segment(2) == 'sertifikat' ? 'active' : '' }}">
                    <a href="{{ route('sertifikat.index') }}">
                        <i class="fas fa-certificate"></i>
                        <p>Sertifikat</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">DATA MASTER</h4>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'peserta' ? 'active' : '' }}">
                    <a href="{{ route('peserta.index') }}">
                        <i class="fas fa-users"></i>
                        <p>Peserta</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'orang' ? 'active' : '' }}">
                    <a href="{{ route('orang.index') }}">
                        <i class="fas fa-user"></i>
                        <p>Orang</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'siswa' ? 'active' : '' }}">
                    <a href="{{ route('siswa.index') }}">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Siswa</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'narasumber' ? 'active' : '' }}">
                    <a href="{{ route('narasumber.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>Narasumber</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(2) == 'penandatangan' ? 'active' : '' }}">
                    <a href="{{ route('penandatangan.index') }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Penandatangan</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
