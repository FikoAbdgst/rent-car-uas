<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <!-- Total Transaksi Card -->
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card"
                style="background: linear-gradient(135deg, #ffffff, #f8fbff); border-radius: 20px; padding: 30px; border: 1px solid rgba(52, 152, 219, 0.1); box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08); transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div class="stat-card-overlay"
                    style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(155, 206, 235, 0.1)); border-radius: 50%; transform: translate(30px, -30px);">
                </div>
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="stat-icon"
                        style="width: 60px; height: 60px; background: linear-gradient(135deg, #3498db, #5dade2); border-radius: 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);">
                        <i class="fa fa-dollar-sign text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="mb-1" style="color: #7fb3d3; font-size: 0.9rem; font-weight: 500;">Total Transaksi
                        </p>
                        <h4 class="mb-0" style="color: #2c3e50; font-weight: 700; font-size: 1.8rem;">
                            @rupiah($transaksi)</h4>
                        <small class="text-success" style="font-size: 0.8rem;">
                            <i class="fa fa-arrow-up me-1"></i>+12% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobil Card -->
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card"
                style="background: linear-gradient(135deg, #ffffff, #f8fbff); border-radius: 20px; padding: 30px; border: 1px solid rgba(52, 152, 219, 0.1); box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08); transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div class="stat-card-overlay"
                    style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, rgba(46, 204, 113, 0.1), rgba(125, 230, 176, 0.1)); border-radius: 50%; transform: translate(30px, -30px);">
                </div>
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="stat-icon"
                        style="width: 60px; height: 60px; background: linear-gradient(135deg, #2ecc71, #7de8b0); border-radius: 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);">
                        <i class="fa fa-car text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="mb-1" style="color: #7fb3d3; font-size: 0.9rem; font-weight: 500;">Total Mobil</p>
                        <h4 class="mb-0" style="color: #2c3e50; font-weight: 700; font-size: 1.8rem;">
                            {{ $mobil }}</h4>
                        <small class="text-info" style="font-size: 0.8rem;">
                            <i class="fa fa-info-circle me-1"></i>Unit tersedia
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Card -->
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card"
                style="background: linear-gradient(135deg, #ffffff, #f8fbff); border-radius: 20px; padding: 30px; border: 1px solid rgba(52, 152, 219, 0.1); box-shadow: 0 10px 30px rgba(52, 152, 219, 0.08); transition: all 0.3s ease; position: relative; overflow: hidden;">
                <div class="stat-card-overlay"
                    style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, rgba(155, 89, 182, 0.1), rgba(195, 155, 211, 0.1)); border-radius: 50%; transform: translate(30px, -30px);">
                </div>
                <div class="d-flex align-items-center justify-content-between position-relative">
                    <div class="stat-icon"
                        style="width: 60px; height: 60px; background: linear-gradient(135deg, #9b59b6, #c39bd3); border-radius: 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(155, 89, 182, 0.3);">
                        <i class="fa fa-users text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <p class="mb-1" style="color: #7fb3d3; font-size: 0.9rem; font-weight: 500;">Total User</p>
                        <h4 class="mb-0" style="color: #2c3e50; font-weight: 700; font-size: 1.8rem;">
                            {{ $user }}</h4>
                        <small class="text-primary" style="font-size: 0.8rem;">
                            <i class="fa fa-user-check me-1"></i>Pengguna aktif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(52, 152, 219, 0.15) !important;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-icon {
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .stat-card {
                padding: 20px !important;
            }

            .stat-icon {
                width: 50px !important;
                height: 50px !important;
            }

            .stat-icon i {
                font-size: 1.2rem !important;
            }
        }
    </style>
</div>
