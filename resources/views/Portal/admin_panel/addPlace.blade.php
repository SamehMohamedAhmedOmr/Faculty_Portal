<div class="headerName">Add Places</div>

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
    <form method="post" class="form-group" style="padding: 15px;" action="{{action('managePlacesController@store')}}">
        @csrf
        <div class="form-group row">
            <label for="Name" class="col-2 col-form-label">Name:</label>
            <input id="name" type="text" class="form-control col-10"  placeholder="Enter Place Name" name="name" maxlength="100" minlength="5" required  value="{{ old('name') }}">
        </div>

        <div class="form-group row">
            <label for="seats" class="col-2 col-form-label">Seats:</label>
            <input id="seats" type="number" class="form-control col-10"  placeholder="Enter Seats Number" name="seats" max="100" min="10" required value="{{ old('seats') }}">
        </div>

        <div id="type" aria-required="true" class="form-group row">
            <labe class="col-2 col-form-label">type of place</labe>
            <div class="col-5 col-form-label">
                <label><input type="radio" value="0" name="type" required> Hall</label> <br>
            </div>
            <div class="col-5 col-form-label">
                <label><input type="radio" value="1" name="type"> Lab</label>
            </div>
        </div>

        <button type="submit"  class="btn btn-block btn-outline-info" >Add New Place <i class="far fa-plus-square"></i></button>
    </form>
</div>
