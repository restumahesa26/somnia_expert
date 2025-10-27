<!-- Topbar Start -->
<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <h5 class="mb-0">{{ \App\Helpers\Helper::greeting() }}, {{ Auth::user()->nama }}</h5>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li class="d-none d-lg-block">
                    <form class="app-search d-none d-md-block me-auto">
                        <div class="position-relative topbar-search">
                            <input type="text" class="form-control ps-4" placeholder="Search..." />
                            <i
                                class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>
                        </div>
                    </form>
                </li>

                <!-- Button Trigger Customizer Offcanvas -->
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                <!-- Light/Dark Mode Button Themes -->
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" id="light-dark-mode">
                        <i data-feather="moon" class="align-middle dark-mode"></i>
                        <i data-feather="sun" class="align-middle light-mode"></i>
                    </button>
                </li>

                <!-- User Dropdown -->
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ url('dist/assets/images/users/user-13.jpg') }}" alt="user-image" class="rounded-circle" />
                        <span class="pro-user-name ms-1">{{ \App\Helpers\Helper::getFirstName(Auth::user()->nama) }} <i class="mdi mdi-chevron-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="pages-profile.html" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                            <span>Akun Saya</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="#" class="dropdown-item notify-item" onclick="event.preventDefault(); confirmLogout();">
                                <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                <span>Logout</span>
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin keluar dari sistem?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush
<!-- end Topbar -->
