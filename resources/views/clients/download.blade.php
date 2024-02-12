@extends('layouts.pdf')

@section('title')
	Client Details
@endsection

@section('content')
	<div class="card card-plain">
		<div class="card-header">
			<h4 class="card-title">{{ $client->name }}</h4>
			<p class="category">{{ $client->email }} / {{ $client->phone }} / {{ $client->box_no }} - {{ $client->post_code }}, {{ $client->town }}</p>
		</div>
		<hr>
		<div class="card-body">
			<h5 class="title">Accounts</h5>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th class="text-right">CREDIT</th>
						<th class="text-right">DEBIT</th>
						<th class="text-right">BALANCEs</th>
					</tr>
				</thead>
				<tbody>
					@foreach($client->accounts->sortBy('balance') as $account)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $account->name }}</td>
						<td class="text-right">KES {{ number_format($account->cr(),2) }}</td>
						<td class="text-right">KES {{ number_format($account->dr(),2) }}</td>
						<td class="text-right">KES {{ number_format($account->balance(),2) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<h5 class="title">Invoices</h5>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Date</th>
						<th class="text-right">Amount</th>
					</tr>
				</thead>
				<tbody>
					@foreach($client->invoices->sortByDateDesc('created_at') as $invoice)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $invoice->name }}</td>
						<td>{!! date_format(date_create($invoice->created_at),'j\<\s\u\p\>S\<\/\s\u\p\> M, Y h:i:s A') !!}</td>
						<td class="text-right">KES {{ number_format($invoice->amount(),2) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
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
