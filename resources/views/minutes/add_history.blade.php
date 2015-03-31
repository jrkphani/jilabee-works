<div class="row">
	<div class="panel panel-default">
		<div class="panel-heading"  {{-- style="background-color:{{$minutes->label}}" --}}>Continue {{$minutes->title}}</div>
		<div class="panel-body">
			{!! Form::open(array('class'=>'form-horizontal','method'=>'POST','role'=>'form', 'id'=>'minute_history_form')) !!}
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-md-4 control-label">Venue</label>
					<div class="col-md-8">
						{!! Form::text('venue',$minutes->venue,array('class'=>"form-control",'placeholder'=>'Venue','autocomplete'=>'off')) !!}
						{!! $errors->first('venue', '<span class="alert alert-danger">:message</span>') !!}
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="form-group">
					<label class="col-md-4 control-label">Attendees</label>
					<div class="col-md-8">
						<?php
							$uids = array_merge(explode(',', $minutes->attendees),explode(',', $minutes->minuters));
							$users = App\User::where('id','!=',Auth::user()->id)
							->whereIn('id',$uids)
							->lists('name','id');
						?>
						@foreach($users as $key=>$value)
							{!! Form::checkbox('attendees[]',$key,true) !!} {{ $value }}</br>
						@endforeach
						{!! $errors->first('attendees', '<span class="error">:message</span>') !!}
					</div>
				</div>
			</div>
			<div class="col-md-2 col-md-offset-6">
				<div class="form-group">
					<span id="continue_minute" mid="{{$minutes->id}}" class='btn btn-primary'>Continue</span>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
