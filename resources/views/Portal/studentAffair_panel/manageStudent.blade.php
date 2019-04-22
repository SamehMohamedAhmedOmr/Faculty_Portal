<br>
@if(session()->has('update_level'))
    <div class="alert alert-success">
        {{session()->pull('update_level', '')}}
        <i class="fa fa-times" style="float: right "></i>
    </div>
@endif
    <div>
        <a href="/Panel/Student/create"  class="btn btn-outline-success active"
           style="font-size: 14px;  margin: 5px auto; float: left !important;">
            Add New Student <i class="fa fa-users"></i>
        </a>
    </div>
{{-- Change the level --}}
    <div>
        <a href="{{ url('/Panel/S_A/updateLevels') }}"  class="btn btn-outline-primary active"
           style="font-size: 14px;  margin: 5px auto; float: right !important;">
            Update All Student Levels <i class="fa fa-users"></i>
        </a>
    </div>
<div class="clearfix"></div>

<div id="student_view">

    @if($students->count()<1  || !$students->count())
        <div class="clearfix"></div>
        <div class="Not_Found"> No Students found</div>
    @else
    <div style="margin-bottom: 0;">
        {{ $students->total()}} Total Users <br>
        <b style="font-size: 11px; color: #f00;"> In this page {{ $students->count()}} </b>
    </div>
    {{--Search--}}
    <div class="form-group">
        <input type="text" class="form-control input" id="student_search" name="student_search" placeholder="search by ID"></input>
    </div>
    {{--table--}}
    <table class="table table-hover col-md-5" style="margin-top: 3px; text-align: center;">
        <thead class="thead-light">
            <tr>
                <th> ID   </th>
                <th> Name </th>
                <th> Phone </th>
                <th> Department   </th>
                <th> Level </th>
                <th> Edit </th>
                {{--<th> Delete </th>--}}
            </tr>
        </thead>
        <tbody id="student_tbl">
        @foreach($students as $student)
                <tr
                        @if($student->userable->account_status == 0)style="background-color: #ffaaaac7;"
                        @else style="background-color: #aaffadc7;"
                        @endif
                >
                    <td> {{$student->userable_id}} </td>
                    <td> {{$student->name_en}} </td>
                    <td> {{$student->phone}} </td>
                    <td> {{ $student->userable->department->name }} </td>
                    <td>
                       @if($student->userable->graduated_status==0)
                            {{ 'graduated' }}
                        @elseif($student->userable->graduated_status==1)
                            {{ 'level 1' }}
                        @elseif($student->userable->graduated_status==2)
                            {{ 'level 2' }}
                        @elseif($student->userable->graduated_status==3)
                            {{ 'level 3' }}
                        @elseif($student->userable->graduated_status==4)
                            {{ 'level 4' }}
                        @endif
                    </td>

                    <td>
                        <form method="GET" action="/Panel/Student/{{ $student->userable_id }}/edit">
                            <button type="submit" class="btn btn-info EditButton"> Edit
                                <i class="fa fa-edit"></i>
                            </button>
                        </form>
                    </td>

                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    {{--<td>--}}
                        {{--<button id="del" class="btn btn-danger DeleteButton" onclick=" delete_student('{{ $student->userable_id }}') "> Delete--}}
                            {{--<i class="fa fa-trash"></i>--}}
                        {{--</button>--}}
                    {{--</td>--}}
                </tr>
            @endforeach
        </tbody>
        @endif
    </table>
</div>
<div class="pagination text-center"> {{ $students->links() }} </div>
