@extends('layouts.app', ['pageSlug' => 'products'])

@section('title')
	Products
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
    <products/>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush
@push('js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
