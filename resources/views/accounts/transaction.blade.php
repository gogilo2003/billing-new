@extends('layouts.app', ['pageSlug' => 'accounts/transaction'])

@section('title')
	New Transaction
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<div class="title">Transaction</div>
		</div>
		<hr>
		<form class="card-body" method="post" action="{{route('accounts-transaction-post')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label for="account">Account</label>
						<select class="form-control" id="account" name="account">
							@foreach(App\Models\Account::with('client')->get()->sortBy('client.name') as $account)
							<option value="{{ $account->id }}" {{ isset($account_id) ? ($account_id == $account->id ? 'selected' : '') : '' }}>{{ $account->client->name }}-{{ $account->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-7">
					<div class="form-group{!! $errors->has('particulars') ? ' has-error':'' !!}">
						<label for="particulars">Particulars</label>
						<input type="text" class="form-control" id="particulars" name="particulars" placeholder="Enter particulars"{!! ((old('particulars')) ? ' value="'.old('particulars').'"' : '') !!}>
						{!! $errors->has('particulars') ? '<span class="text-danger">'.$errors->first('particulars').'</span>' : '' !!}
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label for="type">Transcaction Type</label>
						<select class="form-control" id="type" name="type">
							<option value="DR">Debit</option>
							<option value="CR" selected>Credit</option>
						</select>
					</div>
				</div>
				<div class="col-md-7">
					<div class="form-group{!! $errors->has('method') ? ' has-error':'' !!}">
						<label for="method">Mode of Payment</label>
						<input type="text" class="form-control" id="method" name="method" placeholder="Cash/MPesa/Paypal/etc"{!! ((old('method')) ? ' value="'.old('method').'"' : '') !!}>
						{!! $errors->has('method') ? '<span class="text-danger">'.$errors->first('method').'</span>' : '' !!}
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group{!! $errors->has('amount') ? ' has-error':'' !!}">
						<label for="amount">Amount</label>
						<input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount"{!! ((old('amount')) ? ' value="'.old('amount').'"' : '') !!}>
						{!! $errors->has('amount') ? '<span class="text-danger">'.$errors->first('amount').'</span>' : '' !!}
					</div>
				</div>
			</div>

			<div class="card-footer">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="submit" class="btn btn-primary"><i class="pe-7s-refresh"></i>&nbsp;Post Transaction</button>
			</div>
		</form>
	</div>
@endsection

@section('navbar')
	@parent
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-create') }}">
			New Account
		</a>
	</li>
	@if (isset($account_id))
	<li class="nav-item">
		<a class="nav-link" href="{{ route('invoices-create',$account_id) }}">
			New Invoice
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-view',$account_id) }}">
			View Account
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-edit',$account_id) }}">
			Edit Account
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('accounts-download',$account_id) }}">
			Download Account
		</a>
	</li>
	@endif
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
