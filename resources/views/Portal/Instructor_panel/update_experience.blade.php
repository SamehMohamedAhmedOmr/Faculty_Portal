    <br>
    <h3 class="header text-center"> Edit Experience </h3>

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

    <form class="form-horizontal" action="{{url('/Panel/experience/'.Auth::user()->userable_id .'*'.$exp->date)}}" class="form-group" method="POST">
        @method('PATCH')
        @csrf
        {{--{{ method_field('PUT') }}--}}

        <div class="form-group">
            <label class="control-label col-sm-12" for="subject" style="font-family: aakar;">Subject:</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="subject" placeholder="Enter subject for your experience" value="{{$exp->subject}}">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12" for="description" style="font-family: aakar;">Description:</label>
            <div class="col-sm-12">
                <textarea style="resize: none;" rows="5" class="form-control" name="description" placeholder="Enter description for your experience">{{$exp->description}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-block btn-dark text-center">Save</button>
            </div>
        </div>
</form>
