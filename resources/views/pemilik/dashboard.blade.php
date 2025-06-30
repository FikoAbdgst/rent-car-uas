<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilik - Rental Mobil</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --light-blue: #e3f2fd;
            --dark-blue: #0056b3;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: white !important;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--light-blue) !important;
            transform: translateY(-2px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .section-title {
            color: var(--dark-blue);
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .car-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.2);
        }

        .car-image {
            height: 200px;
            background: var(--light-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 3rem;
        }

        .car-info {
            padding: 20px;
        }

        .car-title {
            color: var(--dark-blue);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .car-price {
            color: var(--primary-blue);
            font-size: 1.5rem;
            font-weight: bold;
        }

        .car-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status-tersedia {
            background: #d4edda;
            color: #155724;
        }

        .status-disewa {
            background: #f8d7da;
            color: #721c24;
        }

        .about-section {
            background: white;
            padding: 80px 0;
            margin: 50px 0;
        }

        .about-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: var(--light-blue);
            margin-bottom: 30px;
        }

        .about-icon {
            color: var(--primary-blue);
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .footer {
            background: var(--dark-blue);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary-blue);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="fas fa-car"></i> Rental Mobil
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#mobil">Mobil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('home') }}">Dashboard Admin</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('login.keluar') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="home" class="hero-section">
        <div class="container">
            <h1>Selamat Datang di Rental Mobil</h1>
            <p>Pilihan terbaik untuk kebutuhan transportasi Anda</p>
            <a href="#mobil" class="btn btn-light btn-lg mt-3">
                Lihat Mobil <i class="fas fa-arrow-down"></i>
            </a>
        </div>
    </section>

    <section id="mobil" class="py-5">
        <div class="container">
            <h2 class="section-title">Koleksi Mobil Kami</h2>
            <div class="row">
                @forelse($mobils as $mobil)
                    <div class="col-md-4">
                        <div class="car-card">
                            <div class="car-image">
                                @if ($mobil->foto)
                                    <img src="{{ asset('storage/' . $mobil->foto) }}"
                                        alt="{{ $mobil->merk }} {{ $mobil->model }}" class="img-fluid"
                                        style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                    <i class="fas fa-car"></i>
                                @endif
                            </div>
                            <div class="car-info">
                                <h5 class="car-title">{{ $mobil->merk }} {{ $mobil->model }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar"></i> {{ $mobil->tahun }} |
                                    <i class="fas fa-palette"></i> {{ $mobil->warna }}
                                </p>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-id-card"></i> {{ $mobil->plat_nomor }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="car-price">Rp
                                        {{ number_format($mobil->harga_sewa, 0, ',', '.') }}/hari</span>
                                    <span
                                        class="car-status {{ $mobil->status == 'tersedia' ? 'status-tersedia' : 'status-disewa' }}">
                                        {{ ucfirst($mobil->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-car text-muted" style="font-size: 5rem;"></i>
                            <h4 class="text-muted mt-3">Belum ada mobil tersedia</h4>
                            <p class="text-muted">Silakan tambahkan mobil melalui dashboard admin</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title">Mengapa Memilih Kami?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="about-card">
                        <div class="about-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Terpercaya</h4>
                        <p>Kami memberikan pelayanan terbaik dengan sistem keamanan yang terjamin untuk kenyamanan Anda.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-card">
                        <div class="about-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h4>Mobil Berkualitas</h4>
                        <p>Semua mobil kami dalam kondisi prima dan terawat dengan baik untuk kepuasan berkendara Anda.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-card">
                        <div class="about-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Layanan 24/7</h4>
                        <p>Kami siap melayani Anda kapan saja dengan customer service yang responsif dan profesional.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-car"></i> Rental Mobil</h5>
                    <p>Solusi terbaik untuk kebutuhan transportasi Anda</p>
                </div>
                <div class="col-md-6">
                    <h5>Kontak Kami</h5>
                    <p>
                        <i class="fas fa-phone"></i> +62 123 456 789<br>
                        <i class="fas fa-envelope"></i> info@rentalmobil.com<br>
                        <i class="fas fa-map-marker-alt"></i> Bandung, West Java
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Rental Mobil. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(0, 123, 255, 0.95)';
            } else {
                navbar.style.background = 'linear-gradient(135deg, var(--primary-blue), var(--dark-blue))';
            }
        });
    </script>
</body>

</html>
