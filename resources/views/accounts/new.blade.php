@extends('layouts.app', ['pageSlug' => 'accounts/add'])

@section('title')
	New Account
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h5 class="title">
				<i class="pe-7s-plus"></i>
				New Account
			</h5>
		</div>
		<hr>
		<form class="card-body" method="post" action="{{route('accounts-store')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="client">Client</label>
						<select class="form-control" id="client" name="client">
							@foreach(App\Models\Client::all() as $client)
							<option value="{{ $client->id }}" selected>{{ $client->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-8">
					<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
						<label for="name">Account Name</label>
						<input type="text" class="form-control" id="name" name="name" placeholder="Enter name"{!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
						{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
					</div>
				</div>
			</div>
			<div class="card-footer">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>  Save</button>
			</div>
		</form>
	</div>
@endsection

@section('navbar')
	@parent
	<li><a href="{{ route('accounts') }}"><i class="pe-7s-graph1"></i>&nbsp;Accounts</a></li>
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
