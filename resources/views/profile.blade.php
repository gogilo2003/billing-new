@extends('layouts.app', ['pageSlug' => 'profile'])

@section('title')
	Profile
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="row">
	    <div class="col-md-8">
	        <div class="card">
	            <div class="card-header">
	                <h4 class="card-title">Edit Profile</h4>
	            </div>
	            <div class="card-body">
	            	<form method="post" action="{{ route('profile') }}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">

	            		<input type="hidden" name="_token" value="{{csrf_token()}}">

						<div class="form-group{!! $errors->has('email') ? ' has-error':'' !!}">
							<label for="email">Email</label>
							<input readonly="true" type="email" class="form-control" id="email" name="email" placeholder="Enter email"{!! ((old('email')) ? ' value="'.old('email').'"' : ' value="'.Auth::user()->email.'"' ) !!}>
							{!! $errors->has('email') ? '<span class="text-danger">'.$errors->first('email').'</span>' : '' !!}
						</div>

						<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Enter name"{!! ((old('name')) ? ' value="'.old('name').'"' : ' value="'.Auth::user()->name.'"') !!}>
							{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
						</div>

	                    <button type="submit" class="btn btn-primary btn-fill pull-right"><i class="pe-7s-edit"></i>&nbsp;Update Profile</button>
	                    <div class="clearfix"></div>
	                </form>
	            </div>
	        </div>
	        <div class="card">
	            <div class="card-header">
	                <h4 class="card-title">Change Password</h4>
	            </div>
	            <div class="card-body">
	                <form method="post" action="{{route('password')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">

	                	<input type="hidden" name="_token" value="{{csrf_token()}}">

						<div class="form-group{!! $errors->has('old_password') ? ' has-error':'' !!}">
							<label for="old_password">Old Password</label>
							<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter old password"{!! ((old('old_password')) ? ' value="'.old('old_password').'"' : '') !!}>
							{!! $errors->has('old_password') ? '<span class="text-danger">'.$errors->first('old_password').'</span>' : '' !!}
						</div>

						<div class="form-group{!! $errors->has('password') ? ' has-error':'' !!}">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Enter password"{!! ((old('password')) ? ' value="'.old('password').'"' : '') !!}>
							{!! $errors->has('password') ? '<span class="text-danger">'.$errors->first('password').'</span>' : '' !!}
						</div>

						<div class="form-group{!! $errors->has('password_confirmation') ? ' has-error':'' !!}">
							<label for="password_confirmation">Password Confirmation</label>
							<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter password confirmation"{!! ((old('password_confirmation')) ? ' value="'.old('password_confirmation').'"' : '') !!}>
							{!! $errors->has('password_confirmation') ? '<span class="text-danger">'.$errors->first('password_confirmation').'</span>' : '' !!}
						</div>

	                    <button type="submit" class="btn btn-primary btn-fill pull-right"><i class="pe-7s-refresh-2"></i>&nbsp;Update Profile</button>
	                    <div class="clearfix"></div>
	                </form>
	            </div>
	        </div>
	    </div>
	    <div class="col-md-4">

	        <div class="card testimonial-card">
                <div class="card-up blue-gradient-rgba"></div>
	            <div class="avatar mx-auto white">
	                <img class="rounded-circle" src="{{ Auth::user()->photo_url }}" alt="...">
	            </div>
	            <div class="card-body">

	                <div class="card-footer text-center">
		                <form method="post" action="{{route('picture')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
		                	<div class="custom-file{!! $errors->has('picture') ? ' has-error':'' !!}">
		                		<label class="custom-file-label" for="picture">Select Profile Picture</label>
		                		<input type="file" class="custom-file-input" id="picture" name="picture">
		                		{!! $errors->has('picture') ? '<span class="text-danger">'.$errors->first('picture').'</span>' : ''!!}
		                		<p class="help-block">Select a Profile picture to replace current</p>
		                	</div>
		                	<input type="hidden" name="_token" value="{{ csrf_token()}}">
		                	<button type="submit" class="btn btn-primary btn-fill btn-block"><i class="pe-7s-photo"></i>&nbsp;Update Picture</button>
	                	</form>
	                </div>
	            </div>
	        </div>
	    </div>

	</div>
@endsection

@section('navbar')
	@parent
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
