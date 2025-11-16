<?php

namespace App\Livewire;

use App\Models\Mobil;
use App\Models\LogActivity;
use Livewire\Component;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Storage;

class MobilComponent extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $addPage = false;
    public $editPage = false;
    public $search = '';
    public $filterStatus = '';

    // Properties sesuai dengan tabel
    public $namamobil, $merek, $tipe, $tahun, $platnomor, $hargasewaperhari, $status, $gambar;
    public $kapasitas, $transmisi; // Field baru
    public $id;

    // Untuk file upload
    public $newImage;
    public $currentImage;

    protected $rules = [
        'namamobil' => 'required|string|max:255',
        'merek' => 'required|string|max:255',
        'tipe' => 'required|string|max:255',
        'tahun' => 'required|integer|min:1900|max:2030',
        'platnomor' => 'required|string|max:20|unique:mobils,platnomor',
        'hargasewaperhari' => 'required|numeric|min:0',
        'status' => 'required|in:tersedia,disewa,maintenance',
        'kapasitas' => 'required|integer|min:1',
        'transmisi' => 'required|in:manual,automatic',
        'newImage' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'namamobil.required' => 'Nama mobil tidak boleh kosong',
        'merek.required' => 'Merek tidak boleh kosong',
        'tipe.required' => 'Tipe tidak boleh kosong',
        'tahun.required' => 'Tahun tidak boleh kosong',
        'tahun.integer' => 'Tahun harus berupa angka',
        'tahun.min' => 'Tahun minimal 1900',
        'tahun.max' => 'Tahun maksimal 2030',
        'platnomor.required' => 'Nomor plat tidak boleh kosong',
        'platnomor.unique' => 'Nomor plat sudah digunakan',
        'hargasewaperhari.required' => 'Harga sewa per hari tidak boleh kosong',
        'hargasewaperhari.numeric' => 'Harga sewa harus berupa angka',
        'status.required' => 'Status tidak boleh kosong',
        'status.in' => 'Status harus tersedia, disewa, atau maintenance',
        'kapasitas.required' => 'Kapasitas tidak boleh kosong',
        'kapasitas.integer' => 'Kapasitas harus berupa angka',
        'kapasitas.min' => 'Kapasitas minimal 1 penumpang',
        'transmisi.required' => 'Transmisi tidak boleh kosong',
        'transmisi.in' => 'Transmisi harus manual atau automatic',
        'newImage.image' => 'File harus berupa gambar',
        'newImage.max' => 'Ukuran gambar maksimal 2MB',
    ];

    public function render()
    {
        $query = Mobil::query();

        // Search functionality
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('namamobil', 'like', '%' . $this->search . '%')
                    ->orWhere('merek', 'like', '%' . $this->search . '%')
                    ->orWhere('platnomor', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $data['mobil'] = $query->latest()->paginate(10);

        return view('livewire.mobil-component', $data);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset([
            'namamobil',
            'merek',
            'tipe',
            'tahun',
            'platnomor',
            'hargasewaperhari',
            'status',
            'gambar',
            'newImage',
            'currentImage',
            'kapasitas',
            'transmisi',
        ]);
        $this->addPage = true;
        $this->editPage = false;
    }

    public function store()
    {
        $this->validate();

        // Handle image upload
        $imagePath = null;
        if ($this->newImage) {
            $imagePath = $this->newImage->store('mobil-images', 'public');
        }

        $mobil = Mobil::create([
            'user_id' => Auth::user()->id,
            'namamobil' => $this->namamobil,
            'merek' => $this->merek,
            'tipe' => $this->tipe,
            'tahun' => $this->tahun,
            'platnomor' => $this->platnomor,
            'hargasewaperhari' => $this->hargasewaperhari,
            'status' => $this->status,
            'gambar' => $imagePath,
            'kapasitas' => $this->kapasitas,
            'transmisi' => $this->transmisi,
        ]);

        // Log activity untuk CREATE
        LogActivity::createLog(
            'CREATE',
            'mobils',
            $mobil->id,
            'Menambahkan mobil baru: ' . $this->namamobil . ' (' . $this->platnomor . ')',
            null,
            $mobil->toArray()
        );

        session()->flash('success', 'Data mobil berhasil disimpan');
        $this->reset();
        $this->addPage = false;
    }

    public function edit($id)
    {
        $this->editPage = true;
        $this->addPage = false;
        $this->id = $id;

        $mobil = Mobil::find($id);
        $this->namamobil = $mobil->namamobil;
        $this->merek = $mobil->merek;
        $this->tipe = $mobil->tipe;
        $this->tahun = $mobil->tahun;
        $this->platnomor = $mobil->platnomor;
        $this->hargasewaperhari = $mobil->hargasewaperhari;
        $this->status = $mobil->status;
        $this->gambar = $mobil->gambar;
        $this->currentImage = $mobil->gambar;
        $this->kapasitas = $mobil->kapasitas;
        $this->transmisi = $mobil->transmisi;
    }

    public function update()
    {
        $rules = $this->rules;
        $rules['platnomor'] = 'required|string|max:20|unique:mobils,platnomor,' . $this->id;

        $this->validate($rules);

        $mobil = Mobil::find($this->id);

        // Simpan data lama untuk log
        $oldData = $mobil->toArray();

        // Handle image upload
        $imagePath = $this->currentImage;
        if ($this->newImage) {
            // Delete old image if exists
            if ($this->currentImage) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $imagePath = $this->newImage->store('mobil-images', 'public');
        }

        $mobil->update([
            'namamobil' => $this->namamobil,
            'merek' => $this->merek,
            'tipe' => $this->tipe,
            'tahun' => $this->tahun,
            'platnomor' => $this->platnomor,
            'hargasewaperhari' => $this->hargasewaperhari,
            'status' => $this->status,
            'gambar' => $imagePath,
            'kapasitas' => $this->kapasitas,
            'transmisi' => $this->transmisi,
        ]);

        // Log activity untuk UPDATE
        LogActivity::createLog(
            'UPDATE',
            'mobils',
            $mobil->id,
            'Mengubah data mobil: ' . $this->namamobil . ' (' . $this->platnomor . ')',
            $oldData,
            $mobil->toArray()
        );

        session()->flash('success', 'Data mobil berhasil diubah');
        $this->reset();
        $this->editPage = false;
    }

    public function destroy($id)
    {
        $mobil = Mobil::find($id);

        // Simpan data untuk log sebelum dihapus
        $oldData = $mobil->toArray();

        // Delete image if exists
        if ($mobil->gambar) {
            Storage::disk('public')->delete($mobil->gambar);
        }

        $mobil->delete();

        // Log activity untuk DELETE
        LogActivity::createLog(
            'DELETE',
            'mobils',
            $id,
            'Menghapus mobil: ' . $oldData['namamobil'] . ' (' . $oldData['platnomor'] . ')',
            $oldData,
            null
        );

        session()->flash('success', 'Data mobil berhasil dihapus');
    }

    public function cancel()
    {
        $this->reset();
        $this->addPage = false;
        $this->editPage = false;
    }
}
