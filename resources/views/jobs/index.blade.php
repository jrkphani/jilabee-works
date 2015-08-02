@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@stop
@section('content')
<div id="jobspage">
</div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/js/jobs.js') }}"></script>
@endsection