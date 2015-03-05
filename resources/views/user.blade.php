@extends('master')

@section('guestcontent')
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
					    <li class="active user_left_menu onload" id=""><a href="#">My Task <span class="badge">2</span></a>
					    </li>
					    <li class="user_left_menu"><a href="#">Follow Ups <span class="badge">4</span></a>

					    </li>
					    <li class="user_left_menu"><a href="#">Minutes <span class="badge">1</span></a>

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
		<div class="col-md-8 border_left">
			@yield('content')
		</div>
	</div>
</div>
@endsection
@section('javascript')
    @parent
    <script type="text/javascript">
    $(document).ready(function($) {
    	$('.onload').click();
    });
    	
    	$('.user_left_menu').click(function(event) {
    		$('.user_left_menu').removeClass('active');
    		$(this).addClass('active');
    		$('#user_left_menu_cont').html('<div class="loading"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div>');
    		$_token = "{{ csrf_token() }}";
    		$.ajax({
    			url: '/mytask',
    			type: 'POST',
    			async:false,
    			dataType: 'html',
    			data: {_token: $_token },
    		})
    		.done(function(output) {
    			$('#user_left_menu_cont').html(output);
    		})
    		.fail(function() {
    			$.notify('Oops, Something went wrong!',
    			{
				   className:'error',
				   globalPosition:'top center'
				});
				$('#user_left_menu_cont').html('No data to display!');
    		})
    		.always(function() {
    			//console.log("complete");
    		});
    		
    	});
    </script>
@stop
