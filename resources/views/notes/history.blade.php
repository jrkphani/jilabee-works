@if($noteshistory->first())

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{$noteshistory->first()->notes->title}}
				<span class="pull-right">
					{!! Form::select('status', array('open', 'close','expired','timeout','failed'), 'opne')!!}
				</span>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-12">
						<h4>{!! nl2br($noteshistory->first()->notes->description) !!}</h4>
					</div>
					@foreach($noteshistory as $history)
						<div class="col-md-12 border_top">
							{!! nl2br($history->description) !!}
						</div>
					@endforeach
				</div>
				<div class="col-md-12">
					<textarea id="description" class="form-group col-md-12" rows='3'></textarea>
					<div class="col-md-12">
						<button id="add_comment" nid="n{{ $noteshistory->first()->notes->id }}" class="pull-right btn btn-primary">Add Comment</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@else
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
						<textarea id="description" class="form-group col-md-12" rows='3'></textarea>
						<div class="col-md-12">
							<button id="add_comment" nid="n{{ $notes->id }}" class="pull-right btn btn-primary">Add Comment</button>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
@endif
