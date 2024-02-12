@extends('layouts.app', ['pageSlug' => 'accounts'])

@section('title')
	Accounts
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Accounts</h3>
			<p class="category">Accounts for {{ isset($client) ? $client->name : 'all clients' }}</p>
		</div>
		<div class="card-body">
			<table class="table table-hover table-striped" id="accountsDataTable">
				<thead>
					<tr class="d-sm-table-row d-none">
                        <th>#</th>
                        @if(!isset($client))
                        <th>Client Name</th>
                        @endif
						<th>Name</th>
						<th>Notification</th>
						<th class="text-right">Credit</th>
						<th class="text-right">Debit</th>
						<th class="text-right">Balance</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($accounts->sortBy('balance') as $account)
					<tr class="d-sm-table-row d-flex flex-column">
                        <td>{{ $loop->iteration }}</td>
                        @if(!isset($client))
						<td>{{ $account->client->name }}</td>
                        @endif
						<td>{{ $account->name }}</td>
						<td>{{ $account->notification ? 'Enabled' : 'Disabled' }}</td>
						<td><span class="d-none d-sm-inline-block" style="width: 180px">Credit: </span>{{ number_format($account->cr(),2) }}</td>
						<td><span class="d-none d-sm-inline-block" style="width: 180px">Debit: </span>{{ number_format($account->dr(),2) }}</td>
						<td><span class="d-none d-sm-inline-block" style="width: 180px">Balance: </span>{{ number_format($account->balance(),2) }}</td>
						<td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm rounded-pill dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="tim-icons icon-settings"></i>
                                </button>
                                <div class="dropdown-menu dropdown-primary">
                                    <a href="{{ route('accounts-edit',$account->id) }}" class="dropdown-item"><i class="tim-icons icon-pencil"></i>&nbsp;Edit</a>
                                    <a href="{{ route('accounts-view',$account->id) }}" class="dropdown-item"><i class="fas fa-th"></i>&nbsp;View</a>
                                    <a href="{{ route('accounts-transaction',$account->id) }}" class="dropdown-item"><i class="fas fa-plus-circle"></i>&nbsp;Transaction</a>
                                    <a href="{{ route('accounts-transactions',$account->id) }}" class="dropdown-item"><i class="tim-icons icon-coins"></i>&nbsp;Transactions</a>
                                    <a href="{{ route('invoices-create',$account->client->id) }}" class="dropdown-item"><i class="tim-icons icon-paper"></i>&nbsp;New Invoice</a>
                                    <a href="{{ route('accounts-download',$account->id) }}" class="dropdown-item"><i class="tim-icons icon-cloud-download-93"></i>&nbsp;Download</a>
                                    <a href="JavaScript:updateNotification({{ $account->id }})" class="dropdown-item"><i class="tim-icons icon-bell-55"></i>&nbsp;Notification</a>
                                </div>
                            </div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection

@section('navbar')
	@parent
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-create') }}">
			New Account
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('invoices-create') }}">
			New Invoice
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-transaction') }}">
			New Transaction
		</a>
	</li>
@endsection

@push('styles')
	<style>

	</style>
@endpush

@push('scripts_top')
	<script type="text/javascript">

	</script>
@endpush

@push('scripts_bottom')
	<script type="text/javascript">
        function updateNotification(id){
            $.post('{{ route("api-accounts-notification-update") }}',{id}).then(response=>{
                alert(response.message)
            })
        }
		$('table#accountsDataTable').dataTable()
	</script>
@endpush
