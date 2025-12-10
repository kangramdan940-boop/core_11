<div class="hstack flex-wrap gap-3 mb-4">
    <div class="flex-grow-1 d-sm-flex align-items-sm-center justify-content-sm-between">
        @php($heading = trim(View::yieldContent('breadcrumbExtra')) ?: trim(View::yieldContent('sub-title')))
        <h4 class="mb-1 fw-semibold">{{ $heading }}</h4>
        <nav>
            <ol class="breadcrumb breadcrumb-arrow mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin/dashboard') }}"><i class="ri-home-4-line me-1"></i> Dashboard</a>
                </li>
                @if (!empty(trim(View::yieldContent('sub-title'))))
                    @php($subLink = trim(View::yieldContent('subLink')))
                    @if (!empty($subLink))
                        <li class="breadcrumb-item"><a href="{{ $subLink }}">@yield('sub-title')</a></li>
                    @else
                        <li class="breadcrumb-item">@yield('sub-title')</li>
                    @endif
                @endif
                @if (!empty(trim(View::yieldContent('breadcrumbExtra'))))
                    <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumbExtra')</li>
                @endif
            </ol>
        </nav>
    </div>
    <div class="d-flex my-xl-auto align-items-center flex-shrink-0">
        @if (!empty(trim(View::yieldContent('modalTarget'))))
            <a href="javascript:void(0)" 
               class="btn btn-sm btn-primary" 
               data-bs-toggle="modal" 
               data-bs-target="#@yield('modalTarget')">
                @yield('buttonTitle')
            </a>
        @endif
    </div>
  </div>
  
