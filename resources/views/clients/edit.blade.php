@extends('layouts.app', ['pageSlug' => 'clients/edit'])

@section('title')
	Edit Client
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<form method="post" action="{{route('clients-update')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Edit Client</h4>
						<p class="category">Edit <em>{{ $client->name }}</em></p>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
									<label for="name">Name</label>
									<input type="text" class="form-control" id="name" name="name" placeholder="Enter name"{!! ((old('name')) ? ' value="'.old('name').'"' : ' value="'.$client->name.'"') !!}>
									{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('phone') ? ' has-error':'' !!}">
									<label for="phone">Phone</label>
									<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone"{!! ((old('phone')) ? ' value="'.old('phone').'"' : ' value="'.$client->phone.'"') !!}>
									{!! $errors->has('phone') ? '<span class="text-danger">'.$errors->first('phone').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('email') ? ' has-error':'' !!}">
									<label for="email">Email</label>
									<input type="text" class="form-control" id="email" name="email" placeholder="Enter email"{!! ((old('email')) ? ' value="'.old('email').'"' : ' value="'.$client->email.'"') !!}>
									{!! $errors->has('email') ? '<span class="text-danger">'.$errors->first('email').'</span>' : '' !!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('box_no') ? ' has-error':'' !!}">
									<label for="box_no">Box No</label>
									<input type="text" class="form-control" id="box_no" name="box_no" placeholder="Enter box no"{!! ((old('box_no')) ? ' value="'.old('box_no').'"' : ' value="'.$client->box_no.'"') !!}>
									{!! $errors->has('box_no') ? '<span class="text-danger">'.$errors->first('box_no').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('post_code') ? ' has-error':'' !!}">
									<label for="post_code">Post Code</label>
									<input type="text" class="form-control" id="post_code" name="post_code" placeholder="Enter post code"{!! ((old('post_code')) ? ' value="'.old('post_code').'"' : ' value="'.$client->post_code.'"') !!}>
									{!! $errors->has('post_code') ? '<span class="text-danger">'.$errors->first('post_code').'</span>' : '' !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('town') ? ' has-error':'' !!}">
									<label for="town">Town</label>
									<input type="text" class="form-control" id="town" name="town" placeholder="Enter town"{!! ((old('town')) ? ' value="'.old('town').'"' : ' value="'.$client->town.'"') !!}>
									{!! $errors->has('town') ? '<span class="text-danger">'.$errors->first('town').'</span>' : '' !!}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group{!! $errors->has('address') ? ' has-error':'' !!}">
									<label for="address">Address</label>
									<input type="text" class="form-control" id="address" name="address" placeholder="Enter address"{!! ((old('address')) ? ' value="'.old('address').'"' : ' value="'.$client->address.'"') !!}>
									{!! $errors->has('address') ? '<span class="text-danger">'.$errors->first('address').'</span>' : '' !!}
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="card-footer">
						<input type="hidden" name="id" value="{{$client->id}}">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<button type="submit" class="btn btn-primary btn-fill"><i class="fas fa-save"></i>  Save</button>
					</div>
				</div>
			</form>
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
			New Client
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ route('clients-view',$client->id) }}">
			<i class="pe-7s-display2"></i>
			View Client
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="javascript:delete_user()">
			<i class="pe-7s-delete-user"></i>
			Delete Client
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
