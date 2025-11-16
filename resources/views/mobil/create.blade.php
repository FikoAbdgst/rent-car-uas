        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Mobil Baru</h5>
                        <button type="button" class="btn-close" wire:click="cancel"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="store">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="namamobil" class="form-label">Nama Mobil <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('namamobil') is-invalid @enderror"
                                            wire:model="namamobil" id="namamobil" placeholder="Contoh: Avanza Veloz">
                                        @error('namamobil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="merek" class="form-label">Merek <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('merek') is-invalid @enderror"
                                            wire:model="merek" id="merek" placeholder="Contoh: Toyota">
                                        @error('merek')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipe" class="form-label">Tipe <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('tipe') is-invalid @enderror"
                                            wire:model="tipe" id="tipe">
                                            <option value="">--Pilih Tipe--</option>
                                            <option value="Sedan">Sedan</option>
                                            <option value="MPV">MPV</option>
                                            <option value="SUV">SUV</option>
                                            <option value="Hatchback">Hatchback</option>
                                            <option value="Pickup">Pickup</option>
                                            <option value="Minibus">Minibus</option>
                                        </select>
                                        @error('tipe')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tahun" class="form-label">Tahun <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                            wire:model="tahun" id="tahun" min="1900" max="2030"
                                            placeholder="2023">
                                        @error('tahun')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platnomor" class="form-label">Plat Nomor <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('platnomor') is-invalid @enderror"
                                            wire:model="platnomor" id="platnomor" placeholder="B 1234 ABC"
                                            style="text-transform: uppercase;">
                                        @error('platnomor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hargasewaperhari" class="form-label">Harga Sewa per Hari <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number"
                                                class="form-control @error('hargasewaperhari') is-invalid @enderror"
                                                wire:model="hargasewaperhari" id="hargasewaperhari" min="0"
                                                placeholder="300000">
                                        </div>
                                        @error('hargasewaperhari')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas" class="form-label">Kapasitas (Penumpang) <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('kapasitas') is-invalid @enderror"
                                            wire:model="kapasitas" id="kapasitas" min="1"
                                            placeholder="Contoh: 7">
                                        @error('kapasitas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="transmisi" class="form-label">Transmisi <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('transmisi') is-invalid @enderror"
                                            wire:model="transmisi" id="transmisi">
                                            <option value="">--Pilih Transmisi--</option>
                                            <option value="manual">Manual</option>
                                            <option value="automatic">Automatic</option>
                                        </select>
                                        @error('transmisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror"
                                            wire:model="status" id="status">
                                            <option value="">--Pilih Status--</option>
                                            <option value="tersedia">Tersedia</option>
                                            <option value="disewa">Disewa</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="newImage" class="form-label">Gambar Mobil</label>
                                        <input type="file"
                                            class="form-control @error('newImage') is-invalid @enderror"
                                            wire:model="newImage" id="newImage" accept="image/*">
                                        @error('newImage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Image -->
                            @if ($newImage)
                                <div class="mb-3">
                                    <label class="form-label">Preview Gambar:</label>
                                    <div>
                                        <img src="{{ $newImage->temporaryUrl() }}" class="img-thumbnail"
                                            style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2"
                                    wire:click="cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove>Simpan</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
