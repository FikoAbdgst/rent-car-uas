<?php

namespace App\Livewire;

use App\Models\Mobil;
use App\Models\Penyewa;
use App\Models\Transaksi;
use App\Models\LogActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class TransaksiComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    public $addPage = false;
    public $lihatPage = false;
    public $showNewCustomerForm = false;
    public $selectedPenyewa = '';
    public $nama, $nik, $telp, $alamat, $tanggalmulai, $tanggalkembali, $idmobil, $harga, $total, $catatan;
    public $harga_sewa, $totalharga;
    public $durasi_hari = 0;

    public function render()
    {
        $data['mobil'] = Mobil::where('status', 'tersedia')->paginate(10);
        $data['transaksi'] = $this->lihatPage ? Transaksi::paginate(10) : [];
        $data['penyewas'] = Penyewa::orderBy('nama', 'asc')->get();
        return view('livewire.transaksi-component', $data);
    }

    public function create($id)
    {
        $mobil = Mobil::findOrFail($id);
        $this->idmobil = $id;
        $this->harga_sewa = $mobil->hargasewaperhari;
        $this->addPage = true;

        // Reset form
        $this->reset(['nama', 'nik', 'telp', 'alamat', 'tanggalmulai', 'tanggalkembali', 'catatan', 'totalharga', 'durasi_hari', 'selectedPenyewa', 'showNewCustomerForm']);
    }

    public function selectPenyewa()
    {
        if ($this->selectedPenyewa) {
            $penyewa = Penyewa::find($this->selectedPenyewa);
            if ($penyewa) {
                $this->nama = $penyewa->nama;
                $this->nik = $penyewa->nik;
                $this->telp = $penyewa->telp;
                $this->alamat = $penyewa->alamat;
                $this->showNewCustomerForm = false;
            }
        } else {
            $this->reset(['nama', 'nik', 'telp', 'alamat']);
        }
    }

    public function toggleNewCustomerForm()
    {
        $this->showNewCustomerForm = !$this->showNewCustomerForm;
        if ($this->showNewCustomerForm) {
            $this->selectedPenyewa = '';
            $this->reset(['nama', 'nik', 'telp', 'alamat']);
        }
    }

    public function hitung()
    {
        if ($this->tanggalmulai && $this->tanggalkembali && $this->harga_sewa) {
            $start = Carbon::parse($this->tanggalmulai);
            $end = Carbon::parse($this->tanggalkembali);

            // PERBAIKAN: Gunakan lessThan untuk mengecek tanggal kembali sebelum tanggal mulai
            if ($end->lessThan($start)) {
                $this->totalharga = 0;
                $this->durasi_hari = 0;
                return;
            }

            // PERBAIKAN: Untuk pemesanan 1 hari, minimal durasi adalah 1 hari
            $this->durasi_hari = max(1, $start->diffInDays($end) + 1);
            $this->totalharga = $this->durasi_hari * $this->harga_sewa;

            Log::info('Perhitungan Durasi Sewa', [
                'tanggal_mulai' => $this->tanggalmulai,
                'tanggal_kembali' => $this->tanggalkembali,
                'durasi_hari' => $this->durasi_hari,
                'harga_sewa' => $this->harga_sewa,
                'total_harga' => $this->totalharga
            ]);
        } else {
            $this->totalharga = 0;
            $this->durasi_hari = 0;
        }
    }

    public function store()
    {
        // PERBAIKAN UTAMA: Ganti 'after:tanggalmulai' menjadi 'after_or_equal:tanggalmulai'
        $this->validate([
            'nama' => 'required|min:3',
            'nik' => $this->showNewCustomerForm ? 'required|digits:16|unique:penyewas,nik' : 'required|digits:16',
            'telp' => 'required|min:10',
            'alamat' => 'required|min:10',
            'tanggalmulai' => 'required|date|after_or_equal:today',
            'tanggalkembali' => 'required|date|after_or_equal:tanggalmulai', // PERUBAHAN DI SINI
            'catatan' => 'nullable|max:500',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 3 karakter',
            'nik.required' => 'NIK tidak boleh kosong',
            'nik.digits' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'telp.required' => 'Nomor telepon tidak boleh kosong',
            'telp.min' => 'Nomor telepon minimal 10 digit',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'alamat.min' => 'Alamat minimal 10 karakter',
            'tanggalmulai.required' => 'Tanggal mulai tidak boleh kosong',
            'tanggalmulai.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya',
            'tanggalkembali.required' => 'Tanggal kembali tidak boleh kosong',
            'tanggalkembali.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal mulai (minimal 1 hari)', // PERUBAHAN PESAN
            'catatan.max' => 'Catatan maksimal 500 karakter',
        ]);

        $this->hitung();

        if ($this->totalharga <= 0) {
            session()->flash('error', 'Total harga tidak valid. Silakan periksa tanggal sewa.');
            return;
        }

        // PERBAIKAN: Validasi konflik jadwal yang lebih akurat untuk mendukung pemesanan 1 hari
        $conflict = Transaksi::where('idmobil', $this->idmobil)
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Cek apakah tanggal mulai baru berada di antara rentang existing booking
                    $q->where('tanggalmulai', '<=', $this->tanggalmulai)
                        ->where('tanggalkembali', '>=', $this->tanggalmulai);
                })->orWhere(function ($q) {
                    // Cek apakah tanggal kembali baru berada di antara rentang existing booking
                    $q->where('tanggalmulai', '<=', $this->tanggalkembali)
                        ->where('tanggalkembali', '>=', $this->tanggalkembali);
                })->orWhere(function ($q) {
                    // Cek apakah booking baru mengcover existing booking
                    $q->where('tanggalmulai', '>=', $this->tanggalmulai)
                        ->where('tanggalkembali', '<=', $this->tanggalkembali);
                });
            })
            ->whereIn('status', ['WAIT', 'PROSES'])
            ->exists();

        if ($conflict) {
            session()->flash('error', 'Mobil sudah dipesan pada tanggal tersebut');
            return;
        }

        try {
            // Cek apakah menggunakan penyewa yang sudah ada atau membuat baru
            if ($this->showNewCustomerForm) {
                $penyewa = Penyewa::create([
                    'nama' => $this->nama,
                    'nik' => $this->nik,
                    'telp' => $this->telp,
                    'alamat' => $this->alamat,
                ]);
                $penyewaId = $penyewa->id;

                // Log activity untuk CREATE penyewa baru
                LogActivity::createLog(
                    'CREATE',
                    'penyewas',
                    $penyewa->id,
                    'Menambahkan penyewa baru: ' . $this->nama . ' (' . $this->nik . ')',
                    null,
                    $penyewa->toArray()
                );
            } else {
                $penyewaId = $this->selectedPenyewa;
            }

            $transaksi = Transaksi::create([
                'idmobil' => $this->idmobil,
                'idpenyewa' => $penyewaId,
                'iduser' => Auth::user()->id,
                'tanggalmulai' => $this->tanggalmulai,
                'tanggalkembali' => $this->tanggalkembali,
                'totalharga' => $this->totalharga,
                'status' => 'WAIT',
                'catatan' => $this->catatan,
            ]);

            // Update status mobil menjadi 'disewa'
            $mobil = Mobil::find($this->idmobil);
            if ($mobil) {
                $oldMobilData = $mobil->toArray();
                $mobil->update(['status' => 'disewa']);

                // Log activity untuk UPDATE mobil
                LogActivity::createLog(
                    'UPDATE',
                    'mobils',
                    $mobil->id,
                    'Mengubah status mobil menjadi disewa: ' . $mobil->namamobil . ' (' . $mobil->platnomor . ')',
                    $oldMobilData,
                    $mobil->toArray()
                );
            }

            // Log activity untuk CREATE transaksi
            LogActivity::createLog(
                'CREATE',
                'transaksis',
                $transaksi->id,
                'Menambahkan transaksi baru dengan ID: ' . $transaksi->id . ' untuk penyewa ' . $this->nama,
                null,
                $transaksi->toArray()
            );

            session()->flash('success', 'Transaksi berhasil disimpan dengan ID: ' . $transaksi->id);
            $this->reset();
            $this->addPage = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancelAdd()
    {
        $this->reset();
        $this->addPage = false;
    }
}
