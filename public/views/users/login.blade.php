@extends('users.layout')

@section('content')

<div class="row">
	<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 login-form">
	<h2>Login</h2>

	@if(Session::has('message'))
		<div class="row">
			<div class="col-md-12 error">
				<p class="alert">{{ Session::get('message') }}</p>
			</div>
		</div>
	@endif

		{{ Form::open(array('url'=>'login', 'class' => 'form-horizontal')) }}

			<div class="form-group">
				<div class="col-sm-12">
					{{ Form::text('username', null, array('class'=>'username', 'placeholder'=>'Username')) }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					{{ Form::password('password', array('class'=>'password', 'placeholder'=>'Password')) }}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<div class="row">
						<div class="col-sm-4">
							<a href="{{ route('register') }}"><input type="button" class="register-button" value="Register"></a>
						</div>
						<div class="col-sm-8">
							{{ Form::submit('Let\'s go !', array('class'=>'submit-button'))}}
						</div>
					</div>
				</div>
			</div>


		{{ Form::close() }}

	</div>
</div>

@stop