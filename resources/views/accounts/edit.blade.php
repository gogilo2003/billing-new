@extends('layouts.app', ['pageSlug' => 'accounts/edit'])

@section('title')
	Edit Account
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h5 class="title">
				<i class="pe-7s-plus"></i>
				Edit Account ({{ $account->client->name }}-{{ $account->name }})
			</h5>
		</div>
		<hr>
		<form class="card-body" method="post" action="{{route('accounts-update')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="client">Client</label>
						<select class="form-control" id="client" name="client">
							@foreach(App\Models\Client::all() as $client)
							<option value="{{ $client->id }}" {!! old('client') ? ((old('client') === $account->client->id) ? 'selected': '') : (($client->id === $account->client->id) ? 'selected' : '') !!}>{{ $client->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-8">
					<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
						<label for="name">Account Name</label>
						<input type="text" class="form-control" id="name" name="name" placeholder="Enter name"{!! ((old('name')) ? ' value="'.old('name').'"' : 'value="'.$account->name.'"') !!}>
						{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
					</div>
				</div>
			</div>
			<div class="card-footer">
				<input type="hidden" name="id" value="{{ $account->id }}">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>  Save</button>
			</div>
		</form>
	</div>
@endsection

@section('navbar')
	@parent
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients-create') }}">
			Create Account
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients-view',$account->id) }}">
			View Account
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="javascript:delete_account()">
			Delete Account
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

	</script>
@endsection
