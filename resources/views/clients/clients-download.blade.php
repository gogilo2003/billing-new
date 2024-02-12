@extends('layouts.pdf')

@section('title')
	Clients
@endsection

@section('content')
	<div class="card card-plain">
        <table class="table table-hover table-striped">
            <thead>
                <th>#</th>
            	<th>Name</th>
            	<th>Phone</th>
            	<th>Email</th>
            	<th class="text-right">DEBITS</th>
            	<th class="text-right">CREDITS</th>
            	<th class="text-right">Balance</th>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                	<td>{{ $loop->iteration }}</td>
                	<td>{{ strtoupper($client->name) }}</td>
                	<td>{{ $client->phone }}</td>
                	<td>{{ $client->email }}</td>
                	<td class="text-right">KES {{ number_format($client->dr(),2) }}</td>
                	<td class="text-right">KES {{ number_format($client->cr(),2) }}</td>
                	<td class="text-right">KES {{ $client->balance() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
	<style>
		
	</style>
@endsection

@section('scripts_top')
	<script type="text/javascript">

	</script>
@endsection

@section('scripts_bottom')
	<script type="text/javascript">

	</script>
@endsection