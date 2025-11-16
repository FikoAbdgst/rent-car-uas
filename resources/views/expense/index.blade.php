@extends('layout.template')
@section('title', 'Expense - Rent Car')

@section('content')
    <style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table th {
            font-weight: 600;
            border-top: none;
        }

        .badge {
            font-size: 0.75em;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
    </style>
    @livewire('ExpenseComponent')

@endsection
