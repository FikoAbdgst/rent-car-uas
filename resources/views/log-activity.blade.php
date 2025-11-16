{{-- resources/views/log-activity.blade.php --}}

@extends('layout.template')

@section('title', 'Log Activity')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-2">

                @livewire('log-activity-component')
            </div>
        </div>
    </div>
@endsection
