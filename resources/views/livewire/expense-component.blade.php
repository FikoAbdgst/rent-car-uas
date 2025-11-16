<div class="container-fluid py-4">
    <!-- Header & Actions -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h4 class="mb-0">Expense Management</h4>
            <p class="text-muted">Kelola pengeluaran kendaraan</p>
        </div>
        <div class="col-md-6 text-end">
            @if (!$addPage && !$editPage)
                <button wire:click="create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Expense
                </button>
            @endif
        </div>
    </div>

    <!-- Form Section -->
    @if ($addPage || $editPage)

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">{{ $editPage ? 'Edit' : 'Tambah' }} Expense</h5>
                <button wire:click="closeForm" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="store">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select wire:model="kategori" class="form-select">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobil (Opsional)</label>
                            <select wire:model="mobil_id" class="form-select">
                                <option value="">Pilih Mobil</option>
                                @foreach ($mobils as $mobil)
                                    <option value="{{ $mobil->id }}">{{ $mobil->namamobil }}
                                        ({{ $mobil->platnomor }})
                                    </option>
                                @endforeach
                            </select>
                            @error('mobil_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Deskripsi *</label>
                            <input type="text" wire:model="deskripsi" class="form-control"
                                placeholder="Masukkan deskripsi expense">
                            @error('deskripsi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jumlah *</label>
                            <input type="number" wire:model="jumlah" class="form-control" placeholder="0"
                                min="1">
                            @error('jumlah')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal *</label>
                            <input type="date" wire:model="tanggal" class="form-control">
                            @error('tanggal')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Metode Pembayaran *</label>
                            <select wire:model="metode_pembayaran" class="form-select">
                                <option value="">Pilih Metode</option>
                                @foreach ($metodePembayaran as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('metode_pembayaran')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">No. Referensi</label>
                            <input type="text" wire:model="nomor_referensi" class="form-control"
                                placeholder="No. transaksi/referensi">
                            @error('nomor_referensi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bukti Pembayaran</label>
                            <input type="file" wire:model="newBukti" class="form-control" accept="image/*">
                            @if ($currentBukti)
                                <small class="text-muted">File saat ini: {{ basename($currentBukti) }}</small>
                            @endif
                            @error('newBukti')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea wire:model="catatan" class="form-control" rows="2" placeholder="Catatan tambahan"></textarea>
                            @error('catatan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ $editPage ? 'Update' : 'Simpan' }}
                        </button>
                        <button type="button" wire:click="closeForm" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Filters -->
    @if (!$addPage && !$editPage)

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input wire:model.live="search" type="text" class="form-control"
                            placeholder="Cari deskripsi, no. referensi...">
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="filterStatus" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="wait">Menunggu</option>
                            <option value="proses">Dalam Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="filterKategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button wire:click="$refresh" class="btn btn-outline-primary w-100">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <div class="card">
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Mobil</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge bg-light text-dark">{{ $kategoris[$expense->kategori] ?? $expense->kategori }}</span>
                                </td>
                                <td>
                                    <div>{{ $expense->deskripsi }}</div>
                                    @if ($expense->nomor_referensi)
                                        <small class="text-muted">Ref: {{ $expense->nomor_referensi }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($expense->mobil)
                                        <small>{{ $expense->mobil->namamobil }}<br>{{ $expense->mobil->platnomor }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($expense->jumlah, 0, ',', '.') }}</strong><br>
                                    <small
                                        class="text-muted">{{ $metodePembayaran[$expense->metode_pembayaran] ?? $expense->metode_pembayaran }}</small>
                                </td>
                                <td>
                                    @if ($expense->status == 'wait')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($expense->status == 'proses')
                                        <span class="badge bg-info">Dalam Proses</span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if (auth()->user()->hasRole('pemilik'))
                                        <!-- Hanya tampilkan tombol Lihat Bukti untuk role pemilik -->
                                        @if ($expense->bukti_pembayaran)
                                            <a href="{{ Storage::url($expense->bukti_pembayaran) }}" target="_blank"
                                                class="btn btn-outline-secondary btn-sm" title="Lihat Bukti">
                                                <i class="fas fa-image"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        <!-- Logika untuk role lain -->
                                        <div class="btn-group btn-group-sm">
                                            @if ($expense->status == 'wait')
                                                <button wire:click="prosesExpense({{ $expense->id }})"
                                                    class="btn btn-outline-info" title="Proses">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button wire:click="edit({{ $expense->id }})"
                                                    class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button wire:click="delete({{ $expense->id }})"
                                                    class="btn btn-outline-danger" title="Hapus"
                                                    onclick="return confirm('Yakin hapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @elseif($expense->status == 'proses')
                                                <button wire:click="selesaiExpense({{ $expense->id }})"
                                                    class="btn btn-outline-success" title="Selesai">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif

                                            @if ($expense->bukti_pembayaran)
                                                <a href="{{ Storage::url($expense->bukti_pembayaran) }}"
                                                    target="_blank" class="btn btn-outline-secondary"
                                                    title="Lihat Bukti">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Belum ada data expense</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Menunggu Approval</h6>
                    <h4>{{ $expenses->where('status', 'wait')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Dalam Proses</h6>
                    <h4>{{ $expenses->where('status', 'proses')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Selesai</h6>
                    <h4>{{ $expenses->where('status', 'selesai')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Total Expense</h6>
                    <h4>Rp {{ number_format($expenses->sum('jumlah'), 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
