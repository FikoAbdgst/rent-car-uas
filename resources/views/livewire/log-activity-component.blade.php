<div>
    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .description-cell {
            max-width: 200px;
            word-wrap: break-word;
        }

        pre {
            max-height: 200px;
            overflow-y: auto;
            font-size: 11px;
        }
    </style>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Log Activity Admin
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Action</label>
                            <select wire:model.live="filterAction" class="form-select">
                                <option value="">Semua Action</option>
                                <option value="CREATE">CREATE</option>
                                <option value="UPDATE">UPDATE</option>
                                <option value="DELETE">DELETE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Table</label>
                            <select wire:model.live="filterTable" class="form-select">
                                <option value="">Semua Table</option>
                                <option value="mobils">Mobil</option>
                                <option value="transaksis">Transaksi</option>
                                <option value="penyewas">Penyewa</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter User</label>
                            <input type="text" wire:model.live="filterUser" class="form-control"
                                placeholder="Nama user...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Tanggal</label>
                            <input type="date" wire:model.live="filterDate" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button wire:click="clearFilters" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eraser me-1"></i>Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Table</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="hidden avatar-sm rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <small class="fw-bold">{{ $log->user->name }}</small><br>
                                            <small class="text-muted">{{ $log->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="{{ $this->getActionBadgeClass($log->action) }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $this->getTableBadgeClass($log->table_name) }}">
                                        {{ $this->getTableDisplayName($log->table_name) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="description-cell">
                                        {{ Str::limit($log->description, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <small>
                                        {{ $log->created_at->format('d/m/Y H:i') }}<br>
                                        <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                                <td>
                                    <button wire:click="showLogDetail({{ $log->id }})"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>Tidak ada log activity ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    @if ($showDetail && $selectedLog)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Log Activity</h5>
                        <button type="button" class="btn-close" wire:click="closeDetail"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>ID:</strong> {{ $selectedLog->id }}
                            </div>
                            <div class="col-md-6">
                                <strong>User:</strong> {{ $selectedLog->user->name }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Action:</strong>
                                <span class="{{ $this->getActionBadgeClass($selectedLog->action) }}">
                                    {{ $selectedLog->action }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Table:</strong>
                                <span class="{{ $this->getTableBadgeClass($selectedLog->table_name) }}">
                                    {{ $this->getTableDisplayName($selectedLog->table_name) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Record ID:</strong> {{ $selectedLog->record_id }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal:</strong> {{ $selectedLog->created_at->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <strong>Deskripsi:</strong>
                                <p class="border rounded p-2 bg-light">{{ $selectedLog->description }}</p>
                            </div>
                        </div>

                        @if ($selectedLog->old_data)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <strong>Data Lama:</strong>
                                    <pre class="border rounded p-2 bg-light small">{{ json_encode($selectedLog->old_data, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif

                        @if ($selectedLog->new_data)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <strong>Data Baru:</strong>
                                    <pre class="border rounded p-2 bg-light small">{{ json_encode($selectedLog->new_data, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetail">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
