<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\LogActivity;
use App\Models\Mobil;
use App\Models\Penyewa;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class UnifiedTransaksi extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    public $statusFilter = 'ALL';
    public $tanggal1, $tanggal2;

    // Properties untuk modal edit
    public $showEditModal = false;
    public $editTransaksiId;
    public $editMobilId;
    public $editPenyewaId;
    public $editTanggalMulai;
    public $editTanggalKembali;
    public $editTotalHarga;
    public $editCatatan;
    public $editStatus;

    // Property untuk modal delete
    public $showDeleteModal = false;
    public $deleteTransaksiId;
    public $deleteTransaksiInfo;

    // PERUBAHAN VALIDASI: Menggunakan after_or_equal untuk memungkinkan tanggal yang sama
    protected $rules = [
        'editMobilId' => 'required|exists:mobils,id',
        'editPenyewaId' => 'required|exists:penyewas,id',
        'editTanggalMulai' => 'required|date',
        'editTanggalKembali' => 'required|date|after_or_equal:editTanggalMulai', // Memungkinkan tanggal yang sama
        'editTotalHarga' => 'required|numeric|min:0',
        'editStatus' => 'required|in:WAIT,PROSES,SELESAI',
    ];

    // PERUBAHAN PESAN VALIDASI: Menjelaskan bahwa tanggal kembali bisa sama dengan tanggal mulai
    protected $messages = [
        'editMobilId.required' => 'Mobil harus dipilih',
        'editMobilId.exists' => 'Mobil yang dipilih tidak valid',
        'editPenyewaId.required' => 'Penyewa harus dipilih',
        'editPenyewaId.exists' => 'Penyewa yang dipilih tidak valid',
        'editTanggalMulai.required' => 'Tanggal mulai harus diisi',
        'editTanggalKembali.required' => 'Tanggal kembali harus diisi',
        'editTanggalKembali.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal mulai (minimal 1 hari)', // Pesan yang lebih jelas
        'editTotalHarga.required' => 'Total harga harus diisi',
        'editTotalHarga.numeric' => 'Total harga harus berupa angka',
        'editTotalHarga.min' => 'Total harga tidak boleh kurang dari 0',
        'editStatus.required' => 'Status harus dipilih',
        'editStatus.in' => 'Status yang dipilih tidak valid',
    ];

    // TAMBAHAN: Fungsi untuk mengecek konflik jadwal (opsional untuk validasi lebih ketat)
    public function checkScheduleConflict($mobilId, $tanggalMulai, $tanggalKembali, $excludeTransaksiId = null)
    {
        $query = Transaksi::where('idmobil', $mobilId)
            ->where('status', '!=', 'SELESAI')
            ->where(function ($q) use ($tanggalMulai, $tanggalKembali) {
                $q->whereBetween('tanggalmulai', [$tanggalMulai, $tanggalKembali])
                    ->orWhereBetween('tanggalkembali', [$tanggalMulai, $tanggalKembali])
                    ->orWhere(function ($subQuery) use ($tanggalMulai, $tanggalKembali) {
                        $subQuery->where('tanggalmulai', '<=', $tanggalMulai)
                            ->where('tanggalkembali', '>=', $tanggalKembali);
                    });
            });

        if ($excludeTransaksiId) {
            $query->where('id', '!=', $excludeTransaksiId);
        }

        return $query->exists();
    }

    // TAMBAHAN: Validasi custom untuk cek konflik jadwal
    public function validateSchedule()
    {
        if ($this->editMobilId && $this->editTanggalMulai && $this->editTanggalKembali) {
            $hasConflict = $this->checkScheduleConflict(
                $this->editMobilId,
                $this->editTanggalMulai,
                $this->editTanggalKembali,
                $this->editTransaksiId
            );

            if ($hasConflict) {
                $this->addError('editTanggalMulai', 'Mobil sudah dipesan pada periode tanggal tersebut');
                $this->addError('editTanggalKembali', 'Mobil sudah dipesan pada periode tanggal tersebut');
                return false;
            }
        }
        return true;
    }

    public function render()
    {
        $query = Transaksi::with(['mobil', 'penyewa', 'user'])
            ->whereHas('mobil')
            ->whereHas('penyewa')
            ->orderBy('id', 'desc');

        if ($this->statusFilter !== 'ALL') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->tanggal1 && $this->tanggal2) {
            $query->whereBetween('tanggalmulai', [$this->tanggal1, $this->tanggal2]);
        }

        $transaksi = $query->paginate(5);

        $mobils = Mobil::all();
        $penyewas = Penyewa::all();

        return view('livewire.unified-transaksi', [
            'transaksi' => $transaksi,
            'mobils' => $mobils,
            'penyewas' => $penyewas
        ]);
    }

    public function filterByStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function cari()
    {
        $this->resetPage();
    }

    public function clearFilter()
    {
        $this->statusFilter = 'ALL';
        $this->tanggal1 = null;
        $this->tanggal2 = null;
        $this->resetPage();
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $this->editTransaksiId = $transaksi->id;
        $this->editMobilId = $transaksi->idmobil;
        $this->editPenyewaId = $transaksi->idpenyewa;
        $this->editTanggalMulai = $transaksi->tanggalmulai;
        $this->editTanggalKembali = $transaksi->tanggalkembali;
        $this->editTotalHarga = $transaksi->totalharga;
        $this->editStatus = $transaksi->status;
        $this->editCatatan = $transaksi->catatan;

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetEditForm();
        $this->resetErrorBag();
    }

    public function resetEditForm()
    {
        $this->editTransaksiId = null;
        $this->editMobilId = null;
        $this->editPenyewaId = null;
        $this->editTanggalMulai = null;
        $this->editTanggalKembali = null;
        $this->editTotalHarga = null;
        $this->editCatatan = null;
        $this->editStatus = null;
    }

    // MODIFIKASI: Tambahkan validasi schedule conflict
    public function updateTransaksi()
    {
        $this->validate();

        // Validasi tambahan untuk konflik jadwal
        if (!$this->validateSchedule()) {
            return;
        }

        $transaksi = Transaksi::find($this->editTransaksiId);

        if (!$transaksi) {
            session()->flash('error', 'Transaksi tidak ditemukan');
            $this->closeEditModal();
            return;
        }

        $oldData = $transaksi->toArray();

        $transaksi->update([
            'idmobil' => $this->editMobilId,
            'idpenyewa' => $this->editPenyewaId,
            'tanggalmulai' => $this->editTanggalMulai,
            'tanggalkembali' => $this->editTanggalKembali,
            'totalharga' => $this->editTotalHarga,
            'catatan' => $this->editCatatan,
            'status' => $this->editStatus,
        ]);

        LogActivity::createLog(
            'UPDATE',
            'transaksis',
            $transaksi->id,
            'Mengupdate data transaksi ID: ' . $transaksi->id,
            $oldData,
            $transaksi->toArray()
        );

        session()->flash('success', 'Transaksi berhasil diupdate');
        $this->closeEditModal();
    }

    public function openDeleteModal($id)
    {
        $transaksi = Transaksi::with(['mobil', 'penyewa'])->find($id);

        if (!$transaksi) {
            session()->flash('error', 'Transaksi tidak ditemukan');
            return;
        }

        $this->deleteTransaksiId = $transaksi->id;
        $this->deleteTransaksiInfo = [
            'mobil' => $transaksi->mobil->namamobil . ' (' . $transaksi->mobil->platnomor . ')',
            'penyewa' => $transaksi->penyewa->nama,
            'tanggal' => date('d/m/Y', strtotime($transaksi->tanggalmulai)) . ' - ' . date('d/m/Y', strtotime($transaksi->tanggalkembali)),
            'total' => 'Rp ' . number_format($transaksi->totalharga, 0, ',', '.')
        ];

        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteTransaksiId = null;
        $this->deleteTransaksiInfo = null;
    }

    public function deleteTransaksi()
    {
        $transaksi = Transaksi::with(['mobil'])->find($this->deleteTransaksiId);

        if (!$transaksi) {
            session()->flash('error', 'Transaksi tidak ditemukan');
            $this->closeDeleteModal();
            return;
        }

        $dataForLog = $transaksi->toArray();

        if ($transaksi->status !== 'SELESAI' && $transaksi->mobil) {
            $mobil = $transaksi->mobil;
            $oldMobilData = $mobil->toArray();
            $mobil->update(['status' => 'tersedia']);

            LogActivity::createLog(
                'UPDATE',
                'mobils',
                $mobil->id,
                'Mengubah status mobil menjadi tersedia karena transaksi dihapus: ' . $mobil->namamobil . ' (' . $mobil->platnomor . ')',
                $oldMobilData,
                $mobil->toArray()
            );
        }

        $transaksi->forceDelete();

        LogActivity::createLog(
            'DELETE',
            'transaksis',
            $this->deleteTransaksiId,
            'Menghapus transaksi ID: ' . $this->deleteTransaksiId,
            $dataForLog,
            null
        );

        session()->flash('success', 'Transaksi berhasil dihapus');
        $this->closeDeleteModal();
    }

    public function proses($id)
    {
        $transaksi = Transaksi::with(['mobil', 'penyewa'])->find($id);

        if (!$transaksi) {
            session()->flash('error', 'Transaksi tidak ditemukan');
            return;
        }

        if (!$transaksi->mobil) {
            session()->flash('error', 'Data mobil tidak ditemukan untuk transaksi ini');
            return;
        }

        $oldData = $transaksi->toArray();

        $transaksi->update(['status' => 'PROSES']);

        LogActivity::createLog(
            'UPDATE',
            'transaksis',
            $transaksi->id,
            'Mengubah status transaksi menjadi PROSES untuk transaksi ID: ' . $transaksi->id,
            $oldData,
            $transaksi->toArray()
        );

        session()->flash('success', 'Transaksi berhasil diproses');
    }

    public function selesai($id)
    {
        $transaksi = Transaksi::with(['mobil', 'penyewa'])->find($id);

        if (!$transaksi) {
            session()->flash('error', 'Transaksi tidak ditemukan');
            return;
        }

        if (!$transaksi->mobil) {
            session()->flash('error', 'Data mobil tidak ditemukan untuk transaksi ini');
            return;
        }

        $oldData = $transaksi->toArray();

        $transaksi->update([
            'status' => 'SELESAI',
            'tanggaldikembalikan' => now(),
        ]);

        $mobil = $transaksi->mobil;
        $oldMobilData = $mobil->toArray();
        $mobil->update(['status' => 'tersedia']);

        LogActivity::createLog(
            'UPDATE',
            'transaksis',
            $transaksi->id,
            'Menyelesaikan transaksi ID: ' . $transaksi->id,
            $oldData,
            $transaksi->toArray()
        );

        LogActivity::createLog(
            'UPDATE',
            'mobils',
            $mobil->id,
            'Mengubah status mobil menjadi tersedia: ' . $mobil->namamobil . ' (' . $mobil->platnomor . ')',
            $oldMobilData,
            $mobil->toArray()
        );

        session()->flash('success', 'Transaksi berhasil diselesaikan');
    }

    public function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'WAIT':
                return 'badge bg-warning text-dark';
            case 'PROSES':
                return 'badge bg-info text-white';
            case 'SELESAI':
                return 'badge bg-success text-white';
            default:
                return 'badge bg-secondary text-white';
        }
    }

    public function getStatusCount($status)
    {
        return Transaksi::whereHas('mobil')
            ->whereHas('penyewa')
            ->where('status', $status)
            ->count();
    }

    public function getAllTransaksiCount()
    {
        return Transaksi::whereHas('mobil')
            ->whereHas('penyewa')
            ->count();
    }
}
