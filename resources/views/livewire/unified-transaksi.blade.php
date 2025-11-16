<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <h6 class="mb-4">Data Transaksi</h6>

                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Filter Transaksi</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Filter Status:</label>
                                    <div class="btn-group" role="group">
                                        <button type="button"
                                            class="btn {{ $statusFilter === 'ALL' ? 'btn-primary' : 'btn-outline-primary' }}"
                                            wire:click="filterByStatus('ALL')">
                                            Semua ({{ $this->getAllTransaksiCount() }})
                                        </button>
                                        <button type="button"
                                            class="btn {{ $statusFilter === 'WAIT' ? 'btn-warning' : 'btn-outline-warning' }}"
                                            wire:click="filterByStatus('WAIT')">
                                            Menunggu ({{ $this->getStatusCount('WAIT') }})
                                        </button>
                                        <button type="button"
                                            class="btn {{ $statusFilter === 'PROSES' ? 'btn-info' : 'btn-outline-info' }}"
                                            wire:click="filterByStatus('PROSES')">
                                            Proses ({{ $this->getStatusCount('PROSES') }})
                                        </button>
                                        <button type="button"
                                            class="btn {{ $statusFilter === 'SELESAI' ? 'btn-success' : 'btn-outline-success' }}"
                                            wire:click="filterByStatus('SELESAI')">
                                            Selesai ({{ $this->getStatusCount('SELESAI') }})
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Mulai:</label>
                                        <input type="date" class="form-control" wire:model="tanggal1">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Akhir:</label>
                                        <input type="date" class="form-control" wire:model="tanggal2">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary" wire:click="cari">
                                                <i class="fa fa-search"></i> Cari
                                            </button>
                                            <button type="button" class="btn btn-secondary" wire:click="clearFilter">
                                                <i class="fa fa-refresh"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">No Polisi</th>
                                <th scope="col">Merek</th>
                                <th scope="col">Nama Penyewa</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Telp</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Tanggal Mulai</th>
                                <th scope="col">Tanggal Kembali</th>
                                <th scope="col">Tanggal Dikembalikan</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Status</th>
                                <th scope="col">Catatan</th>
                                @if (auth()->user()->role !== 'pemilik')
                                    <th scope="col">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $data)
                                <tr>
                                    <th scope="row">
                                        {{ $loop->iteration + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}
                                    </th>
                                    <td>{{ $data->mobil->platnomor }}</td>
                                    <td>{{ $data->mobil->merek }}</td>
                                    <td>{{ $data->penyewa->nama }}</td>
                                    <td>{{ $data->penyewa->nik }}</td>
                                    <td>{{ $data->penyewa->telp }}</td>
                                    <td>{{ $data->penyewa->alamat }}</td>
                                    <td>{{ date('d/m/Y', strtotime($data->tanggalmulai)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($data->tanggalkembali)) }}</td>
                                    <td>{{ $data->tanggaldikembalikan ? date('d/m/Y', strtotime($data->tanggaldikembalikan)) : '-' }}
                                    </td>
                                    <td>Rp {{ number_format($data->totalharga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="{{ $this->getStatusBadgeClass($data->status) }}">
                                            {{ $data->status }}
                                        </span>
                                    </td>
                                    <td>{{ $data->catatan ?? '-' }}</td>
                                    @if (auth()->user()->role !== 'pemilik')
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Edit Button -->
                                                <button class="btn btn-sm btn-warning"
                                                    wire:click="edit({{ $data->id }})" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <!-- Delete Button -->
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="openDeleteModal({{ $data->id }})" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                                <!-- Status Action Buttons -->
                                                @if ($data->status == 'WAIT')
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="prosesTransaksi({{ $data->id }})" title="Proses">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                @endif
                                                @if ($data->status == 'PROSES')
                                                    <button class="btn btn-sm btn-success"
                                                        onclick="selesaiTransaksi({{ $data->id }})"
                                                        title="Selesai">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role !== 'pemilik' ? '14' : '13' }}"
                                        class="text-center">
                                        <div class="py-4">
                                            <i class="fa fa-inbox fa-2x text-muted"></i>
                                            <p class="mt-2 text-muted">
                                                @if ($statusFilter === 'ALL')
                                                    Belum ada transaksi
                                                @else
                                                    Tidak ada transaksi dengan status {{ $statusFilter }}
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal Edit Transaksi -->
                @if ($showEditModal && auth()->user()->role !== 'pemilik')
                    <div class="modal fade show" id="editModal"
                        style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Transaksi</h5>
                                    <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="updateTransaksi">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobil <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select" wire:model.defer="editMobilId">
                                                        <option value="">Pilih Mobil</option>
                                                        @foreach ($mobils as $mobil)
                                                            <option value="{{ $mobil->id }}">
                                                                {{ $mobil->namamobil }} - {{ $mobil->platnomor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('editMobilId')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Penyewa <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select" wire:model.defer="editPenyewaId">
                                                        <option value="">Pilih Penyewa</option>
                                                        @foreach ($penyewas as $penyewa)
                                                            <option value="{{ $penyewa->id }}">
                                                                {{ $penyewa->nama }} - {{ $penyewa->nik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('editPenyewaId')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Mulai <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control"
                                                        wire:model.defer="editTanggalMulai">
                                                    @error('editTanggalMulai')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Kembali <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control"
                                                        wire:model.defer="editTanggalKembali">
                                                    @error('editTanggalKembali')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Total Harga <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control"
                                                        wire:model.defer="editTotalHarga" min="0">
                                                    @error('editTotalHarga')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Status <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select" wire:model.defer="editStatus">
                                                        <option value="">Pilih Status</option>
                                                        <option value="WAIT">WAIT</option>
                                                        <option value="PROSES">PROSES</option>
                                                        <option value="SELESAI">SELESAI</option>
                                                    </select>
                                                    @error('editStatus')
                                                        <div class="text-danger small">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan</label>
                                            <textarea class="form-control" wire:model.defer="editCatatan" rows="3"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="closeEditModal">Batal</button>
                                    <button type="button" class="btn btn-primary" wire:click="updateTransaksi">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Modal Delete Transaksi -->
                @if ($showDeleteModal && auth()->user()->role !== 'pemilik')
                    <div class="modal fade show" id="deleteModal"
                        style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" wire:click="closeDeleteModal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5>Apakah Anda yakin ingin menghapus transaksi ini?</h5>

                                        @if ($deleteTransaksiInfo)
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <p><strong>Mobil:</strong> {{ $deleteTransaksiInfo['mobil'] }}</p>
                                                    <p><strong>Penyewa:</strong> {{ $deleteTransaksiInfo['penyewa'] }}
                                                    </p>
                                                    <p><strong>Periode:</strong> {{ $deleteTransaksiInfo['tanggal'] }}
                                                    </p>
                                                    <p><strong>Total:</strong> {{ $deleteTransaksiInfo['total'] }}</p>
                                                </div>
                                            </div>
                                        @endif

                                        <p class="text-danger mt-3 mb-0">
                                            <small><strong>Peringatan:</strong> Data yang sudah dihapus tidak dapat
                                                dikembalikan!</small>
                                        </p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="closeDeleteModal">Batal</button>
                                    <button type="button" class="btn btn-danger" wire:click="deleteTransaksi">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Menampilkan {{ $transaksi->firstItem() ?? 0 }} - {{ $transaksi->lastItem() ?? 0 }}
                        dari {{ $transaksi->total() }} hasil
                    </div>
                    <div>
                        {{ $transaksi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function prosesTransaksi(id) {
        if (confirm('Apakah Anda yakin ingin memproses transaksi ini?')) {
            @this.call('proses', id);
        }
    }

    function selesaiTransaksi(id) {
        if (confirm('Apakah Anda yakin ingin menyelesaikan transaksi ini?')) {
            @this.call('selesai', id);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('showEditModal', () => {
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });

        Livewire.on('showDeleteModal', () => {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });
</script>
