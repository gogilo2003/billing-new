@extends('layouts.app', ['pageSlug' => 'invoices'])

@section('title')
    Invoices
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <invoices />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush
@push('js')
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
@endpush
