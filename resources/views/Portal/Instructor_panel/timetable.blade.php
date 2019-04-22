<div class="center-block" style=" margin: 10px;">
   @if(!$avlb)
        <div class="clearfix"></div>
        <span class="timetable_table_title"><i class="fa fa-lock"></i>There's no timetable to show currently !</span>
    @else
        <div class="col-sm-12"></div>
        <div>
                @if(!$sections->count())
                        <div class="clearfix"></div>
                        <span class="timetable_table_title"><i class="fa fa-exclamation-circle"></i>You haven't any sections this semester !</span>
                @else
                        <div class="clearfix"></div>
                        <span class="timetable_table_title"><i class="fa fa-calendar"></i>Your timetable for current semester <span>{{ $currentSemester->name }}</span> : </span>
                        @php ($weekDays = ['Sunday','Monday','Tuesday','Wednesday','Thursday'])
                        @php ($times = [7,9,11,13,15,17])
                        <table class="table table-bordered col-md-12 timetable_table">
                                <thead>
                                <tr>
                                        <th>  </th>
                                        @foreach($times as $time)
                                                <th class="timetable_table_th">{{ $time.":00" }}</th>
                                        @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($weekDays as $day)
                                        <tr>
                                                <td class="timetable_table_day">{{ $day }}</td>
                                                @foreach($times as $time)  {{-- times in this day --}}
                                                        @php ($empty=0)
                                                        @foreach($sections as $section)
                                                                {{-- check if there is lecture in this day at this time --}}
                                                                @if($section->day == $day && $section->time == $time)
                                                                        <td class="timetable_table_tt">
                                                                            {{ $section->Subject->Subject->name . ' section'}}
                                                                            <br>
                                                                            <div class="timetable_table_place">{{ $section->Place->name}}</div>
                                                                            @php ($empty=1)
                                                                        </td>
                                                                        @break;
                                                                @endif
                                                        @endforeach
                                                        @if($empty==0)
                                                                <td>  </td>
                                                        @endif
                                                @endforeach
                                        </tr>
                                @endforeach
                                </tbody>
                        </table>
                @endif

        </div>
    @endif

</div>

<div>

</div>
