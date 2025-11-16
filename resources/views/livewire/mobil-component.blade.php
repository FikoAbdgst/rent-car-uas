<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">Data Mobil</h6>
                    @if (auth()->user()->role !== 'pemilik')
                        <button wire:click="create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Mobil
                        </button>
                    @endif
                </div>

                <!-- Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari mobil..."
                                wire:model.live="search">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="disewa">Disewa</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Nama Mobil</th>
                                <th scope="col">Merek</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Plat Nomor</th>
                                <th scope="col">Kapasitas</th>
                                <th scope="col">Transmisi</th>
                                <th scope="col">Harga/Hari</th>
                                <th scope="col">Status</th>
                                @if (auth()->user()->role !== 'pemilik')
                                    <th scope="col">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mobil as $data)
                                <tr>
                                    <th scope="row">
                                        {{ $loop->iteration + ($mobil->currentPage() - 1) * $mobil->perPage() }}</th>
                                    <td>
                                        @if ($data->gambar)
                                            <img src="{{ asset('storage/' . $data->gambar) }}" alt="Gambar Mobil"
                                                class="img-thumbnail"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px; border-radius: 4px;">
                                                <i class="fas fa-car text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $data->namamobil }}</td>
                                    <td>{{ $data->merek }}</td>
                                    <td>{{ $data->tipe }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $data->platnomor }}</td>
                                    <td>{{ $data->formatted_kapasitas }}</td>
                                    <td>{{ $data->formatted_transmisi }}</td>
                                    <td>{{ $data->formatted_harga }}</td>
                                    <td>{!! $data->status_badge !!}</td>
                                    @if (auth()->user()->role !== 'pemilik')
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-info"
                                                    wire:click="edit({{ $data->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="if(confirm('Apakah Anda yakin ingin menghapus mobil {{ $data->namamobil }}?')) { @this.destroy({{ $data->id }}) }">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role !== 'pemilik' ? '12' : '11' }}"
                                        class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Belum ada data mobil</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $mobil->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    @if ($addPage && auth()->user()->role !== 'pemilik')
        @include('mobil.create')
    @endif

    <!-- Modal Edit -->
    @if ($editPage && auth()->user()->role !== 'pemilik')
        @include('mobil.edit')
    @endif


</div>
