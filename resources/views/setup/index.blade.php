@extends('layouts.app', ['pageSlug' => 'setup'])

@section('content')
    <div>
        <a href="#" class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#migrateDialog"><span class="fa fa-cogs"></span>&nbsp;&nbsp; Run Migration</a>
    </div>
    <hr>
    <div id="setup_results"></div>
    @include('setup.migrate')
@endsection

@section('title')
    Setup
@endsection
