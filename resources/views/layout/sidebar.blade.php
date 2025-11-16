<div class="sidebar pe-4 pb-3"
    style="background: linear-gradient(135deg, #ffffff, #f8fbff); border-right: 1px solid rgba(52, 152, 219, 0.1); box-shadow: 5px 0 20px rgba(52, 152, 219, 0.08);">
    <nav class="navbar navbar-light" style="background: transparent;">
        <a href="{{ route('home') }}" class="navbar-brand mx-4 mb-3" style="text-decoration: none;">
            <div class="d-flex align-items-center">
                <div class="brand-icon me-3"
                    style="width: 50px; height: 50px; background: linear-gradient(135deg, #3498db, #5dade2); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-car text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h3 class="text-primary mb-0" style="font-weight: 700; font-size: 1.5rem;">Rent Car</h3>
                    <small class="text-muted" style="font-size: 0.8rem;">Management System</small>
                </div>
            </div>
        </a>

        <div class="user-profile d-flex align-items-center ms-4 mb-4 p-3"
            style="background: rgba(255, 255, 255, 0.8); border-radius: 15px; border: 1px solid rgba(52, 152, 219, 0.1);">
            <div class="position-relative me-3">
                <img class="rounded-circle" src="{{ asset('assets/img/user.jpg') }}" alt=""
                    style="width: 45px; height: 45px; border: 3px solid #3498db;">
                <div
                    class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1 text-primary" style="font-weight: 600;">{{ auth()->user()->name }}</h6>
                <span class="badge"
                    style="background: linear-gradient(135deg, #3498db, #5dade2); color: white; font-size: 0.75rem; padding: 4px 8px; border-radius: 8px;">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        <div class="navbar-nav w-100 px-3">
            <style>
                .nav-item.nav-link {
                    margin-bottom: 8px;
                    padding: 12px 16px;
                    border-radius: 12px;
                    transition: all 0.3s ease;
                    color: #7fb3d3;
                    text-decoration: none;
                    display: flex;
                    align-items: center;
                    font-weight: 500;
                }

                .nav-item.nav-link:hover {
                    background: rgba(52, 152, 219, 0.1);
                    color: #3498db;
                    transform: translateX(5px);
                }

                .nav-item.nav-link.active {
                    background: linear-gradient(135deg, #3498db, #5dade2);
                    color: white;
                    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
                }

                .nav-item.nav-link i {
                    width: 20px;
                    margin-right: 12px;
                    font-size: 1.1rem;
                }
            </style>

            <!-- Admin Specific Routes -->
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('home') }}"
                    class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="{{ route('transaksi') }}"
                    class="nav-item nav-link {{ request()->routeIs('transaksi') ? 'active' : '' }}">
                    <i class="fa fa-shopping-cart"></i>Transaksi
                </a>
                <a href="{{ route('sewa') }}"
                    class="nav-item nav-link {{ request()->routeIs('sewa') ? 'active' : '' }}">
                    <i class="fa fa-clock"></i>Sewa Mobil
                </a>
                <a href="{{ route('mobil') }}"
                    class="nav-item nav-link {{ request()->routeIs('mobil') ? 'active' : '' }}">
                    <i class="fa fa-car"></i>Mobil
                </a>
                <a href="{{ route('penyewa') }}"
                    class="nav-item nav-link {{ request()->routeIs('penyewa') ? 'active' : '' }}">
                    <i class="fa fa-users"></i>Penyewa
                </a>
                <a href="{{ route('expense') }}"
                    class="nav-item nav-link {{ request()->routeIs('expense') ? 'active' : '' }}">
                    <i class="fa fa-wrench"></i> Expense
                </a>
            @endif

            <!-- Pemilik Specific Routes -->
            @if (auth()->user()->role === 'pemilik')
                <a href="{{ route('home') }}"
                    class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa fa-tachometer-alt"></i>Dashboard
                </a>
                <a href="{{ route('transaksi') }}"
                    class="nav-item nav-link {{ request()->routeIs('transaksi') ? 'active' : '' }}">
                    <i class="fa fa-shopping-cart"></i>Transaksi
                </a>
                <a href="{{ route('expense') }}"
                    class="nav-item nav-link {{ request()->routeIs('expense') ? 'active' : '' }}">
                    <i class="fa fa-wrench"></i> Expense
                </a>
                <a href="{{ route('mobil') }}"
                    class="nav-item nav-link {{ request()->routeIs('mobil') ? 'active' : '' }}">
                    <i class="fa fa-car"></i>Mobil
                </a>
                <a href="{{ route('penyewa') }}"
                    class="nav-item nav-link {{ request()->routeIs('penyewa') ? 'active' : '' }}">
                    <i class="fa fa-users"></i>Penyewa
                </a>
                <a href="{{ route('laporan') }}"
                    class="nav-item nav-link {{ request()->routeIs('laporan') ? 'active' : '' }}">
                    <i class="fa fa-chart-bar"></i>Laporan
                </a>
                <a href="{{ route('log-activity') }}"
                    class="nav-item nav-link {{ request()->routeIs('log-activity') ? 'active' : '' }}">
                    <i class="bi-person-fill-gear "></i>Log Activity
                </a>
                <a href="{{ route('users') }}"
                    class="nav-item nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                    <i class="fa fa-users-cog"></i>Kelola User
                </a>
            @endif

            <!-- Divider -->
            <hr style="border-color: rgba(52, 152, 219, 0.2); margin: 20px 0;">

            <!-- Additional Options -->
            <div class="mt-auto">

                <a href="{{ route('login.keluar') }}" class="nav-item nav-link"
                    style=" color:red ; font-size: 0.9rem; opacity: 0.8;">
                    <i class="fa fa-sign-out-alt "></i>Keluar
                </a>

            </div>
        </div>
    </nav>
</div>
