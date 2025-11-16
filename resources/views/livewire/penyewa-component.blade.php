<div>
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

                    <h6 class="mb-4">Data Penyewa</h6>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Telepon</th>
                                    <th scope="col">Alamat</th>
                                    @if (auth()->user()->role !== 'pemilik')
                                        <th scope="col">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penyewa as $data)
                                    <tr>
                                        <th scope="row">
                                            {{ $loop->iteration + ($penyewa->currentPage() - 1) * $penyewa->perPage() }}
                                        </th>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->nik }}</td>
                                        <td>{{ $data->telp }}</td>
                                        <td>{{ $data->alamat }}</td>
                                        @if (auth()->user()->role !== 'pemilik')
                                            <td>
                                                <button wire:click="edit({{ $data->id }})"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Edit
                                                </button>
                                                <button
                                                    onclick="confirmDelete({{ $data->id }}, '{{ $data->nama }}')"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role !== 'pemilik' ? '6' : '5' }}"
                                            class="text-center">
                                            <div class="py-4">
                                                <i class="fa fa-inbox fa-2x text-muted"></i>
                                                <p class="mt-2 text-muted">Belum ada data penyewa</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Menampilkan {{ $penyewa->firstItem() ?? 0 }} - {{ $penyewa->lastItem() ?? 0 }}
                            dari {{ $penyewa->total() }} hasil
                        </div>
                        <div>
                            {{ $penyewa->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($editPage && auth()->user()->role !== 'pemilik')
                <div class="col-sm-12 col-xl-12">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-4">Edit Penyewa</h6>
                        <form>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" wire:model="nama" id="nama">
                                @error('nama')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" wire:model="nik" id="nik">
                                @error('nik')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telp" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" wire:model="telp" id="telp">
                                @error('telp')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" wire:model="alamat" id="alamat"></textarea>
                                @error('alamat')
                                    <div class="form-text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="button" wire:click="update" class="btn btn-primary">Simpan</button>
                            <button type="button" wire:click="cancel" class="btn btn-secondary">Batal</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus penyewa "' + nama +
                '"?\n\nData yang sudah dihapus tidak dapat dikembalikan!')) {
            @this.call('destroy', id);
        }
    }
</script>
