@extends('layouts.app', ['pageSlug' => 'clients/view'])

@section('title')
	{{ $client->name }}
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">{{ $client->name }}</h4>
			<p class="category">{{ $client->email }} / {{ $client->phone }} / {{ $client->box_no }} - {{ $client->post_code }}, {{ $client->town }}</p>
		</div>
		<hr>
		<div class="card-body">
            <h5 class="title">Summary</h5>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Accounts</td>
                        <td>{{ $client->accounts->count() }}</td>
                        <td>Invoices</td>
                        <td>{{ $client->invoices->count() }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Accounts</td>
                        <td>{{ $client->accounts->count() }}</td>
                        <td>Invoices</td>
                        <td>{{ $client->invoices->count() }}</td>
                    </tr>
                </tbody>
            </table>
			<h5 class="title">Accounts</h5>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th class="text-right">CREDIT</th>
						<th class="text-right">DEBIT</th>
						<th class="text-right">BALANCE</th>
					</tr>
				</thead>
				<tbody>
					@foreach($client->accounts->sortBy('balance') as $account)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $account->name }}</td>
						<td class="text-right">{{ number_format($account->cr(),2) }}</td>
						<td class="text-right">{{ number_format($account->dr(),2) }}</td>
						<td class="text-right">{{ number_format($account->balance(),2) }}</td>
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

	<form id="deleteUserForm" method="post" action="{{route('clients-delete')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">

		<input type="hidden" name="id" value="{{$client->id}}">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
	</form>
@endsection

@section('navbar')
	@parent
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients') }}">
			<i class="pe-7s-display2"></i>
			Clients
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients-create') }}">
			<i class="pe-7s-add-user"></i>
			New
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients-edit',$client->id) }}">
			<i class="pe-7s-pen"></i>
			Edit
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients-download',$client->id) }}">
			<i class="pe-7s-download"></i>
			Download
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="javascript:delete_user()">
			<i class="pe-7s-delete-user"></i>
			Delete
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

@section('scripts_bottom')
	<script type="text/javascript">
	function delete_user() {
		$('#deleteUserForm').submit();
	}
	</script>
@endsection
