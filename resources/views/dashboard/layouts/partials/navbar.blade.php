<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
            <li class="nav-item dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        @if (Auth::user()->peserta && Auth::user()->peserta->foto !== null)
                            <div class="avatar"><img src="{{ asset('foto_peserta/' . Auth::user()->peserta->foto) }}"
                                    alt="image profile" class="avatar-img rounded-circle"></div>
                        @else
                            <div class="avatar"><img src="{{ asset('assets/img/profile.jpg') }}" alt="image profile"
                                    class="avatar-img rounded-circle"></div>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                @if (Auth::user()->peserta && Auth::user()->peserta->foto !== null)
                                    <div class="avatar"><img
                                            src="{{ asset('foto_peserta/' . Auth::user()->peserta->foto) }}"
                                            alt="image profile" class="avatar-img rounded-circle"></div>
                                @else
                                    <div class="avatar"><img src="{{ asset('assets/img/profile.jpg') }}"
                                            alt="image profile" class="avatar-img rounded-circle"></div>
                                @endif
                                <div class="u-text">
                                    <h4 class="text-capitalize">{{ Auth::user()->name }}</h4>
                                    <p class="text-muted">{{ Auth::user()->email }}</p><a href="profile.html"
                                        class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form action="{{ route('logout') }}" id="logout-form" method="POST">
                                @csrf
                            </form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
