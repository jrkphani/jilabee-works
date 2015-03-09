@extends('master')
@section('css')
	<link href="{{ asset('/css/colorpicker.css') }}" rel="stylesheet">
@end
@section('usercontent')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-6">{{$minute->title}}</div>
						<div class="col-md-4 col-md-offset-2">{{Auth::user()->name}}</div>
					</div>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="#">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<div class="col-md-12">
									<input type="text" autocomplete="off" class="form-control" name="title" placeholder="Title" value="{{ old('title') }}">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12">
									<textarea type="text" autocomplete="off" class="form-control" rows="1" name="description" placeholder="Description">{{old('description')}}</textarea>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<div class="col-md-12">
											<select class="form-control" name="assignee" autocomplete="off">
												<option value="">Assingee</option>
												@foreach($users as $user)
													<option value="{{$user->id}}">{{$user->name}}</option>
												@endforeach
											</select>
										</div>
									</div>

								</div>
								<div class="col-md-4 btn"><span class="glyphicon glyphicon-trash"></span></div>
							</div>
						</div>
					</div>
					</form>
					<div class="row">
						<div class="col-md-4 col-md-offset-8">
							<div class="col-md-4">
								<button type="submit" class="btn btn-primary">Cancle</button>
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-primary">Save Changes</button>
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-primary">Send minutes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('javascript')
    <script src="{{ asset('/js/bootstrap-colorpicker.js') }}"></script>
     <script src="{{ asset('/js/add_minute.js') }}"></script>
@stop