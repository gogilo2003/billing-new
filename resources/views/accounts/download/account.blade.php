@extends('layouts.pdf')

@section('title')
	Account Details
@endsection

@section('content')
	<div class="card card-plain">
		<div class="card-header">
            <h4 class="card-title"><b style="color: #116AC3; text-transform: uppercase">
                Account Name:</b><br>
                {{ $account->client->name }}<br>
                <small>({{ $account->name }})</small>
            </h4>
			<hr/>
			<p class="category">Credit: <em>{{ number_format($account->cr(),2) }}</em></p>
			<p class="category">Debit: <em>{{ number_format($account->dr(),2) }}</em></p>
			<p class="category">Balance: <em>{{ number_format($account->balance(),2) }}</em></p>
		</div>
		<table class="table table-hover table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Date</th>
					<th>Particulars</th>
					<th>Method</th>
					<th class="text-right">DEBIT</th>
					<th class="text-right">CREDIT</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($account->transactions as $tx)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $tx->created_at->format('j M, Y h:i:s A') }}</td>
					<td>{{ $tx->particulars }}</td>
					<td>{{ $tx->method }}</td>
					<td class="text-right">{{ $tx->type==="DR" ? number_format($tx->amount,2) :'' }}</td>
					<td class="text-right">{{ $tx->type==="CR" ? number_format($tx->amount,2) :'' }}</td>
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
