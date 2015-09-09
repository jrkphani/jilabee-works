@if ($breadcrumbs)
	<div class="breadcrumbs">
		 @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <a href="{{{ $breadcrumb->url }}}">{{$breadcrumb->title}}&nbsp;/&nbsp;</a>
            @else
            <a class="active">{{$breadcrumb->title}}</a>
            @endif
       @endforeach
	</div>
@endif