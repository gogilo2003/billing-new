@extends('layouts.app',['pageSlug'=>'messages/create','page'=>'Compose Message'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Compose Message</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('messages-store') }}" method="post"></form>
        </div>
    </div>
@endsection
