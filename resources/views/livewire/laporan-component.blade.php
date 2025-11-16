<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <h6 class="mb-4">Laporan Keuangan</h6>

                <!-- Filter Tahun -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" wire:model="selectedYear" wire:change="$refresh">
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-9 d-flex justify-content-end">
                        <button class="btn btn-primary" wire:click="exportAllPdf">Export Semua Data (PDF)</button>
                    </div>
                </div>

                <!-- Total Keseluruhan -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Total Keseluruhan Tahun {{ $selectedYear }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <h6>Total Pemasukan</h6>
                                        <h4>@rupiah($yearlyData['total_income'])</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body text-center">
                                        <h6>Total Pengeluaran</h6>
                                        <h4>@rupiah($yearlyData['total_expense'])</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <h6>Keuntungan Bersih</h6>
                                        <h4>@rupiah($yearlyData['net_profit'])</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white h-100">
                                    <div class="card-body text-center">
                                        <h6>Total Transaksi</h6>
                                        <h4>{{ $yearlyData['total_transactions'] }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kalender Bulanan -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Laporan Per Bulan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($months as $monthData)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <div class="card month-card h-100 {{ $monthData['is_current'] ? 'border-primary' : 'border-secondary' }}"
                                        style="cursor: pointer; transition: all 0.3s ease;"
                                        @if ($monthData['is_current']) wire:click="openModal({{ $monthData['month'] }}, {{ $monthData['year'] }})"
                                             onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'"
                                             onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none'" @endif>
                                        <div class="card-body text-center p-3">
                                            <h6 class="card-title mb-2">{{ $monthData['month_name'] }}</h6>
                                            @if ($monthData['is_current'])
                                                <div class="mb-2">
                                                    <small class="text-success">
                                                        <i class="fas fa-arrow-up"></i> @rupiah($monthData['data']['total_income'])
                                                    </small>
                                                </div>
                                                <div class="mb-2">
                                                    <small class="text-danger">
                                                        <i class="fas fa-arrow-down"></i> @rupiah($monthData['data']['total_expense'])
                                                    </small>
                                                </div>
                                                <div>
                                                    <strong class="text-primary">
                                                        @rupiah($monthData['data']['net_profit'])
                                                    </strong>
                                                </div>
                                                <div class="mt-2">
                                                    <span
                                                        class="badge bg-info">{{ count($monthData['data']['transaksi']) }}
                                                        Transaksi</span>
                                                </div>
                                            @else
                                                <div class="text-muted">
                                                    <i class="fas fa-clock"></i>
                                                    <small>Coming Soon</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Bulanan -->
    @if ($showModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Laporan {{ $modalData['month_name'] }} {{ $selectedYear }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6>Pemasukan</h6>
                                        <h4>@rupiah($modalData['total_income'])</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h6>Pengeluaran</h6>
                                        <h4>@rupiah($modalData['total_expense'])</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h6>Keuntungan Bersih</h6>
                                        <h4>@rupiah($modalData['net_profit'])</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs untuk Transaksi dan Pengeluaran -->
                        <ul class="nav nav-tabs" id="detailTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="transaksi-tab" data-bs-toggle="tab"
                                    data-bs-target="#transaksi" type="button">
                                    Transaksi ({{ count($modalData['transaksi']) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="expenses-tab" data-bs-toggle="tab"
                                    data-bs-target="#expenses" type="button">
                                    Pengeluaran ({{ count($modalData['expenses']) }})
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="detailTabContent">
                            <!-- Tab Transaksi -->
                            <div class="tab-pane fade show active" id="transaksi">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>No Polisi</th>
                                                <th>Nama Penyewa</th>
                                                <th>Alamat</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Kembali</th>
                                                <th>Total Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($modalData['transaksi'] as $transaksi)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-primary">{{ $transaksi->mobil->platnomor ?? 'N/A' }}</span>
                                                    </td>
                                                    <td>{{ $transaksi->penyewa->nama ?? 'N/A' }}</td>
                                                    <td>{{ $transaksi->penyewa->alamat ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggalmulai)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggalkembali)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-success fw-bold">@rupiah($transaksi->totalharga)</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        <i class="fas fa-inbox"></i> Tidak ada transaksi
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Pengeluaran -->
                            <div class="tab-pane fade" id="expenses">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Kategori</th>
                                                <th>Deskripsi</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($modalData['expenses'] as $expense)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-secondary">{{ $expense->kategori }}</span>
                                                    </td>
                                                    <td>{{ $expense->deskripsi }}</td>
                                                    <td class="text-danger fw-bold">@rupiah($expense->jumlah)</td>
                                                    <td>{{ \Carbon\Carbon::parse($expense->tanggal)->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">
                                                        <i class="fas fa-inbox"></i> Tidak ada pengeluaran
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        <button type="button" class="btn btn-primary"
                            wire:click="exportMonthlyPdf({{ $selectedMonth }}, {{ $selectedYear }})">
                            <i class="fas fa-download"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
