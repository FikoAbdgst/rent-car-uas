<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif



                <!-- Data Mobil -->
                <div class="row">
                    @foreach ($mobil as $data)
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm" style="width: 18rem;">
                                <div class="card-header text-center bg-primary text-white">
                                    <strong>{{ $data->merek }} - {{ $data->namamobil }}</strong>
                                </div>
                                <ul class="list-group list-group-flush list-unstyled">
                                    <li>

                                        @if ($data->gambar)
                                            <div class="text-center mt-2">
                                                <img src="{{ asset('storage/' . $data->gambar) }}"
                                                    class="img-fluid rounded" alt="{{ $data->namamobil }}"
                                                    style="max-height: 200px; object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="text-center mt-2">
                                                <i class="fas fa-car fa-3x text-muted"></i>
                                                <p class="mt-2 text-muted">Tidak ada gambar mobil</p>
                                            </div>
                                        @endif
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-car me-2 text-primary"></i>No Polisi:
                                        {{ $data->platnomor }}
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-money-bill-wave me-2 text-success"></i>Harga: Rp
                                        {{ number_format($data->hargasewaperhari, 0, ',', '.') }}/hari
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-users me-2 text-info"></i>Kapasitas: {{ $data->kapasitas }}
                                        penumpang
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-cogs me-2 text-warning"></i>Transmisi:
                                        {{ ucfirst($data->transmisi) }}
                                    </li>
                                    <li class="list-group-item">
                                        <span class="badge bg-success">{{ ucfirst($data->status) }}</span>
                                    </li>
                                </ul>
                                <div class="card-body text-center">
                                    <button wire:click="create({{ $data->id }})"
                                        class="btn btn-outline-success w-100"
                                        {{ $data->status != 'tersedia' ? 'disabled' : '' }}>
                                        <i class="fas fa-calendar-plus me-2"></i>Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($mobil->count() === 0)
                    <div class="text-center py-5">
                        <i class="fas fa-car text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Tidak ada mobil yang tersedia</h5>
                        <p class="text-muted">Silakan periksa kembali nanti</p>
                    </div>
                @endif

                {{ $mobil->links() }}

            </div>
        </div>

        @if ($addPage)
            @include('transaksi.create')
        @endif
    </div>
</div>
