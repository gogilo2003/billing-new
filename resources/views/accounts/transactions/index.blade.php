@extends('layouts.app', ['pageSlug' => 'acconts/transactions'])

@section('title')
	Accounts
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Transactions</h3>
			<p class="category">Transactions for {{ isset($account) ? $account->client->name : 'all clients' }}</p>
		</div>
		<div class="card-body table-responsive">
			<table class="table table-hover table-striped" id="accountsDataTable">
				<thead>
					<tr class="d-sm-table-row d-none">
						<th>#</th>
						<th>Client Name</th>
						<th>Account Name</th>
						<th>Particulars</th>
						<th class="text-center">Type</th>
						<th class="text-right">Amount</th>
						<th>Date</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($transactions as $transaction)
					<tr class="d-sm-table-row d-flex flex-column">
						<td>{{ $loop->iteration }}</td>
						<td>{{ $transaction->account->client->name }}</td>
						<td>{{ $transaction->account->name }}</td>
						<td>{{ $transaction->particulars }}</td>
						<td class="text-center">{{ $transaction->type }}</td>
						<td class="text-right">{{ \number_format($transaction->amount) }}</td>
						<td>{{ $transaction->created_at->format('j, F Y') }}</td>
						<td>
							<div class="btn-group">
								<a href="#" data-toggle="modal" data-target="#viewTransactionModal" data-transaction='@json($transaction)' class="btn btn-info btn-fill btn-sm view"><i class="pe-7s-albums"></i>&nbsp;View</a>
								<a href="#" data-toggle="modal" data-target="#editTransactionModal" data-transaction='@json($transaction)' class="btn btn-info btn-fill btn-sm edit"><i class="pe-7s-pen"></i>&nbsp;Edit</a>
								<a href="{{ route('accounts-transactions-download',$transaction->id) }}" class="btn btn-info btn-fill btn-sm"><i class="pe-7s-download"></i>&nbsp;Download</a>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
    </div>
    @include('accounts.transactions.view')
    @include('accounts.transactions.edit')
@endsection

@section('navbar')
	@parent
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-transaction',isset($account) ? $account->id : null) }}">
			New Transaction
		</a>
	</li>
@endsection

@section('styles')
	<style>

	</style>
@endsection

@section('scripts_top')
	<script type="text/javascript">

	</script>
@endsection

@push('scripts_bottom')
	<script type="text/javascript">
		$('table#accountsDataTable').dataTable()
	</script>
@endpush
