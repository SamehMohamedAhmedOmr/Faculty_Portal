@extends('/layouts/layout')

{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/form.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/Emails.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}


{{--start section--}}
@section('content')
    <div class="container-fluid" style="margin: 70px auto 100px;">
        <div class="row justify-content-center">
            <div class="col-md-10 profileCard">
                {{-- Header --}}
                <h2 class="headerName">E-mails</h2>
                <input type="hidden" class="myName" value="{{Auth::user()->name_en }}">
                <input type="hidden" class="myEmail" value="{{ Auth::user()->email }}">
                {{--End Header--}}

                {{--get errors--}}
                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}
                                    <i class="fa fa-times" style="float: right;"></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- ENd Error --}}

                {{-- Start Success message --}}
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                        <i class="fas fa-check-square" style="float: right;"></i>
                    </div>
                @endif
                {{-- End Sucess message --}}

                {{--Mail UI--}}
                <div class="row" style="padding: 10px;">
                    <aside class="MailSideBar col-4">

                        <div class="Maillogo-container">
                            <span class="logo glyphicon glyphicon-envelope "><i class="far fa-envelope-open"></i></span>
                            <span class="logoText">Mail</span>
                        </div>

                        <div class="row">
                            <a class="Mailcompose-button btn-block">Compose</a>
                        </div>

                        <menu class="menu-segment">
                            <ul>
                                <li  id="inboxMail" @if(Request::is('Email/inbox'))class="active"  @endif ><a>Inbox<span>@if(Request::is('Email/inbox')) {{ '('.$inbox->total().')' }} @else {{ '('.$totalInbox.')' }} @endif </span></a></li>
                                <li  id="sendMail" @if(Request::is('Email/Send'))class="active"  @endif  ><a>Sent @if(Request::is('Email/Send')) {{ '('.$send->total().')' }} @else {{ '('.$totalSend.')' }} @endif</a></li>
                            </ul>
                        </menu>
                        <hr style="border: 1px solid #008bc4; width: 75%;">

                        <div class="searchMails row">
                            <label class="form-control">Search Panel</label> <br>

                            <input type="text" class="form-control" placeholder="Search By E-Mail">
                            <input type="text" class="form-control" placeholder="Search By Subject" style="margin-top: 10px; margin-bottom: 100px;">
                        </div>
                    </aside>


                    {{-- ---------------------------------------InboxMails-------------------------------------- --}}
                    @if(Request::is('Email/inbox'))
                    <div class="content col-8 inboxMail">
                        <header class="Mailheader">
                            <h4 class="page-title" style="display: inline-block;">
                                <a class="sidebar-toggle-btn trigger-toggle-sidebar">
                                    <span class="line"></span><span class="line"></span>
                                    <span class="line"></span><span class="line line-angle1"></span>
                                    <span class="line line-angle2"></span></a>Inbox<a>
                                    <span class="icon glyphicon glyphicon-chevron-down"></span>
                                </a> &nbsp;&nbsp;
                                <i class="far fa-envelope-open"></i>
                            </h4>
                            <div style="margin-bottom: 0; font-size: 12px; font-family: 'Fira Code Medium'; float: right" >
                                {{ $inbox->total() }} Total Msgs <br>
                                <b style="font-size: 12px; color: #268bc4;"> In this page {{ $inbox->count() }} Msg</b>
                            </div>
                        </header>
                        <div class="clearfix"></div>
                        <br>
                        <div class="msgsContainer">
                            <ul class="msgsList">
                                <li class="row" style="background-color: #ddd;">

                                    <div class="col-4">
                                        <div class="subject">Sender &nbsp;<i class="fas fa-user"></i></div>
                                    </div>

                                    <div class="col-6">
                                        <div class="subject">Subject &nbsp;<i class="fas fa-book"></i></div>
                                    </div>

                                    <div class="col-2">
                                        <div class="date">date &nbsp;<i class="far fa-clock"></i></div>
                                    </div>
                                </li>
                            @foreach($inbox as $mail)
                                <li class="row MailRecord" style="font-size: 13px;">
                                    <input type="hidden" class="type" value="inbox">
                                    <div class="col-4">
                                        <div class="senderName">{{ $mail->sender->name_en }}</div>
                                        <div class="senderEmail" style="display: none;" >{{ $mail->sender->email }}</div>
                                    </div>

                                    <div class="col-6">
                                        <div class="subject">{{ $mail->header }}</div>
                                    </div>

                                    <div class="body" style="display: none;">
                                        {{ $mail->description }}
                                    </div>

                                    <div class="col-2">
                                        <div>{{ Carbon\Carbon::parse($mail->date_time)->format('d/m , H:i') }}</div>
                                        <div class="date" style="display: none;">{{ Carbon\Carbon::parse($mail->date_time)->format('F j, Y g:i:s a') }}</div>

                                    </div>
                                </li>
                            @endforeach
                            </ul>
                            <div class="pagination text-center" style="margin: 25px auto;"> {{ $inbox->links() }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- ---------------------------------------SendMails-------------------------------------- --}}

                    @if(Request::is('Email/Send'))
                    <div class="content col-8 SendMail">
                        <header class="Mailheader">
                            <h4 class="page-title" style="display: inline-block;">
                                <a class="sidebar-toggle-btn trigger-toggle-sidebar">
                                    <span class="line"></span><span class="line"></span
                                    ><span class="line"></span><span class="line line-angle1"></span>
                                    <span class="line line-angle2"></span></a>Send Messages<a>
                                    <span class="icon glyphicon glyphicon-chevron-down"></span>
                                </a> &nbsp;&nbsp;
                                <i class="fab fa-studiovinari"></i>
                            </h4>
                            <div style="margin-bottom: 0; font-size: 12px; font-family: 'Fira Code Medium'; float: right" >
                                {{ $send->total() }} Total Msgs <br>
                                <b style="font-size: 12px; color: #6ba;"> In this page {{ $send->count() }} Msg</b>
                            </div>
                        </header>
                        <div class="clearfix"></div>
                        <br>
                        <div class="msgsContainer">
                            <ul class="msgsList">
                                <li class="row" style="background-color: #ddd;">

                                    <div class="col-4">
                                        <div class="reciever">receiver &nbsp;<i class="fas fa-user"></i></div>
                                    </div>

                                    <div class="col-6">
                                        <div class="subject">Subject &nbsp;<i class="fas fa-book"></i></div>
                                    </div>

                                    <div class="col-2">
                                        <div class="date">date &nbsp;<i class="far fa-clock"></i></div>
                                    </div>
                                </li>
                                @foreach($send as $mail)
                                    <li class="row MailRecord" style="font-size: 13px;">
                                        <input type="hidden" class="type" value="send">
                                        <div class="col-4">
                                            <div class="receiverName">{{ $mail->receiver->name_en }}</div>
                                            <div class="receiverEmail" style="display: none;" >{{ $mail->receiver->email }}</div>
                                        </div>

                                        <div class="col-6">
                                            <div class="subject">{{ $mail->header }}</div>
                                        </div>

                                        <div class="body" style="display: none;">
                                            {{ $mail->description }}
                                        </div>

                                        <div class="col-2">
                                            <div>{{ Carbon\Carbon::parse($mail->date_time)->format('d/m , H:i') }}</div>
                                            <div class="date" style="display: none;">{{ Carbon\Carbon::parse($mail->date_time)->format('F j, Y g:i:s a') }}</div>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="pagination text-center" style="margin: 25px auto;"> {{ $send->links() }}</div>
                        </div>
                    </div>
                    @endif
                {{--End Mail UI--}}

                {{-- start Message UI--}}
                    <div id="message" style="display: none;">
                        <div class="header">
                            <h1 class="page-title">
                                <i class="fas fa-backward"></i>
                                <b class="headerText"> </b>
                            </h1>
                            <p>

                                From <a  href="#" class="from"  style="color: #008bc4;font-weight: bold;"></a>
                                to  <a href="#" class="to" style="color: #008bc4;font-weight: bold;"></a> &nbsp;
                                , &nbsp; started on <a class="date" href="#" style="color: #008bc4;font-weight: bold;"></a>
                            </p>
                        </div>
                        <br>

                        <div id="message-content" style="height: 80%;" class="nano has-scrollbar">

                            <div class="details">
                                <div  style="float: left;">&nbsp;&nbsp;&nbsp;
                                    <b class="from"></b>&nbsp;&nbsp;
                                    <i class="fas fa-arrow-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <b class="to"></b>
                                </div>
                                <div  style="float: right" class="date"></div>
                            </div>
                            <p class="body" style="padding: 15px;"></p>
                        </div>
                    </div>
                {{--ENd Message UI--}}

                {{-- start compose Message --}}
                    <div class="composeMessage" style="padding: 15px; display: none; width: auto; height: auto;">
                        <div class=""><div class="" style="overflow: auto; width: 815px; height: 470px;">
                        <div id="fancy2" style="display: block;">
                        <h4>Compose Message</h4>
                            <form action="/Email/store" method="POST">
                                @csrf
                                <fieldset class="mail" style="width: 100% !important;"><input name="reciever" placeholder="To: example xxxx@xxx.xx..." type="email" required></fieldset>
                                <fieldset class="subject" style="width: 100% !important;"> <input name="header" placeholder="heading..." type="text" required>  </fieldset>
                                <fieldset class="question" style="width: 100% !important;">
                                    <textarea name="message" placeholder="Message..." required></textarea>
                                </fieldset>
                                <input type="hidden" name="senderId" value="{{ Auth::user()->userable_id }}">
                                <div class="btn-holder" style="padding-top: 12px;">
                                    <button class="btn btn-block btn-outline-dark" style="line-height: normal; width: 100%;" type="submit">Send Message <i class="far fa-share-square"></i> </button>
                                </div>
                            </form>
                        </div></div></div><a title="Close" class=""><i class="close fas fa-times-circle"></i></a>
                    </div>
                {{-- ENd compose message--}}
            </div>
        </div>
    </div>
</div>
@endsection
{{--end section--}}
@section('scripts')
    <script src=" {{ asset('/js/frontend/mails.js') }}"></script>
@endsection
