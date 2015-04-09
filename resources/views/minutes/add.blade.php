@extends('master')
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"  {{-- style="background-color:{{$meeting->label}}" --}}>Continue:<strong>{{$meeting->title}}</strong></div>
				<div class="panel-body">
					{!! Form::open(array('class'=>'form-horizontal','method'=>'POST','role'=>'form', 'id'=>'meetingHistoryForm')) !!}
					<div class="col-md-12">
						<div class="form-group col-md-6">
							<label class="col-md-4 control-label">Venue</label>
							<div class="col-md-8">
								{!! Form::text('venue',$meeting->venue,array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
							</div>
							{!! $errors->first('venue', '<div class="col-md-12 alert alert-danger">:message</div>') !!}
						</div>
						<div class="form-group col-md-6">
							<label class="col-md-4 control-label">Date</label>
							<div class="col-md-8">
								{!! Form::text('dt',date('Y-m-d H:i:s'),array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
							</div>
							{!! $errors->first('dt', '<div class="col-md-12 alert alert-danger">:message</div>') !!}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label class="col-md-2 control-label">Attendees</label>
							<div class="col-md-10">
								<?php
									$uids = array_merge(explode(',', $meeting->attendees),explode(',', $meeting->minuters));
									$users = App\User::where('id','!=',Auth::user()->id)
									->whereIn('id',$uids)
									->lists('name','id');
								?>
								@foreach($users as $key=>$value)
									<div class="col-md-4">
										{!! Form::checkbox('attendees[]',$key,true) !!} {{ $value }}</div>
								@endforeach
							</div>
							{!! $errors->first('attendees', '<div class="col-md-12 alert alert-danger">:message</div>') !!}
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<a href="{{ URL::previous() }}" class="btn btn-primary">Cancel</a>
							<button id="continue_minute" mid="{{$meeting->id}}" class='btn btn-primary pull-right'>Continue</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection