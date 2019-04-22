

{{--add new user btn--}}
<br>
<a href=" {{ '/Panel/Admin/create' }} " class="btn btn-outline-success active"
   style="font-size: 14px;  margin: 5px auto; float: right !important;"> Add Admin <i class="fa fa-users"></i>
</a>
<div id="admin_view">
    @if($list->count()<2  || !$list->count())
        <div class="clearfix"></div>
        <div class="Not_Found"> No accounts found</div>
    @else
    <div style="margin-bottom: 0;">
        {{ $list->total()-1 }} Total Users <br>
        <b style="font-size: 11px; color: #f00;"> In this page {{ $list->count()-1 }} </b>
    </div>
    {{--Search--}}
    <div class="form-group">
        <input type="text" class="form-control input" id="adm_search" name="adm_search" placeholder="search by name"></input>
    </div>
    {{--table--}}
    <table class="table table-hover col-md-12" style="margin-top: 3px;">
        <thead class="thead-light">
            <tr>
                <th scope="col" >  ID </th>
                <th scope="col"> Name  </th>
                <th scope="col"> Email </th>
                <th scope="col"> Phone Number </th>
                <th scope="col">Edit</th>
                {{--<th scope="col">Delete</th>--}}
            </tr>
        </thead>
        <tbody id="adm_tbl">
        @foreach($list as $user)
            @if($user->userable_id != 20161810)
                <tr>
                    <td> {{$user->userable_id}} </td>
                    <td> {{$user->name_en}} </td>
                    <td> {{$user->email}} </td>
                    <td> {{$user->phone}} </td>
                    <td>
                        <form method="GET" action="/Panel/Admin/{{ $user->userable_id }}/edit">
                            <button type="submit" class="btn btn-info EditButton"> Edit
                                <i class="fa fa-edit"></i>
                            </button>
                        </form>
                    </td>
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    {{--<td>--}}
                        {{--<button id="del" class="btn btn-danger DeleteButton" onclick=" delete_admin('{{ $user->userable_id }}') "> Delete--}}
                            {{--<i class="fa fa-trash"></i>--}}
                        {{--</button>--}}
                    {{--</td>--}}
                </tr>
            @endif
        @endforeach
        </tbody>
    @endif
</table>
</div>
<div class="pagination text-center"> {{ $list->links() }} </div>

