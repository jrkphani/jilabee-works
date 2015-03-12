<?php $checkMid = NULL; ?>
@if($notes->first())
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				{{$notes->first()->minute_history->minute->title}}
				<span class="pull-right btn btn-primary" style="padding:0px">Continue</span>
			</div>
			<div class="panel-body">
				
				<div class="table-responsive">       
				    <table class="table">
				        <tbody>
							@foreach($notes as $note)
							@if($checkMid != $note->mhid)
								<tr class="bg-info">
									<td>{{date('d M',strtotime($note->minute_history->updated_at))}}</td>
									<td>authorsdcdscsdc sdc</td>
									<td></td>
									<td>
										<span class="glyphicon glyphicon-pencil"></span>
										{{ $note->minute_history->updatedby->name}}
										<span class="glyphicon glyphicon-user"></span>
									</td>
								</tr>
								<?php $checkMid = $note->mhid ?>
							@endif
							<tr>
								<td>{{$note->updated_at }}</td>
								<td>{{$note->title}}</td>
								<td>{{$note->description}}</td>
								<td>{{$note->priority}}
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@else
	No data to display!
@endif