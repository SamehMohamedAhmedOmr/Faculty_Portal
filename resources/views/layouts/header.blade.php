<!DOCTYPE html>
<!--[if IE 8]> <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>FCIH Helwan iniversity</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <link rel="stylesheet" media="all" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/timetableView.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/header.css') }}">
    <!-- Font Awesome latest CDN-->
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <!-- Bootstrap Latest CDN CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    @yield('cssStyle')
</head>
<body>

<header id="header">
    <div class="container col-12">
        <!-- loginIcon  -->
        <div class="userNav col-12" >
            @if(!Auth::check())

                <a style="float: left; padding-right: 15px" href="/login">login <i class="fas fa-sign-in-alt"></i></a>
            @else
                <span style="float: right;"><i class="fas fa-user" style="margin-right: 10px;"></i>  <a href="/profile">{{ Auth::user()->name_en }}</a></span>
                <a style="float: left; padding-right: 15px" href="/logout">logout <i class="fas fa-sign-out-alt"></i></a>
            @endif
        </div>
        <div class="clearfix"></div>




        <a href="/home" id="logo" title="FCIH College">FCIH College</a>
        <div class="menu-trigger" style="background-color: #fff; padding: 1px; color: #fff !important;border-radius: 3px;"></div>
        <nav id="menu">
            @if(Auth::check())
                <ul>
                    <li><a href="/profile">Profile</a></li>
                    <li><a
                        @if(Auth::user()->userable_type=='Adm')
                             href="{{ action('AdminController@index') }}"
                        @endif

                        @if(Auth::user()->userable_type=='Doc')
                             href="/Doctor/Panel"
                        @endif

                        @if(Auth::user()->userable_type=='T_A')
                        href="/Instructor/Panel"
                        @endif

                        @if(Auth::user()->userable_type=='S_A')
                        href="/StudentAffair/Panel"
                        @endif

                        @if(Auth::user()->userable_type=='Stu')
                        href="/student/panel"
                        @endif

                        >MyPanel</a></li>
                    <li><a href="/Email/inbox">Emails</a></li>
                </ul>
            @else
                {{--<ul>--}}
                    {{--<li><a href="/events">Events</a></li>--}}
                    {{--<li><a href="/gallery/albums">Gallery</a></li>--}}
                    {{--<li><a href="#fancy" class="get-contact">Contact</a></li>--}}
                {{--</ul>--}}

            @endif
            <ul style="margin-top: 5%;">
                <li><a href="/home">Home</a></li>
                <li><a href="/events">Events</a></li>
                <li><a href="/gallery/albums">Gallery</a></li>
                <li><a href="#fancy" class="get-contact">Contact</a></li>
            </ul>
        </nav>
        <!-- / navigation -->
    </div>
    <!-- / container -->

</header>
<!-- / header -->