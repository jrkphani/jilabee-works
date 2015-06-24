@extends('master')
@section('content')
<?php
	print_r(Auth::user()->email);
?>
@endsection