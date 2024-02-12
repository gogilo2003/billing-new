@extends('layouts.app', ['pageSlug' => 'clients'])

@section('title')
    Clients
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <clients />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush

@push('js')
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
@endpush
