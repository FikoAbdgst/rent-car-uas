<?php

namespace App\Livewire;

use App\Models\LogActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class LogActivityComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $filterAction = '';
    public $filterTable = '';
    public $filterUser = '';
    public $filterDate = '';
    public $showDetail = false;
    public $selectedLog = null;

    public function render()
    {
        $query = LogActivity::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan action
        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        // Filter berdasarkan table
        if ($this->filterTable) {
            $query->where('table_name', $this->filterTable);
        }

        // Filter berdasarkan user
        if ($this->filterUser) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->filterUser . '%');
            });
        }

        // Filter berdasarkan tanggal
        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        $logs = $query->paginate(15);

        return view('livewire.log-activity-component', [
            'logs' => $logs
        ]);
    }

    public function clearFilters()
    {
        $this->filterAction = '';
        $this->filterTable = '';
        $this->filterUser = '';
        $this->filterDate = '';
        $this->resetPage();
    }

    public function showLogDetail($id)
    {
        $this->selectedLog = LogActivity::with('user')->find($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedLog = null;
    }

    public function getActionBadgeClass($action)
    {
        switch ($action) {
            case 'CREATE':
                return 'badge bg-success';
            case 'UPDATE':
                return 'badge bg-warning text-dark';
            case 'DELETE':
                return 'badge bg-danger';
            default:
                return 'badge bg-secondary';
        }
    }

    public function getTableBadgeClass($table)
    {
        switch ($table) {
            case 'mobils':
                return 'badge bg-primary';
            case 'transaksis':
                return 'badge bg-info';
            case 'penyewas':
                return 'badge bg-secondary';
            default:
                return 'badge bg-dark';
        }
    }

    public function getTableDisplayName($table)
    {
        switch ($table) {
            case 'mobils':
                return 'Mobil';
            case 'transaksis':
                return 'Transaksi';
            case 'penyewas':
                return 'Penyewa';
            default:
                return ucfirst($table);
        }
    }

    public function updatingFilterAction()
    {
        $this->resetPage();
    }

    public function updatingFilterTable()
    {
        $this->resetPage();
    }

    public function updatingFilterUser()
    {
        $this->resetPage();
    }

    public function updatingFilterDate()
    {
        $this->resetPage();
    }
}
