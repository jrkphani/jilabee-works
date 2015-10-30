@if($notifications)
<ul>
@foreach($notifications as $notification)
	@if($notification->isRead == '1')
	<li class="notification_read">
	@else
	<li>
	@endif
	<?php
    $link = '';
	if($notification->objectType == 'jobs')
        {
            $link = '/jobs/?';
            if($notification->tag == 'history')
            {
                $link = $link.'&history=yes';    
            }
            $link = $link.'&tid='.$notification->objectId;
            if($notification->parentId)
            {
                $link = $link.'&mid='.$notification->parentId;
            }
        }
        else if($notification->objectType == 'followups')
        {
            $link = '/followups/?';
            if($notification->tag == 'history')
            {
                $link = $link.'&history=yes';    
            }
            $link = $link.'&tid='.$notification->objectId;
            if($notification->parentId)
            {
                $link =$link.'&mid='.$notification->parentId;
            }
        }
        else if($notification->objectType == 'meeting')
        {
            $link = '/meetings';
            if($notification->subject == 'user')
            {
                $notification->body = 'New User Added in Meeting';
            }
        }
        else
        {
            $link = '';
        }
        if($notification->isRead == '2')
        {
            $link = '';
        }
        ?>
		<h3>{{$notification->objectType}}</h3>
		{{$notification->body}}
		<p>{{date('Y M d - H:i', strtotime($notification->updated_at))}}</p>
        @if($link)
            <span class="notification_go_btn">
                <a href="{{$link}}">Go</a>
            </span>
        @endif
		<span class="notification_left_bar"></span>
	</li>
@endforeach
</ul>
<div id="ajaxPagination">
    {!! $notifications->render() !!}
</div>
@else
No Notifications
@endif