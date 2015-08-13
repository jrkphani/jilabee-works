@extends('master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
             <div class="panel panel-default">
                <div class="panel-heading">Update</div>
                <div class="panel-body">
                {!! Form::open(['class' => 'form-horizontal','method'=>'POST']) !!}
                   <div class="form-group">
                        <label class="col-md-4 control-label">Name</label>
                        <div class="col-md-6">
                           <input type="text" class="form-control" name="name" value="{{ $profile->name }}">
                           {!! $errors->first('name','<div class="error">:message</div>') !!}
                        </div>
                   </div>
                   <div class="form-group">
                        <label class="col-md-4 control-label">E-Mail Address</label>
                        <div class="col-md-6">
                          {{ $profile->email }}
                        </div>
                   </div>

                   <div class="form-group">
                        <label class="col-md-4 control-label">Password</label>
                        <div class="col-md-6">
                            <input type="password" autocomplete ='off' class="form-control" name="password">
                            {!! $errors->first('password','<div class="error">:message</div>') !!}
                        </div>
                   </div>
                   <div class="form-group">
                        <label class="col-md-4 control-label">Confirm Password</label>
                        <div class="col-md-6">
                           <input type="password" autocomplete ='off' class="form-control" name="password_confirmation">
                        </div>
                   </div>
                   <div class="form-group">
                        <label class="col-md-4 control-label">Phone</label>
                        <div class="col-md-6">
                           <input type="text" class="form-control" name="phone" value="{{ $profile->phone }}">
                           {!! $errors->first('phone','<div class="error">:message</div>') !!}
                        </div>
                   </div>
                   <div class="form-group">
                        <label class="col-md-4 control-label">DOB</label>
                        <div class="col-md-6">
                           <input type="text" class="form-control" name="dob" value="{{ $profile->dob }}">
                           {!! $errors->first('dob','<div class="error">:message</div>') !!}
                        </div>
                   </div>
                  <div class="form-group">
                        <label class="col-md-4 control-label">Gender</label>
                        <div class="col-md-6">
                                <?php $male = $female = false;
                                    if($profile->gender == 'F')
                                        $female = true;
                                    else
                                        $male = true;
                                ?>

                           {!!Form::radio('gender', 'M',$male,['autocomplete'=>'off']) !!} Male 
                           {!!Form::radio('gender', 'F', $female,['autocomplete'=>'off']) !!} Female
                           {!! $errors->first('gender','<div class="error">:message</div>') !!}
                        </div>
                  </div>
                  
                  <div class="form-group">
                        <div class="col-md-3 col-md-offset-3">
                           <a class="btn btn-primary" href="{{ URL::previous() }}">Back</a> 
                        </div>
                        <div class="col-md-3 col-md-offset-3">
                           <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                  </div>
                {!! Form::close() !!}
           </div>
        </div>
    </div>
  </div>
</div>
@endsection