<!-- START HEADER -->
<header class="app-header">
  <div class="container-fluid">
    <div class="nav-header">

      <div class="header-left hstack gap-3">
        <!-- HORIZONTAL BRAND LOGO -->
        <div class="app-sidebar-logo app-horizontal-logo justify-content-center align-items-center">
          <a href="index">
            <img height="27" class="app-sidebar-logo-default logo-light" alt="Logo" loading="lazy" src="{{ asset('assets/images/logo/logo-logotype.png') }}">
            <img height="27" class="app-sidebar-logo-default logo-dark" alt="Logo" loading="lazy" src="{{ asset('assets/images/logo/logo-logotype-white.png') }}">
            <img height="40" class="app-sidebar-logo-minimize" alt="Logo" loading="lazy" src="{{ asset('assets/images/logo/logo.png') }}">
          </a>
        </div>

        <!-- Sidebar Toggle Btn -->
        <button type="button" class="btn btn-light-light icon-btn sidebar-toggle d-none d-md-block" aria-expanded="false" aria-controls="main-menu">
          <span class="visually-hidden">Toggle sidebar</span>
          <i class="ri-menu-2-fill"></i>
        </button>

        <!-- Sidebar Toggle for Mobile -->
        <button class="btn btn-light-light icon-btn d-md-none small-screen-toggle" id="smallScreenSidebarLabel" type="button" data-bs-toggle="offcanvas" data-bs-target="#smallScreenSidebar" aria-controls="smallScreenSidebar">
          <span class="visually-hidden">Sidebar toggle for mobile</span>
          <i class="ri-arrow-right-fill"></i>
        </button>

        <!-- Sidebar Toggle for Horizontal Menu -->
        <button class="btn btn-light-light icon-btn d-lg-none small-screen-horizontal-toggle" type="button" ria-expanded="false" aria-controls="main-menu">
          <span class="visually-hidden">Sidebar toggle for horizontal</span>
          <i class="ri-arrow-right-fill"></i>
        </button>
      </div>

      <div class="header-right hstack gap-3">
        <div class="hstack gap-0 gap-sm-1">
          <!-- Theme -->
          <div class="dropdown features-dropdown d-none d-sm-block">
            <button type="button" class="btn icon-btn btn-text-primary rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="visually-hidden">Light or Dark Mode Switch</span>
              <i class="ri-sun-line fs-20"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-end header-language-scrollable" data-simplebar>

              <div class="dropdown-item cursor-pointer" id="light-theme">
                <span class="hstack gap-2 align-middle"><i class="ri-sun-line"></i>Light</span>
              </div>
              <div class="dropdown-item cursor-pointer" id="dark-theme">
                <span class="hstack gap-2 align-middle"><i class="ri-moon-clear-line"></i>Dark</span>
              </div>
              <div class="dropdown-item cursor-pointer" id="system-theme">
                <span class="hstack gap-2 align-middle"><i class="ri-computer-line"></i>System</span>
              </div>

            </div>
          </div>

          <!-- Fullscreen -->
          <button type="button" id="fullscreen-button" class="btn icon-btn btn-text-primary rounded-circle custom-toggle d-none d-sm-block" aria-pressed="false">
            <span class="visually-hidden">Toggle Fullscreen</span>
            <span class="icon-on">
              <i class="ri-fullscreen-exit-line fs-16"></i>
            </span>
            <span class="icon-off">
              <i class="ri-fullscreen-line fs-16"></i>
            </span>
          </button>
        </div>

        <!-- Profile Section -->
        <div class="dropdown profile-dropdown features-dropdown">
          <button type="button" id="accountNavbarDropdown" class="btn profile-btn shadow-none px-0 hstack gap-0 gap-sm-3" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
            <span class="position-relative">
              <span class="avatar-item avatar overflow-hidden">
                <img class="img-fluid" src="{{ asset('assets/images/avatar/avatar-default.png') }}" alt="avatar image">
              </span>
              <span class="position-absolute border-2 border border-white h-12px w-12px rounded-circle bg-success end-0 bottom-0"></span>
            </span>
            <span>
              <span class="h6 d-none d-xl-inline-block text-start fw-semibold mb-0">{{ optional(auth()->user())->name ?? '-' }}</span>
              {{-- <span class="d-none d-xl-block fs-12 text-start text-muted">{{ optional(optional(auth()->user())->role)->name ?? optional(\App\Models\Backend\Role::find(optional(auth()->user())->role_id))->name ?? '-' }}</span> --}}
            </span>
          </button>

          <div class="dropdown-menu dropdown-menu-end header-language-scrollable" aria-labelledby="accountNavbarDropdown">
            {{-- <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a> --}}
            <button type="button" class="switcher-icon dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#switcher">
              <i class="bi-sliders fs-6 me-2"></i> Ubah Tema
            </button>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('admin.logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bi-box-arrow-right fs-6 me-2"></i>
                Sign out
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</header>
<!-- END HEADER -->
