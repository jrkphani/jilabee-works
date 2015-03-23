@if($notes)
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{$notes->title}}
				<span class="pull-right">
					{!! Form::select('status', array('open', 'close','expired','timeout','failed'), 'opne')!!}
				</span>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-12">
						<h4>{!! nl2br($notes->description) !!}</h4>
					</div>
					@foreach($notes->notes_history()->get() as $history)
						<div class="col-md-8 border_top">
							{!! nl2br($history->description) !!}
						</div>
						<div class="col-md-4 border_top">
							<a href="{{ app_url('/profile/') }}" >{!! nl2br($history->createdby->name) !!}
							<span class="glyphicon glyphicon-user"></span></a>
						</div>
					@endforeach
				</div>
				<div class="col-md-12">
					<textarea id="description" class="form-group col-md-12" rows='3'></textarea>
					<div class="col-md-12">
						<button id="add_comment" nid="{{ $notes->id }}" class="pull-right btn btn-primary">Add Comment</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
