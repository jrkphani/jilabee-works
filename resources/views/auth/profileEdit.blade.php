@extends('master')
@section('content')

        <div class="profile_div">
             <div class="panel panel-default">
                <div class="profile_edit_title">Update</div>
                <div class="panel-body">
                {!! Form::open(['class' => 'form-horizontal','method'=>'POST']) !!}
                   <div class="profile_edit_row">
                        <label>Name</label>
                        <div>
                           <input type="text" class="form-control" name="name" value="{{ $profile->name }}">
                           {!! $errors->first('name','<div class="error">:message</div>') !!}
                        </div>
                        <div class="clearboth"></div>
                   </div>
                   <div class="profile_edit_row">
                        <label>E-Mail Address</label>
                        <div>
                          {{ $profile->user->email }}
                        </div>
                         <div class="clearboth"></div>
                   </div>

                   <div class="profile_edit_row">
                        <label>Password</label>
                        <div>
                            <input type="password" autocomplete ='off' class="form-control" name="password">
                            {!! $errors->first('password','<div class="error">:message</div>') !!}
                        </div>
                         <div class="clearboth"></div>
                   </div>
                   <div class="profile_edit_row">
                        <label>Confirm Password</label>
                        <div >
                           <input type="password" autocomplete ='off' class="form-control" name="password_confirmation">
                        </div>
                         <div class="clearboth"></div>
                   </div>
                   <div class="profile_edit_row">
                        <label>Phone</label>
                        <div>
                           <input type="text" class="form-control" name="phone" value="{{ $profile->phone }}">
                           {!! $errors->first('phone','<div class="error">:message</div>') !!}
                        </div>
                         <div class="clearboth"></div>
                   </div>
                   <div class="profile_edit_row">
                        <label>DOB</label>
                        <div>
                           <input type="text" class="form-control" id="dob" name="dob" value="{{ $profile->dob }}">
                           {!! $errors->first('dob','<div class="error">:message</div>') !!}
                        </div>
                         <div class="clearboth"></div>
                   </div>
                  <div class="profile_edit_row">
                        <label>Gender</label>
                        <div>
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
                         <div class="clearboth"></div>
                  </div>
                  
                  <div class="profile_edit_row">
                        <div >
                           <a class="login_loginbtn login_register_back" href="{{ URL::previous() }}">Back</a> 
                           <button type="submit" class="login_loginbtn profile_edit_btn">Update</button>
                        </div>
                        <br/>
                        <br/>

                  </div>
                {!! Form::close() !!}
           </div>
        </div>
    </div>

@endsection
@section('javascript')
<script src="{{ asset('/js/jquery.simple-dtpicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function($)
  {
    curentval = $('#dob').val();
     d= new Date();
      d.setFullYear(d.getFullYear()-15);
      $('#dob').appendDtpicker(
          {
          "autodateOnStart": false,
          "maxDate":d,
          "closeOnSelected": true,
          "dateOnly":true
          });
      $('#dob').handleDtpicker('setDate',d);
      $('#dob').val(curentval);
  });
  </script>
@endsection
@section('css')
<link href="{{ asset('/css/jquery.simple-dtpicker.css') }}" rel="stylesheet">
@endsection