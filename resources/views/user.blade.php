@extends('master')

@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
					    <li class="user_left_menu" id="menuMytask"><a href="#">My Task <span class="badge">2</span></a>
					    </li>
					    <li class="user_left_menu" id="menuFolloup" url="{{ app_url('/') }}">
					    	<a href="#">Follow Ups <span class="badge">4</span></a>

					    </li>
					    <li class="user_left_menu" id="menuMinutes">
					    	<a href="#">Minutes <span class="badge">1</span></a>
					    </li>
				  	</ul>
				</div>
			</div>
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
			  				<select>
							    <option>Date</option>
						    	<option>Minutes</option>
						    	<option>Something</option>
							 </select>
			  			</div>
			  			<div class="col-md-6 text-right">
			  				Filter By
			  				<select>
						    	<option>Open</option>
						    	<option>Closed</option>
						    	<option>Expire</option>
					    	</select>
			  			</div>
			  		</div>
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-12 scroll_horizontal margin_top_20" id="user_left_menu_cont">
					<!-- Ajax content -->
					No data to display
				</div>
			</div>
		</div>
		<div class="col-md-8 border_left" id="content_right">
			@yield('content')
		</div>
	</div>
</div>
@endsection
@section('javascript')
    <script src="{{ asset('/js/user.js') }}"></script>
    <script src="{{ asset('/js/add_comment.js') }}"></script>
@stop
