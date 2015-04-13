<div class="row scroll_horizontal">
	@if($meetings->first())
	@foreach($meetings as $meeting)
	<div class="col-md-12 border_bottom margin_top_10">
		<div class="col-md-8">
			<strong>{{$meeting->title}}</strong>
			@if(Auth::user()->profile->role == '999')
				<a href="{{url('meeting/'.$meeting->id.'/edit')}}">
					<span class=" glyphicon glyphicon-edit"></span>
				</a>
			@endif
		</div>
		<div class="col-md-4">
			@if($meeting->isMinuter())
				@if(!$meeting->minutes()->where('lock_flag','!=','0')->count())
					<a href="{{url('meeting/'.$meeting->id.'/nextminute')}}" ><span mid="{{$meeting->id}}" class="pull-right glyphicon glyphicon-forward"></span></a>
				@endif
			@endif
		</div>
	</div>
	
		@foreach($meeting->minutes()->orderBy('updated_at', 'desc')->get() as $minute)
		@if($minute->lock_flag != 0)
			@if($minute->lock_flag == Auth::id())
				<a class="col-md-12" href="{{ url('minute/'.$minute->id.'/tasks/add')}}">
			@else
				<a class="col-md-12 minute" id="minute{{$minute->id}}" href="#meetings#minute{{$minute->id}}">
			@endif
		@else
			<a class="col-md-12 minute" id="minute{{$minute->id}}" href="#meetings#minute{{$minute->id}}">
		
		@endif		
			<div class="col-md-4">{{ date("d M",strtotime($minute->updated_at)) }}</div>
			<div class="col-md-8">
				{{ $minute->venue }}
				@if($minute->lock_flag != 0)
			        {{-- <span class="glyphicon glyphicon-lock pull-right"></span> --}}
			        <strong>draft</strong>
			    @endif
			</div>
		</a>
		@endforeach
	
	@endforeach
	<div class"col-md-12">
		<?php echo $meetings->render(); ?>
	</div>
@else
	No data to display!
@endif
</div>