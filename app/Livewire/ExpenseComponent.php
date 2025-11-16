<?php

namespace App\Livewire;

use App\Models\Expense;
use App\Models\Mobil;
use App\Models\LogActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ExpenseComponent extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $addPage = false;
    public $editPage = false;
    public $search = '';
    public $filterKategori = '';
    public $filterStatus = '';
    public $filterMonth = '';
    public $filterYear = '';

    // Properties untuk form
    public $mobil_id, $kategori, $deskripsi;
    public $jumlah, $tanggal, $metode_pembayaran, $nomor_referensi;
    public $status, $bukti_pembayaran, $catatan;
    public $id;

    // Untuk file upload
    public $newBukti;
    public $currentBukti;

    // Data untuk dropdown
    public $kategoris = [
        'fuel' => 'Bahan Bakar',
        'maintenance' => 'Perawatan/Service',
        'repair' => 'Perbaikan',
        'insurance' => 'Asuransi',
        'tax' => 'Pajak',
        'cleaning' => 'Cuci Mobil',
        'tire' => 'Ban',
        'spare_parts' => 'Suku Cadang',
        'license' => 'STNK/KIR',
        'parking' => 'Parkir',
        'toll' => 'Tol',
        'operational' => 'Operasional Lainnya',
        'other' => 'Lainnya'
    ];

    public $metodePembayaran = [
        'cash' => 'Cash',
        'transfer' => 'Transfer Bank',
        'credit_card' => 'Kartu Kredit',
        'debit_card' => 'Kartu Debit',
        'e_wallet' => 'E-Wallet'
    ];

    // Status options
    public $statusOptions = [
        'wait' => 'Menunggu',
        'proses' => 'Dalam Proses',
        'selesai' => 'Selesai'
    ];

    protected $rules = [
        'kategori' => 'required|string',
        'deskripsi' => 'required|string|max:1000',
        'jumlah' => 'required|numeric|min:0',
        'tanggal' => 'required|date',
        'metode_pembayaran' => 'nullable|string',
        'nomor_referensi' => 'nullable|string|max:100',
        'status' => 'required|string',
        'catatan' => 'nullable|string|max:500',
        'newBukti' => 'nullable|image|max:2048',
        'mobil_id' => 'nullable|exists:mobils,id'
    ];

    protected $messages = [
        'kategori.required' => 'Kategori tidak boleh kosong',
        'deskripsi.required' => 'Deskripsi tidak boleh kosong',
        'jumlah.required' => 'Jumlah tidak boleh kosong',
        'jumlah.numeric' => 'Jumlah harus berupa angka',
        'jumlah.min' => 'Jumlah harus lebih dari 0',
        'tanggal.required' => 'Tanggal tidak boleh kosong',
        'status.required' => 'Status tidak boleh kosong',
        'newBukti.image' => 'File harus berupa gambar',
        'newBukti.max' => 'Ukuran file maksimal 2MB',
    ];

    public function mount()
    {
        $this->filterYear = date('Y');
        $this->filterMonth = date('m');
    }

    public function render()
    {
        $query = Expense::with(['mobil', 'user']);

        // Search functionality
        if ($this->search) {
            $query->where('deskripsi', 'like', '%' . $this->search . '%')
                ->orWhere('nomor_referensi', 'like', '%' . $this->search . '%');
        }

        // Filter by kategori
        if ($this->filterKategori) {
            $query->where('kategori', $this->filterKategori);
        }

        // Filter by status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Filter by month and year
        if ($this->filterYear) {
            $query->whereYear('tanggal', $this->filterYear);
        }
        if ($this->filterMonth) {
            $query->whereMonth('tanggal', $this->filterMonth);
        }

        $data['expenses'] = $query->latest('tanggal')->paginate(10);
        $data['mobils'] = Mobil::orderBy('namamobil')->get();

        // Summary data
        $data['totalExpense'] = $query->sum('jumlah');
        $data['totalWait'] = Expense::where('status', 'wait')->sum('jumlah');
        $data['totalProses'] = Expense::where('status', 'proses')->sum('jumlah');
        $data['totalSelesai'] = Expense::where('status', 'selesai')->sum('jumlah');

        return view('livewire.expense-component', $data);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterMonth()
    {
        $this->resetPage();
    }

    public function updatingFilterYear()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset([
            'mobil_id',
            'kategori',
            'deskripsi',
            'jumlah',
            'tanggal',
            'metode_pembayaran',
            'nomor_referensi',
            'status',
            'bukti_pembayaran',
            'catatan',
            'newBukti',
            'currentBukti'
        ]);
        $this->tanggal = date('Y-m-d');
        $this->status = 'wait';
        $this->addPage = true;
        $this->editPage = false;
    }

    public function store()
    {
        $this->validate();

        // Handle file upload
        $buktiPath = null;
        if ($this->newBukti) {
            $buktiPath = $this->newBukti->store('expense-bukti', 'public');
        }

        try {
            $expense = Expense::create([
                'user_id' => Auth::id(),
                'mobil_id' => $this->mobil_id,
                'kategori' => $this->kategori,
                'deskripsi' => $this->deskripsi,
                'jumlah' => $this->jumlah,
                'tanggal' => $this->tanggal,
                'metode_pembayaran' => $this->metode_pembayaran,
                'nomor_referensi' => $this->nomor_referensi,
                'status' => $this->status,
                'bukti_pembayaran' => $buktiPath,
                'catatan' => $this->catatan,
            ]);

            // Log activity
            LogActivity::createLog(
                'CREATE',
                'expenses',
                $expense->id,
                'Menambahkan expense baru: ' . $this->deskripsi . ' - Rp ' . number_format($this->jumlah, 0, ',', '.'),
                null,
                $expense->toArray()
            );

            session()->flash('success', 'Data expense berhasil disimpan');
            $this->closeForm();
        } catch (\Exception $e) {
            Log::error('Error saving expense: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->editPage = true;
        $this->addPage = false;
        $this->id = $id;

        $expense = Expense::find($id);
        $this->mobil_id = $expense->mobil_id;
        $this->kategori = $expense->kategori;
        $this->deskripsi = $expense->deskripsi;
        $this->jumlah = $expense->jumlah;
        $this->tanggal = $expense->tanggal->format('Y-m-d');
        $this->metode_pembayaran = $expense->metode_pembayaran;
        $this->nomor_referensi = $expense->nomor_referensi;
        $this->status = $expense->status;
        $this->bukti_pembayaran = $expense->bukti_pembayaran;
        $this->currentBukti = $expense->bukti_pembayaran;
        $this->catatan = $expense->catatan;
    }

    public function update()
    {
        $this->validate();

        $expense = Expense::find($this->id);
        $oldData = $expense->toArray();
        $oldStatus = $expense->status;

        // Handle file upload
        $buktiPath = $this->currentBukti;
        if ($this->newBukti) {
            // Delete old file if exists
            if ($this->currentBukti) {
                Storage::disk('public')->delete($this->currentBukti);
            }
            $buktiPath = $this->newBukti->store('expense-bukti', 'public');
        }

        $expense->update([
            'mobil_id' => $this->mobil_id,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'jumlah' => $this->jumlah,
            'tanggal' => $this->tanggal,
            'metode_pembayaran' => $this->metode_pembayaran,
            'nomor_referensi' => $this->nomor_referensi,
            'status' => $this->status,
            'bukti_pembayaran' => $buktiPath,
            'catatan' => $this->catatan,
        ]);

        // Handle mobil status change based on expense status
        $this->handleMobilStatusChange($expense, $oldStatus, $this->status);

        // Log activity
        LogActivity::createLog(
            'UPDATE',
            'expenses',
            $expense->id,
            'Mengubah data expense: ' . $this->deskripsi . ' - Rp ' . number_format($this->jumlah, 0, ',', '.'),
            $oldData,
            $expense->toArray()
        );

        session()->flash('success', 'Data expense berhasil diubah');
        $this->closeForm();
    }

    public function prosesExpense($id)
    {
        $expense = Expense::find($id);
        $oldStatus = $expense->status;

        $expense->update(['status' => 'proses']);

        // Handle mobil status change
        $this->handleMobilStatusChange($expense, $oldStatus, 'proses');

        // Log activity
        LogActivity::createLog(
            'UPDATE',
            'expenses',
            $expense->id,
            'Mengubah status expense ke PROSES: ' . $expense->deskripsi,
            ['status' => $oldStatus],
            ['status' => 'proses']
        );

        session()->flash('success', 'Expense berhasil diubah ke status proses');
    }

    public function selesaiExpense($id)
    {
        $expense = Expense::find($id);
        $oldStatus = $expense->status;

        $expense->update(['status' => 'selesai']);

        // Handle mobil status change
        $this->handleMobilStatusChange($expense, $oldStatus, 'selesai');

        // Log activity
        LogActivity::createLog(
            'UPDATE',
            'expenses',
            $expense->id,
            'Mengubah status expense ke SELESAI: ' . $expense->deskripsi,
            ['status' => $oldStatus],
            ['status' => 'selesai']
        );

        session()->flash('success', 'Expense berhasil diselesaikan');
    }

    private function handleMobilStatusChange($expense, $oldStatus, $newStatus)
    {
        // Hanya proses jika expense terkait dengan mobil
        if (!$expense->mobil_id) {
            return;
        }

        $mobil = Mobil::find($expense->mobil_id);
        if (!$mobil) {
            return;
        }

        // Jika status berubah ke 'proses' dan kategori terkait maintenance
        if ($newStatus === 'proses' && $oldStatus !== 'proses') {
            $maintenanceCategories = ['maintenance', 'repair', 'spare_parts', 'tire'];

            if (in_array($expense->kategori, $maintenanceCategories)) {
                $mobil->update(['status' => 'maintenance']);

                // Log perubahan status mobil
                LogActivity::createLog(
                    'UPDATE',
                    'mobils',
                    $mobil->id,
                    'Mobil ' . $mobil->namamobil . ' diubah status ke MAINTENANCE karena expense: ' . $expense->deskripsi,
                    ['status' => $mobil->getOriginal('status')],
                    ['status' => 'maintenance']
                );
            }
        }

        // Jika status berubah ke 'selesai', kembalikan status mobil ke 'tersedia'
        if ($newStatus === 'selesai' && $oldStatus !== 'selesai') {
            if ($mobil->status === 'maintenance') {
                $mobil->update(['status' => 'tersedia']);

                // Log perubahan status mobil
                LogActivity::createLog(
                    'UPDATE',
                    'mobils',
                    $mobil->id,
                    'Mobil ' . $mobil->namamobil . ' dikembalikan ke status TERSEDIA setelah selesai expense: ' . $expense->deskripsi,
                    ['status' => 'maintenance'],
                    ['status' => 'tersedia']
                );
            }
        }
    }

    public function delete($id)
    {
        $expense = Expense::find($id);
        $oldData = $expense->toArray();

        // Delete file if exists
        if ($expense->bukti_pembayaran) {
            Storage::disk('public')->delete($expense->bukti_pembayaran);
        }

        $expense->delete();

        // Log activity
        LogActivity::createLog(
            'DELETE',
            'expenses',
            $id,
            'Menghapus expense: ' . $oldData['deskripsi'] . ' - Rp ' . number_format($oldData['jumlah'], 0, ',', '.'),
            $oldData,
            null
        );

        session()->flash('success', 'Data expense berhasil dihapus');
    }

    // Method untuk menutup form
    public function closeForm()
    {
        $this->reset([
            'mobil_id',
            'kategori',
            'deskripsi',
            'jumlah',
            'tanggal',
            'metode_pembayaran',
            'nomor_referensi',
            'status',
            'bukti_pembayaran',
            'catatan',
            'newBukti',
            'currentBukti',
            'id'
        ]);
        $this->addPage = false;
        $this->editPage = false;
    }

    // Alias untuk backward compatibility
    public function cancel()
    {
        $this->closeForm();
    }
}
