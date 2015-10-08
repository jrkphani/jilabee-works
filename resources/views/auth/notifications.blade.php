<ul>
@foreach($notifications as $notification)
	@if($notification->isRead == '1')
	<li class="notification_read">
	@else
	<li>
	@endif
		<h3>{{$notification->objectType}}</h3>
		<a href="#">{{$notification->body}}</a>
		<p>{{date('Y M d - H:i', strtotime($notification->updated_at))}}</p>
		<span class="notification_go_btn">Go</span>
		<span class="notification_left_bar"></span>
	</li>
@endforeach
</ul>