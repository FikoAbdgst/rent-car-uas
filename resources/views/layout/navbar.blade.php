<nav class="navbar navbar-expand navbar-light sticky-top px-4 py-3"
    style="background: linear-gradient(135deg, #ffffff, #f8fbff); border-bottom: 1px solid rgba(52, 152, 219, 0.1); box-shadow: 0 2px 20px rgba(52, 152, 219, 0.08);">
    <a href="#" class="navbar-brand d-flex d-lg-none me-4">
        <div
            style="width: 40px; height: 40px; background: linear-gradient(135deg, #3498db, #5dade2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i class="fa fa-car text-white"></i>
        </div>
    </a>

    <a href="#" class="sidebar-toggler flex-shrink-0 me-3"
        style="color: #3498db; font-size: 1.2rem; padding: 8px 12px; border-radius: 8px; transition: all 0.3s ease; background: rgba(52, 152, 219, 0.1); border: none;">
        <i class="fa fa-bars"></i>
    </a>

    <!-- Breadcrumb -->
    <div class="d-none d-md-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background: transparent;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" style="color: #7fb3d3; text-decoration: none;">
                        <i class="fa fa-home me-1"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" style="color: #3498db;">
                    {{ ucfirst(str_replace('.', ' ', Route::currentRouteName())) }}
                </li>
            </ol>
        </nav>
    </div>



    <style>
        .sidebar-toggler:hover {
            background: rgba(52, 152, 219, 0.2) !important;
            transform: scale(1.05);
        }

        .nav-link:hover {
            background: rgba(52, 152, 219, 0.1) !important;
            transform: translateY(-1px);
        }

        .dropdown-item:hover {
            background: rgba(52, 152, 219, 0.1) !important;
            color: #3498db !important;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            color: #7fb3d3;
        }
    </style>
</nav>
