
{{--add new user btn--}}
<br>
<a href=" {{ '/Panel/SA/create' }} " class="btn btn-outline-success active"
   style="font-size: 14px;  margin: 5px auto; float: right !important;"> Add Student Affair <i class="fa fa-users"></i>
</a>
<div id="sa_view">
    @if(!$list->count())
        <div class="clearfix"></div>
        <div class="Not_Found"> No Student Affair Accounts found</div>
    @else
    <div style="margin-bottom: 0;">
        {{ $list->total() }} Total Users <br>
        <b style="font-size: 11px; color: #f00;"> In this page {{ $list->count() }} </b>
    </div>
        {{--Search--}}
        <div class="form-group">
            <input type="text" class="form-control input" id="sa_search" name="sa_search" placeholder="search by name"></input>
        </div>
        {{--table--}}

        <table class="table table-hover col-md-12"  style="margin-top: 3px;">
            <thead class="thead-light">
            <tr>
                <th scope="col" >  ID </th>
                <th scope="col"> Name  </th>
                <th scope="col"> Email </th>
                <th scope="col"> Phone Number </th>
                <th scope="col"> Degree </th>
                <th scope="col">Edit</th>
                {{--<th scope="col">Delete</th>--}}
            </tr>
            </thead>
            <tbody id="sa_tbl">
            @foreach($list as $user)
                    <tr>
                        <td> {{$user->userable_id}} </td>
                        <td> {{$user->name_en}} </td>
                        <td> {{$user->email}} </td>
                        <td> {{$user->phone}} </td>
                        <td> {{ $user->userable->degree }} </td>
                        <td>
                            <form method="GET" action="/Panel/SA/{{ $user->userable_id }}/edit">
                                <button type="submit" class="btn btn-info EditButton"> Edit
                                    <i class="fa fa-edit"></i>
                                </button>
                            </form>
                        </td>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        {{--<td>--}}
                            {{--<button id="del" class="btn btn-danger DeleteButton" onclick=" delete_sa('{{ $user->userable_id }}') "> Delete--}}
                                {{--<i class="fa fa-trash"></i>--}}
                            {{--</button>--}}
                        {{--</td>--}}
                    </tr>
            @endforeach
            </tbody>
            @endif
            <div class="clearfix"></div>
    </table>
</div>
<div class="pagination text-center"> {{ $list->links() }} </div>

