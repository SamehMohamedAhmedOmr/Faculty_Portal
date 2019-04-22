{{--start Style--}}

{{--End Style--}}
<br>
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
{{--Start ManageTimeTable--}}
<div>
    {{--Start Header--}}
    <div class="headerName">{{ 'Manage Time Table' }}</div>
    <div id="overlay"></div>
    {{--End Header--}}

    {{--FIrst Check if the re is any semester or not--}}
    @if(!isset($semester))
        <div class="Not_Found"> {{ 'No Semester exists to Manage ' }}&nbsp;<i class="fas fa-exclamation-triangle"></i></div>
    @else
        {{--Select semester list to Manage--}}
            @csrf
            <div class="form-group row" style="padding: 30px;">
                <select class="form-control col-7" id="selectSemester">
                    @foreach($semester as $term)
                        <option value="{{ $term->id }}">{{$term->name}}</option>
                    @endforeach
                </select>
                <div class="offset-1"></div>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <button id="ManageSemester" class="btn btn-outline-info form-control col-4 btn-lg">Manage &nbsp;<i class="fas fa-cogs"></i></button>
            </div>
        {{--End semester list--}}
        {{------------------------------------------------------------------------------------------------------------}}
        {{--Start Manage semester (this div is totally handeled by client side (jquery) )--}}
            <div class="ManageSemester" style=" position: relative;width: 90vw;left: calc(-45vw + 50%); overflow-x: auto;">
            </div>
        {{--End Manage semester--}}

        {{------------------------------------------------------------------------------------------------------------}}
        {{--show open Course to add in TimeTable--}}
        <div class="modal-content TimeTableCreate"  style="display: none;">

        </div>
        {{--End  Show open Course to add in TimeTable--}}
    @endif
</div>
{{--End Manage TimeTable--}}

@section('scripts')
    <script src=" {{ asset('/js/frontend/TimeTable.js') }}"></script>
@endsection
