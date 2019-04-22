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
            <h3>Add Event</h3>
            <hr>
            <form action="{{'/events'}}" class="form-group" method="POST">

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

                <div class="form-group row">
                    <label for="name" class="col-12 col-form-label" style="margin: auto">Event  Name:</label>
                    <div class="col-7" style="margin: auto">
                        <input
                                type="text"
                                required
                                class="form-control input-sm"
                                placeholder="Enter event name"
                                value="{{ old('name') }}"
                                name="name">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-12 col-form-label" style="margin: auto">Event Description:</label>
                    <div class="col-7" style="margin: auto">
                        <textarea style="resize: none;" rows="5" class="form-control" name="description" placeholder="Enter description for the event">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="place" class="col-12 col-form-label" style="margin: auto">Event Place:</label>
                    <div class="col-7" style="margin: auto">
                        <input
                                type="text"
                                required
                                class="form-control input-sm"
                                placeholder="Enter event place"
                                value="{{ old('name') }}"
                                name="place">
                    </div>
                </div>

                <div class="form-group row" style="margin: auto">
                    <label for="date" class="col-12 col-form-label">Time:</label>
                    <div class="col-7" style="margin: auto">
                        <input
                                type="datetime-local"
                                class="form-control input-sm"
                                name="date"
                                value="{{ old('date') }}"
                                required>
                    </div>
                </div>

                <button class="btn btn-block btn-info col-5 " style="margin: auto; margin-top: 40px;" type="submit"><i class="fa fa-plus"></i></button>
            </form>


        </div>
        <!-- / container -->
    </div>

@endsection
{{--end section--}}

@section('scripts')
    {{--<script src=" {{ asset('/js/frontend/events.js') }}"></script>--}}
@endsection