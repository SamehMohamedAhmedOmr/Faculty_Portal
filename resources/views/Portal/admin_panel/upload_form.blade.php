@extends('/layouts/layout')

{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/events.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}


{{--start section--}}

@section('content')

    <div class="divider"></div>

    <div class="content">
        <div class="container add-event-view">
            <hr>
            <h3>Add photos to {{ $album->name }} album</h3>
            <hr>
            <form action="{{'/gallery/upload'}}" class="form-group" method="post" enctype="multipart/form-data">

                @method('POST')
                @csrf

                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="alert alert-danger">{{ $error }}
                                    <i class="fa fa-times" style="float: right "></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{--<div class="form-group row">--}}
                    {{--<label for="name" class="col-12 col-form-label" style="margin: auto">Album  Name:</label>--}}
                    {{--<div class="col-7" style="margin: auto">--}}
                        {{--<input--}}
                                {{--type="text"--}}
                                {{--required--}}
                                {{--class="form-control input-sm"--}}
                                {{--placeholder="Enter album name"--}}
                                {{--value="{{ old('name') }}"--}}
                                {{--name="name">--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="form-group row" style="margin: auto">
                    <label for="filename" class="col-12 col-form-label">Upload Album Photo:</label>
                    <div class="col-7" style="margin: auto">
                        <input class="form-control input-sm" type="file" name="photo" required />
                    </div>
                </div>

                <input type="hidden" name="id" value="{{ $album->id }}">

                <button class="btn btn-block btn-info col-5 " value="Upload" style="margin: auto; margin-top: 40px;" type="submit"><i class="fa fa-plus"></i></button>
            </form>


        </div>
        <!-- / container -->
    </div>

@endsection
{{--end section--}}

@section('scripts')
    {{--<script src=" {{ asset('/js/frontend/events.js') }}"></script>--}}
@endsection