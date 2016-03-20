@extends('master')
@section('css')		
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="{{ asset('/css/jquery.datetimepicker.css') }}" rel="stylesheet">
@stop
@section('content')
<div class="header-row2">
        <h2>Meetings</h2>
        <div class="header-sort">
            <select name="meeting_id" id="meeting_id" tabindex="1">
                <option>SELECT MEETING</option>
                <option>Meeting name 01 </option>
                <option>Meeting name 02</option>
                <option>Meeting name 03</option>
            </select>
        </div>
        <div class="header-sort">
            <select name="sort_id" id="sort_id" tabindex="2">
                <option>Sort By</option>
                <option>Assignee</option>
                <option>Marketing Team</option>
                <option>Management</option>
            </select>
        </div>
        <div class="header-reset">
            <input class="sb_reset" type="submit" value="Reset">
        </div>

        <a href="create-followup.html" class="btn-inter btn-create-task">+ create  meeting</a>
    </div>


    <div class="inner-container follow">

        <div class="inner-page">
            <h2 class="sub-title">Meeting ID #{{$meeting->id}} <a href="#" class="btn-close">X</a> </h2>
            <div class="meeting-header no-print">
                <div class="meeting-head-left">
                    <a href="#" class="btn-history-gray">History</a>
                    <ul>
                    @foreach($minutes as $minut)
                    	<li><a href="#" class="active"><span>{{date('d',strtotime($minut->startDate))}}</span>{{ date('M Y',strtotime($minut->startDate)) }}</a></li>
                    @endforeach
                        <li><a href="#"><span>+</span>Add New</a></li>
                    </ul>
                </div>

                <div class="meeting-head-right">
                    <ul>
                        <li><a href="javascript:window.print();" class="btn-print">Print</a></li>
                    </ul>
                </div>
            </div>


            <div class="admin-meeting-wrap margin0">
                <form>

                <div class="admin-create-cont">
                    <div class="admin-create-list">
                        <div class="admin-create-left"><img src="img/admin/logo.jpg"/></div>
                        <div class="admin-create-right">
                            <p>
                                <span class="text-bold">Anabond Limited</span>
                                <span>|</span>
                                <span>Minutes of Meeting</span>
                            </p>
                            <h1>{{$meeting->title}}</h1>
                            <ul class="meeting-details-wrap">
                                <li class="meeting-details-date">{{date('d M Y',strtotime($minute->startDate))}}</li>
                                <li class="meeting-details-time">{{date('H:i A',strtotime($minute->startDate))}}</li>
                                <li class="meeting-details-venue">{{$minute->venue}}</li>
                            </ul>

                               <article>
                                   {!!$meeting->description!!}
                               </article>

                        </div>
                    </div>
                    <div class="admin-create-list">
                        <div class="admin-create-left">Minute Taker</div>
                        <div class="admin-create-right">
                            <p>
								@foreach(App\Model\Profile::whereIn('userId',explode(',',$meeting->minuters))->lists('name','userId') as $minuter)
								{{$minuter}} ,
								@endforeach
                            </p>
                        </div>
                    </div>
                    <div class="admin-create-list">
                        <div class="admin-create-left">Participants</div>
                        <div class="admin-create-right">
                            <p>
                            @foreach(explode(',',$minute->attendees) as $attendee)
								@if(isEmail($attendee))
									{{$attendee}} ,
								@else
								<?php $attendees[]=$attendee; ?>
								@endif
							@endforeach
							@if(isset($attendees))
								@foreach(App\Model\Profile::whereIn('userId',$attendees)->lists('name','userId') as $attendee)
								{{$attendee}} ,
								@endforeach
							@endif
                            </p>
                        </div>
                    </div>
                    <div class="admin-create-list">
                        <div class="admin-create-left">Absentees</div>
                        <div class="admin-create-right">
                            <p>@foreach(explode(',',$minute->absentees) as $absentee)
									@if(isEmail($absentee))
										{{$absentee}} ,
									@else
									<?php $absentees[]=$absentee; ?>
									@endif
								@endforeach
								@if(isset($absentees))
									@foreach(App\Model\Profile::whereIn('userId',$absentees)->lists('name','userId') as $absentees)
										{{$absentees}} ,
									@endforeach
								@endif
								</p>
                        </div>
                    </div>
                </div>

            </form>

            <div class="meeting-new">
                <div class="meeting-new-title">
                    <div class="meeting-sl"><strong>No.</strong></div>
                    <div class="meeting-topic"><strong>Topic</strong></div>
                    <div class="meeting-owner"><strong>Owner</strong></div>
                    <div class="meeting-ddate"><strong>Due Date</strong></div>
                    <div class="meeting-status"><strong>Status</strong></div>
                </div><!-- 

                <div class="meeting-new-box">
                    <div class="meeting-sl">1</div>
                    <div class="meeting-topic"><input type="text" class="meet-name" placeholder="Topic"></div>
                    <div class="meeting-owner"><input type="text" class="meet-owner" placeholder="Owner"></div>
                    <div class="meeting-ddate"><input type="text" class="meet-ddate" placeholder="Due Date"></div>
                    <div class="meeting-status">
                        <select><option>Open</option><option>Close</option></select>
                    </div>
                    <div class="meeting-desc">
                        <textarea class="meet-textarea" placeholder="Meeting Description"></textarea>
                    </div>
                </div>
                <a href="#" class="btn-inter btn-add-meet">+ Add New</a> -->

            </div>
            @foreach($minute->tasks as $task)
            <div class="meeting-box">
                <!-- <div class="meeting-top">
                    <ul class="meeting-details-wrap">
                        <li class="meeting-details-date">{{date('d M Y',strtotime($task->dueDate))}}</li>
                        <li class="meeting-details-time">{{date('H:i A',strtotime($task->dueDate))}}</li>
                        <li class="meeting-details-venue">Kurunji Hall, Chennai</li>
                    </ul>
                </div> -->

                <div class="meeting-content">
                    <div class="meeting-sl"><strong>{{ $task->id }}</strong></div>
                    <div class="meeting-topic"><strong>{{ $task->title }}</strong></div>
                    <div class="meeting-owner"><strong>{{ $task->assigneeDetail->name }}</strong></div>
                    <div class="meeting-ddate"><strong>{{date('d M Y H:i',strtotime($task->dueDate))}}</strong></div>
                    <div class="meeting-status">
                        <select><option>Open</option><option>Close</option></select>
                    </div>
                    <div class="meeting-desc">
                        {!! $task->description !!}
                    </div>
                </div>
            </div>
            @endforeach
                <!-- <div class="meeting-pagination">

                    <ul>
                        <li><a href="#" class="btn-prev disable"> << </a> </li>
                        <li> <span>1</span>/7 </li>
                        <li><a href="#" class="btn-next"> >> </a> </li>
                    </ul>

                </div>
 -->

        </div>


        </div>

        <div class="bottom-button no-print">
            <a href="#" class="btn-inter btn-bottom">&lt; Back to Meetings</a>
        </div>

    </div>
@endsection
@section('javascript')
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="{{ asset('/js/jquery.datetimepicker.full.js') }}"></script>
@endsection