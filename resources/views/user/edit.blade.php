@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-4">
			{!! Form::model( $user, ['class' => 'form-horizontal']) !!}
			<div class="col-md-12">
			{!! Form::label('name', 'Name') !!}
        	{!! Form::text('name') !!}
        	</div>
        	<div class="col-md-12">
        	{!! Form::label('email', 'Email') !!}
        	{!! Form::text('email') !!}
        	</div>
        	<div class="col-md-12">
        	{!! Form::label('dob', 'DOB') !!}
        	{!! Form::text('dob',$user->profile->dob) !!}
        	</div>
        	<div class="col-md-12">
        	{!! Form::label('phone', 'Phone') !!}
        	{!! Form::text('phone',$user->profile->phone) !!}
        	</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection