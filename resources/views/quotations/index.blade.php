@extends('layouts.app', ['pageSlug' => 'quotations'])

@section('title')
    Domains
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <quotations />
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush
@push('js')
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
@endpush
