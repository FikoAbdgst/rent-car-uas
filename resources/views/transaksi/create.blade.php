<style>
    .form-floating-modern {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-floating-modern input,
    .form-floating-modern textarea,
    .form-floating-modern select {
        width: 100%;
        padding: 1rem 1rem 1rem 2.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-floating-modern input:focus,
    .form-floating-modern textarea:focus,
    .form-floating-modern select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-floating-modern label {
        position: absolute;
        top: 1rem;
        left: 2.5rem;
        font-size: 1rem;
        color: #6b7280;
        transition: all 0.3s ease;
        pointer-events: none;
        background: white;
        padding: 0 0.5rem;
    }

    .form-floating-modern input:focus+label,
    .form-floating-modern input:not(:placeholder-shown)+label,
    .form-floating-modern textarea:focus+label,
    .form-floating-modern textarea:not(:placeholder-shown)+label,
    .form-floating-modern select:focus+label,
    .form-floating-modern select:not([value=""])+label {
        top: -0.5rem;
        left: 2rem;
        font-size: 0.875rem;
        color: #667eea;
        font-weight: 500;
    }

    .form-icon {
        position: absolute;
        top: 1rem;
        left: 1rem;
        color: #6b7280;
        z-index: 1;
    }

    .price-display {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .btn-gradient {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-outline-gradient {
        background: white;
        border: 2px solid #667eea;
        color: #667eea;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-gradient:hover {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        transform: translateY(-2px);
    }

    .btn-success-gradient {
        background: linear-gradient(45deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
    }

    .btn-success-gradient:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .border-red-500 {
        border-color: #ef4444 !important;
    }

    .text-red-500 {
        color: #ef4444;
    }

    .customer-selection-card {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="bg-white rounded-xl shadow-lg p-6 mt-4">
    <div class="mb-4">
        <h5 class="text-primary"><i class="fas fa-plus-circle me-2"></i>Form Pemesanan Mobil</h5>
        <hr>
    </div>

    <form class="space-y-4" wire:submit.prevent="store">
        <!-- Customer Selection Section -->
        <div class="customer-selection-card">
            <h6 class="text-primary mb-3">
                <i class="fas fa-users me-2"></i>Pilih Identitas Penyewa
            </h6>

            <div class="row g-3">
                <div class="col-md-8">
                    <div class="form-floating-modern">
                        <i class="fas fa-user-friends form-icon"></i>
                        <select wire:model="selectedPenyewa" wire:change="selectPenyewa" id="selectedPenyewa"
                            class="form-select" {{ $showNewCustomerForm ? 'disabled' : '' }}>
                            <option value="">-- Pilih Penyewa yang Sudah Terdaftar --</option>
                            @foreach ($penyewas as $penyewa)
                                <option value="{{ $penyewa->id }}">
                                    {{ $penyewa->nama }} - {{ $penyewa->nik }} ({{ $penyewa->telp }})
                                </option>
                            @endforeach
                        </select>
                        <label for="selectedPenyewa">Penyewa Terdaftar</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <button type="button" wire:click="toggleNewCustomerForm"
                        class="btn {{ $showNewCustomerForm ? 'btn-outline-gradient' : 'btn-success-gradient' }} w-100">
                        @if ($showNewCustomerForm)
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Pilih Penyewa
                        @else
                            <i class="fas fa-user-plus me-2"></i>Tambah Penyewa Baru
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <!-- Customer Information Form -->
        @if ($showNewCustomerForm || $selectedPenyewa)
            <div class="fade-in">
                <div class="row g-4">
                    <!-- Nama Pemesan -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" wire:model="nama" wire:keyup="$refresh" id="nama" placeholder=" "
                                class="@error('nama') border-red-500 @enderror"
                                {{ !$showNewCustomerForm && $selectedPenyewa ? 'readonly' : '' }}>
                            <label for="nama">Nama Pemesan</label>
                            @error('nama')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- NIK -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-id-card form-icon"></i>
                            <input type="text" wire:model="nik" wire:keyup="$refresh" id="nik" placeholder=" "
                                maxlength="16" class="@error('nik') border-red-500 @enderror"
                                {{ !$showNewCustomerForm && $selectedPenyewa ? 'readonly' : '' }}>
                            <label for="nik">NIK (16 digit)</label>
                            @error('nik')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-phone form-icon"></i>
                            <input type="text" wire:model="telp" wire:keyup="$refresh" id="telp" placeholder=" "
                                class="@error('telp') border-red-500 @enderror"
                                {{ !$showNewCustomerForm && $selectedPenyewa ? 'readonly' : '' }}>
                            <label for="telp">Nomor Telepon</label>
                            @error('telp')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-map-marker-alt form-icon"></i>
                            <textarea wire:model="alamat" wire:keyup="$refresh" id="alamat" rows="3" placeholder=" "
                                class="@error('alamat') border-red-500 @enderror"
                                {{ !$showNewCustomerForm && $selectedPenyewa ? 'readonly' : '' }}></textarea>
                            <label for="alamat">Alamat Pemesan</label>
                            @error('alamat')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Rental Information -->
        @if ($showNewCustomerForm || $selectedPenyewa)
            <div class="fade-in">
                <hr class="my-4">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-calendar-alt me-2"></i>Informasi Sewa
                </h6>

                <div class="row g-4">
                    <!-- Tanggal Mulai -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-calendar-alt form-icon"></i>
                            <input type="date" wire:model="tanggalmulai" wire:change="hitung" id="tanggalmulai"
                                min="{{ date('Y-m-d') }}" class="@error('tanggalmulai') border-red-500 @enderror">
                            <label for="tanggalmulai">Tanggal Mulai</label>
                            @error('tanggalmulai')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-calendar-check form-icon"></i>
                            <input type="date" wire:model="tanggalkembali" wire:change="hitung" id="tanggalkembali"
                                min="{{ $tanggalmulai ?? date('Y-m-d') }}"
                                class="@error('tanggalkembali') border-red-500 @enderror">
                            <label for="tanggalkembali">Tanggal Kembali</label>
                            @error('tanggalkembali')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Harga Sewa per Hari (Display Only) -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-money-bill-wave form-icon"></i>
                            <input type="text" value="Rp {{ number_format($harga_sewa ?? 0, 0, ',', '.') }}"
                                readonly class="bg-light">
                            <label>Harga Sewa per Hari</label>
                        </div>
                    </div>

                    <!-- Durasi (Display Only) -->
                    <div class="col-md-6">
                        <div class="form-floating-modern">
                            <i class="fas fa-clock form-icon"></i>
                            <input type="text" value="{{ $durasi_hari }} hari" readonly class="bg-light">
                            <label>Durasi Sewa</label>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="col-12">
                        <div class="form-floating-modern">
                            <i class="fas fa-sticky-note form-icon"></i>
                            <textarea wire:model="catatan" id="catatan" rows="3" placeholder=" " maxlength="500"
                                class="@error('catatan') border-red-500 @enderror"></textarea>
                            <label for="catatan">Catatan (Opsional)</label>
                            @error('catatan')
                                <div class="text-red-500 text-sm mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Total Harga Display -->
                    <div class="col-12">
                        <div class="price-display">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    <span class="font-semibold">Total Harga:</span>
                                </div>
                                <div class="text-2xl font-bold">
                                    Rp {{ number_format($totalharga ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            @if ($tanggalmulai && $tanggalkembali)
                                <div class="mt-2 text-sm opacity-90">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Durasi: {{ $durasi_hari }} hari
                                    × Rp {{ number_format($harga_sewa ?? 0, 0, ',', '.') }}
                                    <br>
                                    <small>{{ \Carbon\Carbon::parse($tanggalmulai)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($tanggalkembali)->format('d M Y') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12">
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="button" wire:click="cancelAdd" class="btn btn-outline-gradient">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-gradient" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-save me-2"></i>Simpan Transaksi
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-format phone number
        const phoneInput = document.getElementById('telp');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Skip formatting if readonly
                if (e.target.readOnly) return;

                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }
                if (value.length > 0) {
                    value = '0' + value;
                }
                if (value.length > 15) {
                    value = value.slice(0, 15);
                }
                e.target.value = value;
            });
        }

        // Auto-format NIK (only numbers)
        const nikInput = document.getElementById('nik');
        if (nikInput) {
            nikInput.addEventListener('input', function(e) {
                // Skip formatting if readonly
                if (e.target.readOnly) return;

                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.slice(0, 16);
                }
                e.target.value = value;
            });
        }

        // Update min date for tanggal kembali when tanggal mulai changes
        const tanggalMulai = document.getElementById('tanggalmulai');
        const tanggalKembali = document.getElementById('tanggalkembali');

        if (tanggalMulai && tanggalKembali) {
            tanggalMulai.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                // Tanggal kembali minimal adalah tanggal mulai + 1 hari
                const nextDay = new Date(selectedDate);
                nextDay.setDate(nextDay.getDate() + 1);
                tanggalKembali.min = nextDay.toISOString().split('T')[0];

                // Jika tanggal kembali sudah dipilih tapi lebih kecil dari tanggal mulai, reset
                if (tanggalKembali.value && tanggalKembali.value <= this.value) {
                    tanggalKembali.value = nextDay.toISOString().split('T')[0];
                    // Trigger Livewire update
                    tanggalKembali.dispatchEvent(new Event('change'));
                }
            });
        }
    });

    // Livewire event listener for form updates
    window.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', (message, component) => {
            // Auto scroll to top when there's an error or success message
            if (component.fingerprint.name === 'transaksi-component') {
                const alerts = document.querySelectorAll('.alert');
                if (alerts.length > 0) {
                    alerts[0].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    });
</script>
