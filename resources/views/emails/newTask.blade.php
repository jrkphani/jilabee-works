Hi {{$user->profile->name}},<br/>
New ticket has been assigned to you.<br/>
Please login for further action.<br/>
<a href="{{url('/jobs?&tid='.$task->id)}}">Ticket#{{$task->id}}</a>
<br/>
<br/>
Regards
<br/>
Support Team