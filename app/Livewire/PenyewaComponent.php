<?php

namespace App\Livewire;

use App\Models\Penyewa;
use App\Models\LogActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class PenyewaComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $editPage = false;
    public $nama, $nik, $telp, $alamat, $id;

    protected $rules = [
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|max:16|unique:penyewas,nik',
        'telp' => 'required|string|max:15',
        'alamat' => 'required|string',
    ];

    protected $messages = [
        'nama.required' => 'Nama tidak boleh kosong',
        'nik.required' => 'NIK tidak boleh kosong',
        'nik.unique' => 'NIK sudah terdaftar',
        'nik.max' => 'NIK maksimal 16 karakter',
        'telp.required' => 'Nomor telepon tidak boleh kosong',
        'telp.max' => 'Nomor telepon maksimal 15 karakter',
        'alamat.required' => 'Alamat tidak boleh kosong',
    ];

    public function render()
    {
        $data['penyewa'] = Penyewa::latest()->paginate(10);
        return view('livewire.penyewa-component', $data);
    }

    public function edit($id)
    {
        $penyewa = Penyewa::findOrFail($id);
        $this->id = $penyewa->id;
        $this->nama = $penyewa->nama;
        $this->nik = $penyewa->nik;
        $this->telp = $penyewa->telp;
        $this->alamat = $penyewa->alamat;
        $this->editPage = true;
    }

    public function update()
    {
        $this->rules['nik'] = 'required|string|max:16|unique:penyewas,nik,' . $this->id;
        $this->validate();

        $penyewa = Penyewa::findOrFail($this->id);

        // Simpan data lama untuk log
        $oldData = $penyewa->toArray();

        $penyewa->update([
            'nama' => $this->nama,
            'nik' => $this->nik,
            'telp' => $this->telp,
            'alamat' => $this->alamat,
        ]);

        // Log activity untuk UPDATE
        LogActivity::createLog(
            'UPDATE',
            'penyewas',
            $penyewa->id,
            'Mengubah data penyewa: ' . $this->nama . ' (' . $this->nik . ')',
            $oldData,
            $penyewa->toArray()
        );

        session()->flash('success', 'Data penyewa berhasil diubah');
        $this->reset();
        $this->editPage = false;
    }

    public function destroy($id)
    {
        $penyewa = Penyewa::findOrFail($id);

        // Cek apakah penyewa memiliki transaksi (tanpa soft delete check)
        $transaksiCount = $penyewa->transaksis()->count();

        if ($transaksiCount > 0) {
            session()->flash('error', 'Penyewa tidak dapat dihapus karena memiliki ' . $transaksiCount . ' transaksi terkait. Hapus transaksi terlebih dahulu.');
            return;
        }

        // Simpan data untuk log sebelum dihapus
        $oldData = $penyewa->toArray();

        // Hard delete - langsung hapus dari database
        $penyewa->forceDelete();

        // Log activity untuk DELETE
        LogActivity::createLog(
            'DELETE',
            'penyewas',
            $id,
            'Menghapus penyewa: ' . $oldData['nama'] . ' (' . $oldData['nik'] . ')',
            $oldData,
            null
        );

        session()->flash('success', 'Data penyewa berhasil dihapus');
    }

    public function cancel()
    {
        $this->reset();
        $this->editPage = false;
    }
}
