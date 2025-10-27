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
                <li>
                    <a href="{{ route('dashboard') }}" class="tp-link">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @if (Auth::user()->is_admin)
                    <li>
                        <a href="#gejalaMenu" data-bs-toggle="collapse">
                            <i data-feather="list"></i>
                            <span> Gejala </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="gejalaMenu">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('gejala.index') }}" class="tp-link">List Gejala</a>
                                </li>
                                <li>
                                    <a href="{{ route('gejala.create') }}" class="tp-link">Tambah Gejala</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="#penyakitMenu" data-bs-toggle="collapse">
                            <i data-feather="book-open"></i>
                            <span>Penyakit</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="penyakitMenu">
                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('penyakit.index') }}" class="tp-link">List Penyakit</a>
                                </li>
                                <li>
                                    <a href="{{ route('penyakit.create') }}" class="tp-link">Tambah Penyakit</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('penyakit-gejala.index') }}" class="tp-link">
                            <i data-feather="home"></i>
                            <span> Bobot Gejala</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="#diagnosisMenu" data-bs-toggle="collapse">
                        <i data-feather="command"></i>
                        <span> Diagnosis </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="diagnosisMenu">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('diagnosa.form') }}" class="tp-link">Diagnosa Baru</a>
                            </li>
                            <li>
                                <a href="{{ route('diagnosa.riwayat') }}" class="tp-link">Riwayat Diagnosa</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
