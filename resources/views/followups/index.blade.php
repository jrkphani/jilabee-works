@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
@stop
@section('content')

	<!--=================================== contentLeft - History section ================================-->
	<div class="contentLeft" id="contentLeft">
			<div class="mainListFilter">
				<input type="text" placeholder="Search..." id="historySearch" autocomplete="off" value="{{$historysearchtxt}}">
				{!! Form::select('historysortby',['timeline'=>'Time Line','assignee'=>'People'],$historysortby,['id'=>'historysortby','autocomplete'=>'off']) !!}
				{!! Form::select('days',['7'=>'Last 7 days','7'=>'Last 7 days','14'=>'Last 14 days','30'=>'Last 30 days','90'=>'Last 90 days','all'=>'Beginning of time'],$days,['id'=>'days','autocomplete'=>'off']) !!}
				<span id="showHistroyDiv" class="button">Reset</span>
			</div>
			<div id="historyDiv" class="mainList">
				@include('followups.history')
			</div>
			<!--================ Buttons for now sections ======================-->
		<div class="arrowBtn arrowBtnRight">
			<span id="moveright"><img src="{{asset('images/arrow_right.png')}}"> </span>
			<p>Now</p>
		</div>
	</div>
	<!--=================================== contentRight - Main/default section ================================-->
	<div id="contentRight" class="contentRight">
			<div class="mainListFilter">
				<input type="text" placeholder="Search..." id="nowSearch" autocomplete="off" value="{{$nowsearchtxt}}">
				{!! Form::select('nowsortby',['timeline'=>'Time Line','assignee'=>'People'],$nowsortby,['id'=>'nowsortby','autocomplete'=>'off']) !!}
				<span class="button" id="showNowDiv">Reset</span>
			</div>
			<div id="nowDiv" class="mainList">
				@include('followups.now')
				<div class="clearboth"></div>
			</div>
		<div class="arrowBtn">
			<span id="moveleft"><img src="{{asset('images/arrow_left.png')}}"> </span>
			<p>History</p>
		</div>
		<!--disable add task for general user on followup -->
		@if(getOrgId())
			<button id="createTask" class="addBtn"> </button>
		@endif
		<div class="clearboth"></div>
	</div>
		<!--================ Buttons for now sections ======================-->
	<!--========================================= POP UP 1 ===================================================-->
	<div class="popupOverlay" id="popup" >
	</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
<script src="{{ asset('/js/followups.js') }}"></script>
<script src="{{ asset('/js/search.js') }}"></script>
<script type="text/javascript">
 $( "#selectAssignee" ).autocomplete({
            source: "/assignee/search",
            minLength: 2,
            select: function( event, ui ) {
            	insert = '<option value="">Search user</option><option value="'+ui.item.userId+'" selected="selected">'+ui.item.value+'</option>';
	            $('#assignee').html(insert);
	            $(this).val('');
	            $('#selectAssignee').hide();
	            $('#assignee').show();
	             getHistory();
	            return false;
            }
            });
 $('#assignee').change(function(event)
 {
 	if(!$(this).val())
 	{
 		$(this).hide();
 		$('#selectAssignee').show();
 		 getHistory();
 	}
 });
  $('#meeting').change(function(event)
 {
 	 getHistory();
 });
</script>
@endsection

