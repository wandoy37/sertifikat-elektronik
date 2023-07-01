<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="{{ asset('assets/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle">
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
                <li class="nav-item {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">MENU MASTER</h4>
                </li>
                <li class="nav-item {{ request()->segment(1) == 'pengguna' ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}">
                        <i class="fas fa-user"></i>
                        <p>Pengguna</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(1) == 'peserta' ? 'active' : '' }}">
                    <a href="{{ route('peserta.index') }}">
                        <i class="fas fa-users"></i>
                        <p>Peserta</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->segment(1) == 'penandatangan' ? 'active' : '' }}">
                    <a href="{{ route('penandatangan.index') }}">
                        <i class="fas fa-file-signature"></i>
                        <p>Penandatangan</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
