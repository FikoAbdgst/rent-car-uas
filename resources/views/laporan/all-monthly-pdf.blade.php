<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .month-section {
            margin-bottom: 40px;
        }

        .summary {
            margin-bottom: 20px;
        }

        .summary div {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Keuangan Tahunan</h2>
        <h3>Tahun {{ $year }}</h3>
    </div>

    @foreach ($months as $monthData)
        <div class="month-section">
            <h3>{{ $monthData['month_name'] }} {{ $year }}</h3>
            <div class="summary">
                <div>Pemasukan: @rupiah($monthData['total_income'])</div>
                <div>Pengeluaran: @rupiah($monthData['total_expense'])</div>
                <div>Keuntungan Bersih: @rupiah($monthData['net_profit'])</div>
            </div>

            <h4>Detail Transaksi</h4>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Polisi</th>
                        <th>Nama Penyewa</th>
                        <th>Alamat</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Kembali</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monthData['transaksi'] as $transaksi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaksi->mobil->platnomor ?? 'N/A' }}</td>
                            <td>{{ $transaksi->penyewa->nama ?? 'N/A' }}</td>
                            <td>{{ $transaksi->penyewa->alamat ?? 'N/A' }}</td>
                            <td>{{ $transaksi->tanggalmulai }}</td>
                            <td>{{ $transaksi->tanggalkembali }}</td>
                            <td>@rupiah($transaksi->totalharga)</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <h4>Detail Pengeluaran</h4>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monthData['expenses'] as $expense)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expense->kategori }}</td>
                            <td>{{ $expense->deskripsi }}</td>
                            <td>@rupiah($expense->jumlah)</td>
                            <td>{{ $expense->tanggal }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Tidak ada pengeluaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
