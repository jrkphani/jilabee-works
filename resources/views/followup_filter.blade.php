<div class="row margin_top_20">
	<div class="col-lg-12">
	    <div class="input-group">
	      <input type="text" class="form-control" placeholder="Search for...">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="button">Go!</button>
	      </span>
	   </div>
	</div>
</div>
<div class="row margin_top_20">
  	<div class="col-md-12">
 		<div class="row">
  			<div class="col-md-6">
  				Sort By
  				{!! Form::select('followup_filter', ['duedate'=>'Timeline','meeting'=>'Meeting','assignee'=>'Assignee'], $sortby, ['id'=>'followup_filter','autocomplete'=>'off'])!!}
  			</div>
  		</div>
  	</div>
</div>