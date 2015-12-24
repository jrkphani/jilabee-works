Hi {{$user->profile->name}},<br>
New ticket has beed assigned for you.<br>
Please login for further action.<br>
<a href="{{url('/jobs?&tid='.$task->id)}}">{{url('/jobs?&tid='.$task->id)}}</a>
<br>
<br>
Thanks
Regards