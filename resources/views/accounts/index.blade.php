@extends('layouts.app', ['pageSlug' => 'accounts'])

@section('title')
    Accounts
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <accounts />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush

@push('js')
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
@endpush
