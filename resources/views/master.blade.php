<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jotter - Jobs</title>
    <link rel="icon" href="{{asset('/img/favicon.ico')}}" type="image/gif" sizes="16x16">
    <link type="text/css" rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}" media="all" />
    <!--link type="text/css" rel="stylesheet" href="css/jquery.mCustomScrollbar.css" media="all" /-->
    <link type="text/css" rel="stylesheet" href="{{asset('/css/bootstrap-theme.min.css')}}" media="all">
    <link type="text/css" rel="stylesheet" href="{{asset('/css/jquery.jscrollpane.css')}}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{asset('/css/jquery-ui.min.css')}}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{asset('/css/style.css')}}" media="all" />
    @yield('css')  
</head>
<body>

<div id="wrapper">
{!! Form::hidden('_token', csrf_token(),['id'=>'_token']) !!}
    <header>
        <div class="header-row1">
            <div class="header-left">
                <div class="logo">
                    <a href="#"><img src="{{asset('/img/logo-jotter.gif')}}" /></a>
                </div>
            </div>
            <div class="header-right">
                <nav>
                    <ul>
                    <li>
						@if(Request::segment(1) == 'jobs' || Request::segment(1) == NULL)
						<a class="active" id="jobs">Jobs</a>
						@else
							<a href="{{ url('jobs') }}" id="jobs">Jobs</a>
						@endif
					</li>
					<li>
						@if(Request::segment(1) == 'followups')
							<a class="active">Followups</a>
						@else
							<a href="{{ url('followups') }}">Follow Ups</a>
						@endif
					</li>
					<li>
						@if(Request::segment(1) == 'meetings')
							<a class="active">Meetings</a>
						@else
							<a href="{{ url('meetings') }}">Meetings</a>
						@endif
					</li>
                    </ul>
                </nav>
                <div class="header-profile">
                    <div class="profile-pic"><img src="{{asset('/img/profile/img-user.jpg')}}"/> </div>
                    <div class="profile-name">{{Auth::user()->profile()->first()->name}}</div>
                    <div class="profile-set dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><img src="img/ico-setting.png" /> </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile Setting</a></li>
                            <li><a href="#">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <div class="notify">
                    <a href="#" id="note_button" class="btn-notify"><strong>12</strong></a>
                </div>
                <div class="note-block">
                    <div class="note-copy">
                        <h2><span>04</span>Notifications</h2>

                        <div class="note-box unread">
                            <a>
                                <div class="note-status online col-lg-1 col-md-1"></div>
                                <div class="note-icon info col-lg-1 col-md-1"></div>
                                <div class="note-text  col-lg-10 col-md-10">
                                    <h4>Task Description Changed</h4>
                                    <p>Lorem ipsum dolor sit amet conse tetur ipsum dolor sit amet conse the new outstanding jobwork ipsum dolor sit amet conse tetur...</p>
                                    <div class="note-bot row">
                                        <div class="col-lg-1 col-md-1"><img src="{{asset('/img/profile/img-user.jpg')}}" /></div>
                                        <div class="col-lg-7 col-md-7">Optimus Prime</div>
                                        <div class="col-lg-4 col-md-4 text-right">2.10 pm</div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="note-box">
                            <a>
                                <div class="note-status online col-lg-1 col-md-1"></div>
                                <div class="note-icon reject col-lg-1 col-md-1"></div>
                                <div class="note-text  col-lg-10 col-md-10">
                                    <h4>Task Rejected</h4>
                                    <p>Lorem ipsum dolor sit amet conse tetur ipsum dolor sit amet conse the new outstanding jobwork ipsum dolor sit amet conse tetur...</p>
                                    <div class="note-bot row">
                                        <div class="col-lg-1 col-md-1"><img src="{{asset('/img/profile/img-user.jpg')}}" /></div>
                                        <div class="col-lg-7 col-md-7">Optimus Prime</div>
                                        <div class="col-lg-4 col-md-4 text-right">2.10 pm</div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="note-box">
                            <a>
                                <div class="note-status col-lg-1 col-md-1"></div>
                                <div class="note-icon comment col-lg-1 col-md-1"></div>
                                <div class="note-text  col-lg-10 col-md-10">
                                    <h4>Comments</h4>
                                    <p>Lorem ipsum dolor sit amet conse tetur ipsum dolor sit amet conse the new outstanding jobwork ipsum dolor sit amet conse tetur...</p>
                                    <div class="note-bot row">
                                        <div class="col-lg-1 col-md-1"><img src="{{asset('/img/profile/img-user.jpg')}}" /></div>
                                        <div class="col-lg-7 col-md-7">Optimus Prime</div>
                                        <div class="col-lg-4 col-md-4 text-right">2.10 pm</div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="note-box">
                            <a>
                                <div class="note-status col-lg-1 col-md-1"></div>
                                <div class="note-icon accept col-lg-1 col-md-1"></div>
                                <div class="note-text  col-lg-10 col-md-10">
                                    <h4>Task Description Changed</h4>
                                    <p>Lorem ipsum dolor sit amet conse tetur ipsum dolor sit amet conse the new outstanding jobwork ipsum dolor sit amet conse tetur...</p>
                                    <div class="note-bot row">
                                        <div class="col-lg-1 col-md-1"><img src="{{asset('/img/profile/img-user.jpg')}}" /></div>
                                        <div class="col-lg-7 col-md-7">Optimus Prime</div>
                                        <div class="col-lg-4 col-md-4 text-right">2.10 pm</div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="btn-view-all-box">
                            <a href="#" class="btn-view-all">View All</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>
    @yield('content')
    <footer>
    	@yield('footer')
    	{{-- <div style="color:red;">
			{!! Breadcrumbs::render(Request::segment(1)) !!}
			<div class="clearboth"></div>
		</div> --}}
    </footer>
</div>

</body>
<script type="text/javascript" src="{{asset('/js/jquery-1.11.3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jquery.mousewheel.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/app.js')}}"></script>
<script type="text/javascript" src="{{asset('/js/jquery.toaster.js')}}"></script>
@yield('javascript')
</html>