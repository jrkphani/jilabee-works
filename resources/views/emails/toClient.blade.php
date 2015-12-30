Dear Customer,
<br/>
@if($state == 'new')
We would like to acknowledge that we have received your request and a ticket has been created.
@elseif($state == 'closed')
We would like to inform you that with reference to the below ticket resolved.
<br/>
Thanks for using to register the tickets. If you need more clarification, kindly contact our personal support representative. Email: gokul.s@anabond.com
@elseif($state == 'cancelled')
	We would like to acknowledge that we have received your request and a ticket has been <b>Cancelled</b>.
<br>
<b>If you need more clarification, contact our personal support representative gokul.s@anabond.com</b>
@endif
<br/>
Ticket No: {{$task->id}}
<br/>
Title:{{$task->title}}
<br/>
<br/>
Regards
<br/>
Support Team