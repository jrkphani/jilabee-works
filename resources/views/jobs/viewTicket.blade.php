<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{env('APP_NAME')}}</title>
	<meta name="author" content="Dexel Designs">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta name="description" content="">
	<meta name="keywords" content="Anabond, Jotter, ">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/base.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sss.css') }}" />
</head>
<body class="login_body">
	<header>
	<!-- 	<h1>Jotter</h1> -->
	</header>
	<div class="indexLogin"  style="width:auto">
		<h1>{{env('APP_NAME')}}</h1>
		<div class="indexLoginForm" style="text-align: center;">
				<label>Ticket Number: {{$task->id}}</label><br/>
				<label>Title: {{$task->title}}</label><br/>
				<label>Status:
				@if($task->status == 'Closed')
					Resolved
				@elseif($task->status == 'Cancelled')
					Cancelled
				@elseif((!$task->assignee) || ($task->assigner == '-1'))
					Received
				@else
					Processing
				@endif
				</label><br/>
				<a class="login_forgotpassword" href="{{ url('/ticket/view') }}">Back</a>
		</div>
		<br/>
			<div class="clearboth"></div>
	</div>
</body>
</html>