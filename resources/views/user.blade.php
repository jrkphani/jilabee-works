@extends('master')
@section('content')
<?php
	print_r(App\Model\Profile::find(1));
?>
@endsection