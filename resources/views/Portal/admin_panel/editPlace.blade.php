 <div class="headerName">{{$selected->name}} Details</div>

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
 <div class="form-group form_style">
     <form method="post" action="{{action('managePlacesController@update',['id' => $selected->id])}}" style="padding: 15px;">
        {{csrf_field()}}
        <input type="hidden" name="_method" value="PUT" >

        <div class="form-group row">
            <label for="Name" class="col-2 col-form-label">Name:</label>
            <input disabled id="placeName" type="text" class="form-control col-10" placeholder="Enter Place Name" name="name" maxlength="100" minlength="5"  value="{{$selected->name}}" required >
        </div>

        <div class="form-group row">
            <label for="seats" class="col-2 col-form-label">Seats:</label>
            <input disabled id="placeSeats" type="number" class="form-control col-10"  placeholder="Enter Seats Number" name="seats" max="100" min="10" required value="{{$selected->seats}}">
        </div>

        <div id="type" aria-required="true" >
            <div id = "newType" style="display: none;" class="form-group row">
                <label for="type" class="col-2 col-form-label">Type:</label>
                <div class="col-5">
                    <input type="radio" value="0" name="type" required @if($selected->type == 0){{'checked="checked"'}}@endif> Hall
                </div>

                <div class="col-5">
                    <input type="radio" value="1" name="type" @if($selected->type == 1){{'checked="checked"'}}@endif> Lab
                </div>
            </div>
        </div>
        <div class="EditPlaceLabels">
            @if($selected->type == 0)
                <div class="form-group row">
                    <label id="labelType" class="col-2 col-form-label">Type :</label>
                    <label class="form-control col-10">Hall</label>
                </div>
            @elseif($selected->type == 1)
                <div class="form-group row">
                    <label id="labelType" class="col-2 col-form-label">Type :</label>
                    <label class="form-control col-10">Lab</label>
                </div>
            @endif
        </div>
        <button onclick="return false;" id="editPlace" class="btn btn-outline-info btn-block">Edit</button>
        <button id="submitEdit" class="btn btn-block btn-success" style="display: none;">Save</button>
    </form>
 </div>
