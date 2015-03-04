@extends('master')

@section('guestcontent')
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
					    <li class="active"><a href="#">My Task <span class="badge">2</span></a>
					    </li>
					    <li><a href="#">Follow Ups <span class="badge">4</span></a>

					    </li>
					    <li><a href="#">Minutes <span class="badge">1</span></a>

					    </li>
				  	</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
				    <div class="input-group">
				      <input type="text" class="form-control" placeholder="Search for...">
				      <span class="input-group-btn">
				        <button class="btn btn-default" type="button">Go!</button>
				      </span>
				    </div>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-12">
			  		<div class="row">
			  			<div class="col-md-6">
			  				Sort By
			  				<select class="selectpicker">
						    	<option>Date</option>
						    	<option>Minutes</option>
						    	<option>Something</option>
					    	</select>
			  			</div>
			  			<div class="col-md-6 text-right">
			  				Filter By
			  				<select class="selectpicker">
						    	<option>Open</option>
						    	<option>Closed</option>
						    	<option>Expire</option>
					    	</select>
			  			</div>
			  		</div>
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">          
					    <table class="table">
					      	{{-- <thead>
					          <tr>
					            <th>#</th>
					            <th>Firstname</th>
					          </tr>
					        </thead> --}}
					        <tbody>
					          <tr>
					            <td>1</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					          <tr>
					            <td>2</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					          <tr>
					            <td>3</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					          <tr>
					            <td>3</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					          <tr>
					            <td>3</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					          <tr>
					            <td>3</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					          <tr>
					            <td>3</td>
					            <td>Sed ac interdum sem. Pellentesque nulla quam, eleifend vel laoreet et, </td>
					            <td><span class="glyphicon glyphicon-tag" aria-hidden="true"></td>
					            <td><span class="glyphicon glyphicon-flag" aria-hidden="true"></td>
					          </tr>
					        </tbody>
					    </table>
				     </div>
				</div>
			</div>
		</div>
		<div class="col-md-8 vertical_border_left">
			@yield('content')
		</div>
	</div>
</div>
@endsection
