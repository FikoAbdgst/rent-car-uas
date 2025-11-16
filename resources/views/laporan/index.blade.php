@extends('layout.template')
@section('title', 'Laporan Transaksi - Rent Car')

@section('content')
    <style>
        .month-card:hover {
            cursor: pointer;
        }

        .month-card.border-secondary {
            opacity: 0.6;
            cursor: not-allowed !important;
        }

        .modal {
            z-index: 1050;
        }

        .table th {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            font-weight: 600;
        }
    </style>

    @livewire('LaporanComponent')

@endsection
