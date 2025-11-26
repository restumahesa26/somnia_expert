<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ url('dist/assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ url('dist/assets/images/logo-light.png') }}" alt="" height="50">
                    </span>
                </a>
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ url('dist/assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ url('dist/assets/images/logo-dark.png') }}" alt="" height="50">
                    </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>
                <li class="{{ request()->segment(1) === 'dashboard' ? 'menuitem-active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="tp-link {{ request()->segment(1) === 'dashboard' ? 'active' : '' }}">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @if (Auth::user()->is_admin)
                    <li class="{{ request()->segment(1) === 'gejala' ? 'menuitem-active' : '' }}">
                        <a href="#gejalaMenu" data-bs-toggle="collapse">
                            <i data-feather="list"></i>
                            <span> Gejala </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->segment(1) === 'gejala' ? 'show' : '' }}" id="gejalaMenu">
                            <ul class="nav-second-level">
                                <li class="{{ request()->segment(1) === 'gejala' && request()->segment(3) === 'edit' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('gejala.index') }}" class="tp-link">List Gejala</a>
                                </li>
                                <li class="{{ request()->segment(1) === 'gejala' && request()->segment(2) === 'create' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('gejala.create') }}" class="tp-link">Tambah Gejala</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="{{ request()->segment(1) === 'penyakit' ? 'menuitem-active' : '' }}">
                        <a href="#penyakitMenu" data-bs-toggle="collapse">
                            <i data-feather="book-open"></i>
                            <span>Penyakit</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->segment(1) === 'penyakit' ? 'show' : '' }}" id="penyakitMenu">
                            <ul class="nav-second-level">
                                <li class="{{ request()->segment(1) === 'penyakit' && request()->segment(3) === 'edit' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('penyakit.index') }}" class="tp-link">List Penyakit</a>
                                </li>
                                <li class="{{ request()->segment(1) === 'penyakit' && request()->segment(2) === 'create' ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('penyakit.create') }}" class="tp-link">Tambah Penyakit</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="{{ request()->segment(1) === 'penyakit-gejala' ? 'menuitem-active' : '' }}">
                        <a href="{{ route('penyakit-gejala.index') }}" class="tp-link {{ request()->segment(1) === 'penyakit-gejala' ? 'active' : '' }}">
                            <i data-feather="home"></i>
                            <span> Bobot Gejala</span>
                        </a>
                    </li>
                @endif

                <li class="{{ request()->segment(1) === 'diagnosa' ? 'menuitem-active' : '' }}">
                    <a href="#diagnosisMenu" data-bs-toggle="collapse">
                        <i data-feather="command"></i>
                        <span> Screening </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->segment(1) === 'diagnosa' ? 'show' : '' }}" id="diagnosisMenu">
                        <ul class="nav-second-level">
                            <li class="{{ request()->segment(1) === 'konsultasi' ? 'menuitem-active' : '' }}">
                                <a href="{{ route('diagnosa.form') }}" class="tp-link">Screening Baru</a>
                            </li>
                            <li class="{{ request()->segment(1) === 'diagnosa' && request()->segment(2) === 'detail' ? 'menuitem-active' : '' }}">
                                <a href="{{ route('diagnosa.riwayat') }}" class="tp-link">Riwayat Screening</a>
                            </li>
                        </ul>
                    </div>
                </li>

                @if (Auth::user()->is_admin)
                    <li class="{{ request()->segment(1) === 'pengguna' ? 'menuitem-active' : '' }}">
                        <a href="{{ route('pengguna.index') }}" class="tp-link {{ request()->segment(1) === 'pengguna' ? 'active' : '' }}">
                            <i data-feather="users"></i>
                            <span> Data Pengguna</span>
                        </a>
                    </li>
                @endif

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
