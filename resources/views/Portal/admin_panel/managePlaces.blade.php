<br>
<form method="get" action="{{action('managePlacesController@create')}}">
    <button  class="btn btn-outline-success active"
             style="font-size: 14px;  margin: 5px auto; float: right !important;" type="submit">Add Place &nbsp; <i class="fa fa-building"></i></button>
</form>
<div class="clearfix"></div>
@if(session()->has('no_Place'))
    <div class="alert alert-danger">
        {{session()->pull('no_Place', '')}}
        <i class="fa fa-times" style="float: right "></i>
    </div>
@endif

@if (session()->has('Add_Place_Success'))
    <div class="alert alert-success">
        {{session()->pull('Add_Place_Success', '')}}
        <i class="fas fa-check-square"></i>
    </div>
@elseif (session()->has('update_Place_Success'))
    <div class="alert alert-success">
        {{session()->pull('update_Place_Success', '')}}
        <i class="fas fa-check-square"></i>
    </div>
@endif
@if(!$places->count())
    <div class="clearfix"></div>
    <div class="Not_Found"> No Places exists yet</div>
@else
    {{--Search--}}
    <div class="form-group">
        <input type="text" class="form-control input" id="place_search" name="place_search" placeholder="search by name">
    </div>
    {{--table--}}
    <table class="table table-hover col-md-12" style="margin-top: 3px;">
        <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Seats</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody id="place_tbl">
            @foreach($places as $key => $place)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$place->name}}</td>
                <td>
                    @if($place->type==0)
                        Hall
                    @elseif($place->type ==1)
                        Lab
                    @endif
                </td>
                <td>{{$place->seats}}</td>
                <td>
                    <a href="{{route('managePlaces.edit',$place->id)}}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
        {{$places->links()}}
    </table>
</div>
@endif
