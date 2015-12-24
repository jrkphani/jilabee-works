Hi,<br>
@if($task->status == 'Closed')
	{{$task->title}} issue is closed<br>
@else
	New ticket create {{$task->title}}<br>
	Will resolve ASAP.
@endif
<br>
<br>
Thanks
Regards