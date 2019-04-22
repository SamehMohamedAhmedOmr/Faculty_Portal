    <br>
    <h3 class="header text-center"> Add New Experience </h3>
    <form class="form-horizontal" action="{{url('/Panel/experience')}}" class="form-group" method="POST">
        {{--get errors--}}
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

        @csrf
        <div class="form-group">
            <label class="control-label col-sm-12" for="subject" style="font-family: aakar;">Subject:</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Enter subject for your experience">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12" for="description" style="font-family: aakar;">Description:</label>
            <div class="col-sm-12">
                <textarea style="resize: none;" rows="5" class="form-control" name="description" placeholder="Enter description for your experience">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-block btn-dark text-center">Submit</button>
            </div>
        </div>
</form>
