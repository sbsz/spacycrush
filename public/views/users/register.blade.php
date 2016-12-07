@extends('users.layout')

@section('content')

<div class="row">
	<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4 register-form">
	<h2>Register</h2>

	@if(Session::has('message'))
		<div class="row">
			<div class="col-md-12 error">
				<p class="alert">{{ Session::get('message') }}</p>
			</div>

		</div>
	@endif

		{{ Form::open(array('url'=>'register', 'class' => 'form-horizontal')) }}

			<div class="form-group">
				<div class="col-sm-12{{ $errors->has('username') ? ' input-error': '' }}">
					{{ Form::text('username', null, array('class'=>'username', 'placeholder'=>'Username')) }}

					@if($errors->has('username'))
						{{ $errors->first('username', '<span>:message</span>') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12{{ $errors->has('password') ? ' input-error': '' }}">
					{{ Form::password('password', array('class'=>'password', 'placeholder'=>'Password')) }}

					@if($errors->has('password'))
						{{ $errors->first('password', '<span>:message</span>') }}
					@endif
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12{{ $errors->has('password_confirmation') ? ' input-error': '' }}">
					{{ Form::password('password_confirmation', array('class'=>'password', 'placeholder'=>'Confirm Password')) }}

					@if($errors->has('password_confirmation'))
						{{ $errors->first('password_confirmation', '<span>:message</span>') }}
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4">
							<a href="{{ route('login') }}"><input type="button" class="register-button" value="Cancel"></a>
						</div>
						<div class="col-sm-8">
							{{ Form::submit('Create account', array('class'=>'submit-button'))}}
						</div>
					</div>
				</div>
			</div>


		{{ Form::close() }}

	</div>
</div>

@stop