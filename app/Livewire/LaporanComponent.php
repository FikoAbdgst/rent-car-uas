<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Carbon\Carbon;

class LaporanComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $selectedYear;
    public $selectedMonth;
    public $showModal = false;
    public $modalData = [];

    public function mount()
    {
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('m');
    }

    public function render()
    {
        $months = $this->generateMonthsData();
        $yearlyData = $this->getYearlyData();

        return view('livewire.laporan-component', compact('months', 'yearlyData'));
    }

    private function generateMonthsData()
    {
        $months = [];
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Generate all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $monthName = date('F', mktime(0, 0, 0, $month, 1, $this->selectedYear));

            // Check if month is current or past (has data)
            $isCurrentOrPast = ($this->selectedYear < $currentYear) ||
                ($this->selectedYear == $currentYear && $month <= $currentMonth);

            $monthData = [
                'month' => $month,
                'year' => $this->selectedYear,
                'month_name' => $monthName,
                'is_current' => $isCurrentOrPast,
                'data' => $isCurrentOrPast ? $this->getMonthlyData($month, $this->selectedYear) : null,
            ];

            $months[] = $monthData;
        }

        return $months;
    }

    private function getMonthlyData($month, $year)
    {
        $startDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $endDate = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

        $transaksi = Transaksi::with(['mobil', 'penyewa', 'user'])
            ->where('status', 'SELESAI')
            ->whereBetween('tanggalmulai', [$startDate, $endDate])
            ->orderBy('tanggalmulai', 'desc')
            ->get();

        $expenses = Expense::where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalIncome = $transaksi->sum('totalharga');
        $totalExpense = $expenses->sum('jumlah');
        $netProfit = $totalIncome - $totalExpense;

        return [
            'transaksi' => $transaksi,
            'expenses' => $expenses,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $netProfit,
            'month_name' => date('F', mktime(0, 0, 0, $month, 1, $year)),
        ];
    }

    private function getYearlyData()
    {
        $startDate = $this->selectedYear . '-01-01';
        $endDate = $this->selectedYear . '-12-31';

        // Get current date limits
        $currentDate = date('Y-m-d');
        if ($this->selectedYear == date('Y')) {
            $endDate = $currentDate;
        }

        $transaksi = Transaksi::where('status', 'SELESAI')
            ->whereBetween('tanggalmulai', [$startDate, $endDate])
            ->get();

        $expenses = Expense::where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $totalIncome = $transaksi->sum('totalharga');
        $totalExpense = $expenses->sum('jumlah');
        $netProfit = $totalIncome - $totalExpense;
        $totalTransactions = $transaksi->count();

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $netProfit,
            'total_transactions' => $totalTransactions,
        ];
    }

    public function openModal($month, $year)
    {
        $this->selectedMonth = $month;
        $this->modalData = $this->getMonthlyData($month, $year);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalData = [];
    }

    public function exportMonthlyPdf($month, $year)
    {
        $data = $this->getMonthlyData($month, $year);
        $monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));

        $pdf = Pdf::loadView('laporan.monthly-pdf', [
            'data' => $data,
            'month_name' => $monthName,
        ])->output();

        return response()->streamDownload(
            fn() => print($pdf),
            "laporan-bulanan-{$month}-{$year}.pdf"
        );
    }

    public function exportAllPdf()
    {
        $allData = [];
        $currentYear = date('Y');
        $currentMonth = ($this->selectedYear == $currentYear) ? date('m') : 12;

        for ($month = 1; $month <= $currentMonth; $month++) {
            $monthData = $this->getMonthlyData($month, $this->selectedYear);
            $monthData['month_name'] = date('F', mktime(0, 0, 0, $month, 1, $this->selectedYear));
            $allData[] = $monthData;
        }

        $yearlyData = $this->getYearlyData();

        $pdf = Pdf::loadView('laporan.all-monthly-pdf', [
            'months' => $allData,
            'year' => $this->selectedYear,
            'yearlyData' => $yearlyData,
        ])->output();

        return response()->streamDownload(
            fn() => print($pdf),
            "laporan-tahunan-{$this->selectedYear}.pdf"
        );
    }

    // Reset modal when year changes
    public function updatedSelectedYear()
    {
        $this->closeModal();
    }
}
