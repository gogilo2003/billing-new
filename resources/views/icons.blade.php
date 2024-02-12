@extends('layouts.app', ['pageSlug' => 'icons'])

@section('title')
    Icons
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <h2>Tim Icons</h2>
        </div>
        @foreach ($icons as $icon)
            <div class="col-md-2 text-center mb-4">
                <div class="card" style="height:100%">
                    <div class="card-body"
                        style="padding: 15px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <span class="tim-icons {{ $icon }}" style="font-size: 2rem; margin-bottom: 1.5rem"></span>
                        <p style="font-size: 0.8rem">{{ $icon }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('navbar')
    @parent
@endsection

@section('styles')
    <style>

    </style>
@endsection

@section('scripts_top')
    <script type="text/javascript"></script>
@endsection

@section('scripts_bottom')
    <script type="text/javascript"></script>
@endsection
