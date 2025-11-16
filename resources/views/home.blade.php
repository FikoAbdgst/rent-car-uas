@extends('layout.template')
@section('title', 'Dashboard - Rent Car')

@section('content')
    @include('layout.card')
    <style>
        body.popup-active {
            pointer-events: none;
            overflow: hidden;
        }

        body.popup-active .popup {
            pointer-events: all;
        }

        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(155, 206, 235, 0.1));
            backdrop-filter: blur(10px);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .popup-content {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            width: 400px;
            max-width: 90vw;
            box-shadow: 0 25px 50px rgba(52, 152, 219, 0.15);
            border: 1px solid rgba(52, 152, 219, 0.1);
            position: relative;
            animation: slideUp 0.3s ease-out;
        }

        .popup-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #5dade2);
            border-radius: 20px 20px 0 0;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #7fb3d3;
            transition: all 0.3s ease;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            color: #3498db;
            background: rgba(52, 152, 219, 0.1);
            transform: rotate(90deg);
        }

        .welcome-section {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(52, 152, 219, 0.1);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08);
        }

        .dashboard-stats {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(52, 152, 219, 0.1);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08);
        }

        .quick-actions {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(52, 152, 219, 0.1);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08);
        }

        .action-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(52, 152, 219, 0.1);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(52, 152, 219, 0.15);
            border-color: #3498db;
        }

        .action-card i {
            font-size: 2.5rem;
            color: #3498db;
            margin-bottom: 15px;
        }

        .action-card h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .action-card p {
            color: #7fb3d3;
            font-size: 0.9rem;
        }

        /* Pemilik specific styles */
        .pemilik-section {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(44, 62, 80, 0.2);
        }

        .pemilik-card {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            border: 1px solid rgba(52, 152, 219, 0.1);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            border-left: 4px solid #e74c3c;
        }

        .pemilik-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(231, 76, 60, 0.2);
            border-left-color: #c0392b;
        }

        .pemilik-card i {
            font-size: 2.5rem;
            color: #e74c3c;
            margin-bottom: 15px;
        }

        .admin-card {
            border-left: 4px solid #27ae60;
        }

        .admin-card:hover {
            border-left-color: #229954;
            box-shadow: 0 15px 40px rgba(39, 174, 96, 0.2);
        }

        .admin-card i {
            color: #27ae60;
        }

        .stats-card {
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(52, 152, 219, 0.1);
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(52, 152, 219, 0.15);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }

        .role-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-admin {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }

        .badge-pemilik {
            background: linear-gradient(135deg, #e74c3c, #ec7063);
            color: white;
        }

        .analytics-section {
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(142, 68, 173, 0.2);
        }

        .system-status {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(243, 156, 18, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .section-title-white {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }

        .welcome-text {
            color: #7fb3d3;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .privilege-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
    </style>

    <!-- Welcome Popup -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <div class="mb-4">
                <i class="fas fa-car fa-3x text-primary mb-3"></i>
                <h4 class="text-primary mb-3">Selamat Datang di Rent Car!</h4>
                <p class="text-muted">Sistem manajemen rental mobil yang modern dan efisien</p>
                @if (auth()->user()->role === 'pemilik')
                    <div class="privilege-indicator">
                        <i class="fas fa-crown"></i> FULL ACCESS
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-primary mb-2">
                        Selamat Datang, {{ auth()->user()->name }}!
                        @if (auth()->user()->role === 'admin')
                            <span class="role-badge badge-admin">Admin</span>
                        @elseif (auth()->user()->role === 'pemilik')
                            <span class="role-badge badge-pemilik">
                                <i class="fas fa-crown"></i> Pemilik
                            </span>
                        @endif
                    </h2>
                    <p id="datetime" class="welcome-text"></p>
                </div>
                <div class="col-md-4 text-end">
                    @if (auth()->user()->role === 'admin')
                        <i class="fas fa-tachometer-alt fa-4x text-primary" style="opacity: 0.3;"></i>
                    @elseif (auth()->user()->role === 'pemilik')
                        <i class="fas fa-crown fa-4x" style="color: #e74c3c; opacity: 0.3;"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <h4 class="section-title">
                @if (auth()->user()->role === 'admin')
                    Statistik Operasional
                @elseif (auth()->user()->role === 'pemilik')
                    Analisis Bisnis Komprehensif
                @endif
            </h4>

            @if (auth()->user()->role === 'admin')
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-handshake fa-2x text-success mb-3"></i>
                            <div class="stats-number">{{ $totalSewa ?? '0' }}</div>
                            <h5 class="text-success">Sewa Aktif</h5>
                            <p class="text-muted">Rental berlangsung</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-car fa-2x text-info mb-3"></i>
                            <div class="stats-number">{{ $totalMobil ?? '0' }}</div>
                            <h5 class="text-info">Mobil Tersedia</h5>
                            <p class="text-muted">Siap disewakan</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-users fa-2x text-warning mb-3"></i>
                            <div class="stats-number">{{ $totalPenyewa ?? '0' }}</div>
                            <h5 class="text-warning">Penyewa</h5>
                            <p class="text-muted">Terdaftar</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-chart-line fa-2x text-primary mb-3"></i>
                            <div class="stats-number">{{ $transaksiHariIni ?? '0' }}</div>
                            <h5 class="text-primary">Transaksi</h5>
                            <p class="text-muted">Hari ini</p>
                        </div>
                    </div>
                </div>
            @elseif (auth()->user()->role === 'pemilik')
                <div class="row">
                    <div class="col-md-2">
                        <div class="stats-card">
                            <i class="fas fa-money-bill-wave fa-2x text-success mb-3"></i>
                            <div class="stats-number">{{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                            <h5 class="text-success">Pendapatan</h5>
                            <p class="text-muted">Total bulan ini</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stats-card">
                            <i class="fas fa-percentage fa-2x text-info mb-3"></i>
                            <div class="stats-number">{{ $tingkatOkupansi ?? '0' }}%</div>
                            <h5 class="text-info">Okupansi</h5>
                            <p class="text-muted">Tingkat utilitas</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stats-card">
                            <i class="fas fa-chart-bar fa-2x text-warning mb-3"></i>
                            <div class="stats-number">{{ $rataRataHarian ?? '0' }}</div>
                            <h5 class="text-warning">Rata-rata</h5>
                            <p class="text-muted">Sewa per hari</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stats-card">
                            <i class="fas fa-users-cog fa-2x text-primary mb-3"></i>
                            <div class="stats-number">{{ $totalUser ?? '0' }}</div>
                            <h5 class="text-primary">Total User</h5>
                            <p class="text-muted">Sistem</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stats-card">
                            <i class="fas fa-star fa-2x text-danger mb-3"></i>
                            <div class="stats-number">{{ $ratingRataRata ?? '0' }}</div>
                            <h5 class="text-danger">Rating</h5>
                            <p class="text-muted">Kepuasan</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stats-card">
                            <i class="fas fa-trophy fa-2x text-success mb-3"></i>
                            <div class="stats-number">{{ $targetCapaian ?? '0' }}%</div>
                            <h5 class="text-success">Target</h5>
                            <p class="text-muted">Pencapaian</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Role-specific Analytics (Only for Pemilik) -->
        @if (auth()->user()->role === 'pemilik')
            <div class="analytics-section">
                <h4 class="section-title-white">
                    <i class="fas fa-chart-pie"></i> Analisis Lanjutan
                </h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-trending-up fa-3x mb-3" style="opacity: 0.8;"></i>
                            <h5>Tren Pertumbuhan</h5>
                            <p>Analisis pertumbuhan bulanan dan proyeksi</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-pie-chart fa-3x mb-3" style="opacity: 0.8;"></i>
                            <h5>Segmentasi Pasar</h5>
                            <p>Distribusi pelanggan berdasarkan kategori</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-bullseye fa-3x mb-3" style="opacity: 0.8;"></i>
                            <h5>ROI Analysis</h5>
                            <p>Return on Investment per unit kendaraan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status (Only for Pemilik) -->
            <div class="system-status">
                <h4 class="section-title-white">
                    <i class="fas fa-server"></i> Status Sistem
                </h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center">
                            <i class="fas fa-database fa-2x mb-3" style="opacity: 0.8;"></i>
                            <h5>Database Health</h5>
                            <p>Status: <span class="badge bg-success">Optimal</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <i class="fas fa-shield-alt fa-2x mb-3" style="opacity: 0.8;"></i>
                            <h5>Security Status</h5>
                            <p>Status: <span class="badge bg-success">Secure</span></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        @if (auth()->user()->role === 'admin')
            <div class="quick-actions">
                <h4 class="section-title">Aksi Cepat - Operasional</h4>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="action-card admin-card" onclick="window.location.href='{{ route('sewa') }}'">
                            <i class="fas fa-plus-circle"></i>
                            <h5>Sewa Baru</h5>
                            <p>Buat transaksi sewa mobil baru</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="action-card admin-card" onclick="window.location.href='{{ route('mobil') }}'">
                            <i class="fas fa-car"></i>
                            <h5>Kelola Mobil</h5>
                            <p>Tambah atau edit data mobil</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="action-card admin-card" onclick="window.location.href='{{ route('penyewa') }}'">
                            <i class="fas fa-user-plus"></i>
                            <h5>Penyewa</h5>
                            <p>Kelola data penyewa</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="action-card admin-card" onclick="window.location.href='{{ route('transaksi') }}'">
                            <i class="fas fa-file-invoice"></i>
                            <h5>Transaksi</h5>
                            <p>Lihat riwayat transaksi</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->role === 'pemilik')
            <div class="quick-actions">
                <h4 class="section-title">Kontrol Penuh Sistem</h4>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('laporan') }}'">
                            <div class="privilege-indicator">FULL</div>
                            <i class="fas fa-chart-bar"></i>
                            <h5>Laporan Komprehensif</h5>
                            <p>Analisis mendalam semua aspek bisnis</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('users') }}'">
                            <div class="privilege-indicator">ADMIN</div>
                            <i class="fas fa-users-cog"></i>
                            <h5>Manajemen User</h5>
                            <p>Kontrol penuh hak akses pengguna</p>
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('finance') }}'">
                            <div class="privilege-indicator">FINANCE</div>
                            <i class="fas fa-coins"></i>
                            <h5>Manajemen Keuangan</h5>
                            <p>Laporan keuangan dan cash flow</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('settings') }}'">
                            <div class="privilege-indicator">SYSTEM</div>
                            <i class="fas fa-cog"></i>
                            <h5>Pengaturan Sistem</h5>
                            <p>Konfigurasi tingkat lanjut</p>
                        </div>
                    </div> --}}
                </div>

                <!-- Additional Management Tools for Pemilik -->
                {{-- <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('backup') }}'">
                            <div class="privilege-indicator">BACKUP</div>
                            <i class="fas fa-database"></i>
                            <h5>Backup & Recovery</h5>
                            <p>Manajemen cadangan data sistem</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('audit') }}'">
                            <div class="privilege-indicator">AUDIT</div>
                            <i class="fas fa-search"></i>
                            <h5>Audit Trail</h5>
                            <p>Log aktivitas dan keamanan sistem</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="action-card pemilik-card" onclick="window.location.href='{{ route('analytics') }}'">
                            <div class="privilege-indicator">ANALYTICS</div>
                            <i class="fas fa-brain"></i>
                            <h5>Business Intelligence</h5>
                            <p>Analisis prediktif dan insights</p>
                        </div>
                    </div>
                </div> --}}
            </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            var popup = document.getElementById("popup");
            var closeBtn = document.getElementsByClassName("close-btn")[0];

            document.body.classList.add('popup-active');
            popup.style.display = "flex";

            closeBtn.onclick = function() {
                popup.style.display = "none";
                document.body.classList.remove('popup-active');
            }

            window.onclick = function(event) {
                if (event.target == popup) {
                    popup.style.display = "none";
                    document.body.classList.remove('popup-active');
                }
            }
        }

        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: 'Asia/Jakarta'
            };
            const formattedDateTime = now.toLocaleString('id-ID', options);
            document.getElementById('datetime').textContent = formattedDateTime;
        }

        // Panggil fungsi saat halaman dimuat
        updateDateTime();
        // Perbarui setiap detik
        setInterval(updateDateTime, 1000);
    </script>

@endsection
