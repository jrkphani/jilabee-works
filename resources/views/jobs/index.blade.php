@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')
<ul class="nav nav-tabs">
  <li id="mytask" class="jobsMenu"><a href="#mytask">My Task</a></li>
  <li id="followups" class="jobsMenu"><a href="#followups">Follow ups</a></li>
  <li id="history" class="jobsMenu"><a href="#history">History</a></li>
</ul>
<div class="row">
	<div id="listLeft" class="col-md-12">
		loading...
	</div>
</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jobs.js') }}"></script>
@endsection