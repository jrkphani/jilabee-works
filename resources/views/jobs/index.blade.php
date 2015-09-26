@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop
@section('content')

	<!--=================================== contentLeft - History section ================================-->
	<div class="contentLeft" id="contentLeft">
		@include('jobs.history')
	</div>
	<!--=================================== contentRight - Main/default section ================================-->
	<div id="contentRight" class="contentRight">
		@include('jobs.now')
	</div>
	<div class="clearboth"></div>
	<!--========================================= POP UP 1 ===================================================-->
	<div class="popupOverlay" id="popup" >
	</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jobs.js') }}"></script>
<script src="{{ asset('/js/search.js') }}"></script>

@endsection