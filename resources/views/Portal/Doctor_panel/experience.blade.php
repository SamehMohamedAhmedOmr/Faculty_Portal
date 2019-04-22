<div class="center-block" style="float:right; margin: 10px;">
    <br>
    <a href="{{url('/Panel/experience/create')}}" class="btn btn-outline-dark active">
         Add New Experience <i class="fas fa-file-alt"></i>
    </a>
</div>

<div id="doc_exp_view">
    @if( $experiences->count()<1  || !$experiences->count())
        <div class="clearfix"></div>
        <div class="Not_Found"> No Experiences exists</div>
    @else

    <br>
    <table class="table table-light col-md-12" style="margin-top: 3px;">
        <thead class="thead-light">
        <tr>
            <th scope="col"> Subject </th>
            <th scope="col"> Description </th>
            <th scope="col" width="20%">Edit</th>
            <th scope="col" width="20%">Delete</th>
        </tr>
        </thead>
        @if(!$experiences->count())
            {{ 'No experiences yet' }}
        @else
            @foreach($experiences as $experience)
                <tr>
                    <td> {{$experience->subject}} </td>
                    <td> {{$experience->description}} </td>
                    <td>
                        <form method="GET" action="/Panel/experience/{{ Auth::user()->userable_id .'*'.$experience->date }}/edit">
                            <button type="submit" class="btn btn-info EditButton"> Edit
                                <i class="fa fa-edit"></i>
                            </button>
                        </form>
                    </td>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <td>
                        <button id="del" class="btn btn-danger DeleteButton" onclick="doc_delete_exp('{{ Auth::user()->userable_id .'*'.$experience->date }}')"> Delete
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
    @endif
</div>
