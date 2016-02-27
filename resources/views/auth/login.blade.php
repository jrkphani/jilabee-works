<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jotter - Login</title>
    <link rel="icon" href="favicon.ico" type="image/gif" sizes="16x16">

    <link type="text/css" rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" media="all" />
    <!-- <link type="text/css" rel="stylesheet" href="{{asset('css/jquery.mCustomScrollbar.css')}}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{asset('css/bootstrap-theme.min.css')}}" media="all">
    <link type="text/css" rel="stylesheet" href="{{asset('css/jquery.jscrollpane.css')}}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}" media="all" /> -->
    <link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}" media="all" />

    <!-- <script src="{{asset('js/prettify.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-1.11.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.toaster.js')}}"></script> -->

</head>
<body>
<div id="wrapper">
    <div class="login-wrapper">
        <div class="login-warp">
            <form action="" method="post" id="sign-form" novalidate="novalidate">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="login-cont">
                <div class="login-header">
                    <p class="login-logo"><img src="{{asset('img/login/login_logo.png')}}"></p>
                    <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, </p>
                </div>
                <div class="textEdit-wrap">
                    <img src="{{asset('img/login/ico_login.png')}}">
                    <div class="textBox-wrap">
                        <p><input type="text" name="email" placeholder="Username" class="txt-input" required></p>
                        {!! $errors->first('email','<div class="error">:message</div><br/>') !!}
                        <p><input type="password" name="password" placeholder="Password" class="txt-input" required></p>
                        {!! $errors->first('password','<div class="error">:message</div><br/>') !!}
                        <div class="text-extra">
                            <p>
                                <input id="checkbox" class="checkbox-custom" name="checkbox" type="checkbox">
                                <label for="checkbox">Remember me</label>
                            </p>
                            <p class="btn-forget"><a href="{{ url('/password/email') }}">forgot password</a></p>
                        </div>
                    </div>
                </div>
                <p class="btn-login"><input class="class="text-center text-uppercase"" type="submit" value="Login"></p>
            </div>
                </form>
        </div>
    </div>
</div>
</body>
</html>
</html>