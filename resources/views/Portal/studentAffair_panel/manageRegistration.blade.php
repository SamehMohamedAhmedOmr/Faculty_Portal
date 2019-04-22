<br>
{{-- Start Success message --}}
@if(session()->has('success'))
    <div class="alert alert-success regFormHint">
        {{ session()->get('success') }}
        <i class="fas fa-check-square"></i>
    </div>
@endif
{{-- End Sucess message --}}

        {{--Filter--}}
        @csrf
        <div class="form-group row" style="padding: 30px;">
            {{--Select level list to Manage--}}
            <select class="form-control col-10 mngRegLevel" id="mng_reg_dep" name="level" value="Choose Level">
                <option class="mngRegOpt" value="1">Level - 1</option>
                <option class="mngRegOpt" value="2">Level - 2</option>
                <option class="mngRegOpt" value="3">Level - 3</option>
                <option class="mngRegOptEnd"value="4">Level - 4</option>
            </select>
            {{--Select department list to Manage--}}
            <select class="form-control col-10 mngRegDep" id="mng_reg_level" name="department_id" value="Choose Department">
                @foreach($departments as $dep)
                    <option class="mngRegOpt" value="{{$dep->id}}"> {{$dep->name}} Department</option>
                @endforeach
            </select>
            <div class="offset-1"></div>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <button id="ManageRegistration" class="btn btn-outline-info form-control col-3 btn-md">
                Show →
            </button>
        </div>
        {{--End filteration list--}}
        {{------------------------------------------------------------------------------------------------------------}}
        {{--Start Manage semester (this div is totally handeled by client side (jquery) )--}}
        <div class="ManageRegistration">

        </div>
        {{--End Manage Registration--}}

        <!--Message Modal -->
        <div id="regMailModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <span><i class="fa fa-envelope"></i> New Message »  </span>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                            <div class="form-group">
                                <label for="regMailSubj">Subject:</label>
                                <input type="text" class="form-control" name="regMailSubj" id="regMailSubj">
                                <hr>
                                <label for="regMailSubj">Message:</label>
                                <textarea class="form-control" rows="5" name="regMailMsg" id="regMailMsg"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input class="form-control col-10" id="regMailModalBtn" type="submit" value="Send" onclick='sendRegMail()' data-dismiss="modal">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>
        {{------------------------------------------------------------------------------------------------------------}}
<!--Message Modal -->
<div id="stuRegModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fa fa-bars"></i> Student Rgistration :  </span>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="stuRegModalBody">
            </div>
            <div class="modal-footer">
                <a id="stuMngRegBtn" href="/Panel/newRegister/"><button class='btn btn-primary'>Register for current semester</button></a>
            </div>
        </div>

    </div>
</div>

@section('scripts')
    <script  src="{{asset('/js/frontend/registration.js')}}"></script>
@endsection
