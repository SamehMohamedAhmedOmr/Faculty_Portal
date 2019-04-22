@extends('/layouts/layout')

{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/gallery.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}

{{--start section--}}
@section('content')

    <div class="divider"></div>

    <div class="content">
        <div class="container gallery-view">
            @if(Auth::check())
                @if(Auth::user()->userable_type=='Adm')
                    <button class="btn add-album-btn" onclick=""><a href=" {{ '/gallery/albums/create' }} ">New Album</a></button>
                    {{--<a href=" {{ '/events/create' }} " class="btn add-event-btn"> Add Event </a>--}}
                @endif
            @endif
            {{-- Start Success message --}}
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                    <i class="fas fa-check-square"></i>
                </div>
            @endif
            {{-- End Sucess message --}}
            <h1 class="single"> </h1>
            <div class="main-content">
                @if(sizeof($albums)>0)
                    @if($images->count()>0)
                        <div class="slider-con" style="margin: auto">
                            <ul class="bxslider">
                                @foreach($images as $image)
                                    <li>
                                        <div class="slide">
                                            @if(Auth::check())
                                                @if(Auth::user()->userable_type=='Adm')
                                                    <ul>
                                                        <form action="/gallery/delete/{{$image->id}}">
                                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                                            <button class="col-12 btn del-img-btn"> Remove </button>
                                                        </form>
                                                    </ul>
                                                @endif
                                            @endif
                                            <ul>
                                                <li style="height: 500px;width: 700px;">
                                                    <a href="#">
                                                        <img src="/images/frontend/gallery/{{$image->filename}}" alt="img" style="height: 100%;width: 100%;">
                                                    </a>

                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        @if($func==1)
                            <h3 class="albm-hint">Select an album</h3>
                            <hr>
                        @else
                            <h3 class="albm-hint">This album is empty</h3>
                            <hr>
                        @endif
                    @endif
                @else
                    <h3 class="albm-hint">There aren't any albums to show.</h3>
                    <hr>
                @endif
            </div>

            <aside id="sidebar">
                <div class="widget sidemenu">
                    <ul>
                        @foreach($albums as $album)
                            <li class="albm-li" >
                                @if(Auth::check())
                                    @if(Auth::user()->userable_type=='Adm')
                                        <form method=\"GET\" action={{ '/gallery/upload/'.$album->id }}>
                                            <meta name="csrf-token" content="{{ csrf_token() }}">

                                            <a href={{ '/gallery/albums/'. $album->id }}>{{ $album->name }}
                                                <button class='btn add-img'> + </button>
                                            </a>
                                        </form>

                                        <form method=\"GET\" action={{ '/gallery/albums/'.$album->id.'/edit' }}>
                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                            <button class='btn edit-album'> Edit </button>
                                        </form>

                                        <form action={{ '/gallery/albums/'.$album->id }} method="post" >
                                            @method('delete')
                                            {{ csrf_field() }}
                                            <button class="btn del-album"> Remove </button>
                                        </form>

                                    @else
                                        <a href={{ '/gallery/albums/'. $album->id }}>{{ $album->name }}</a>
                                    @endif
                                @else
                                    <a href={{ '/gallery/albums/'. $album->id }}>{{ $album->name }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
            <!-- / container -->
        </div>
    </div>

@endsection
{{--end section--}}