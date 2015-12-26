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
	<div class="indexLogin">
		<h1>{{env('APP_NAME')}}</h1>
		<div class="indexLoginForm">
			Ticket raised successfully. Please note down the ticket number <strong>#{{$task->id}}</strong> for further enquiry.
		</div>
		<br/>
			<div class="clearboth"></div>
			<a href="{{url('ticket/new')}}"> Back </a>
	</div>
</body>
</html>