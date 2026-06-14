<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary">
                KLINIK CERIA
            </a>
        </div>

        <div class="navbar-content">
            <ul class="pc-navbar">

                {{-- ================= ADMIN ================= --}}
                @auth
                @if (auth()->user()->role === 'admin')

                <li class="pc-item">
                    <a href="{{ route('pasien.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-users"></i></span>
                        <span class="pc-mtext">Data Pasien</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{ route('rekamMedis.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                        <span class="pc-mtext">Tangani Pasien</span>
                    </a>
                </li>

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-menu"></i></span>
                        <span class="pc-mtext">Kunjungan</span>
                        <span class="pc-arrow"></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('kunjungan.create') }}" class="pc-link">
                                Buat Kunjungan
                            </a>
                        </li>
                        <li class="pc-item">
                            <a href="{{ route('kunjungan.index') }}" class="pc-link">
                                Daftar Kunjungan
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="pc-item">
                    <a href="{{ route('dokter.jadwal') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user"></i></span>
                        <span class="pc-mtext">Jadwal Dokter</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{ route('dokter.index') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user"></i></span>
                        <span class="pc-mtext">Data Dokter</span>
                    </a>
                </li>


                @endif
                @endauth

                {{-- ================= DOKTER ================= --}}
                @auth
                @if (auth()->user()->role === 'dokter')

                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-stethoscope"></i></span>
                        <span class="pc-mtext">Menu</span>
                        <span class="pc-arrow"></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a href="{{ route('dokter.daftarAntrian') }}" class="pc-link">
                                Daftar Kunjungan
                            </a>
                        </li>
                    </ul>
                </li>

                @endif
                @endauth

                {{-- ================= ACCOUNT ================= --}}
                <li class="pc-item pc-caption">
                    <label>Account</label>
                    <i class="ti ti-news"></i>
                </li>

                {{-- LOGOUT --}}
                @auth
                <li class="pc-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="pc-link btn btn-link p-0 text-start w-100">
                            <span class="pc-micon"><i class="ti ti-lock"></i></span>
                            <span class="pc-mtext">Logout</span>
                        </button>
                    </form>
                </li>
                @else
                <li class="pc-item">
                    <a href="{{ route('login') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-lock"></i></span>
                        <span class="pc-mtext">Login</span>
                    </a>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>