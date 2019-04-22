<div class="center-block" style=" margin: 10px;">
    @if(!$avlb)
        <div class="clearfix"></div>
        <span class="timetable_table_title"><i class="fa fa-lock"></i>There's no timetable to show currently !</span>
    @else
        <div>
            @if(!$timetables->count())
                <span class="timetable_table_title"><i class="fa fa-exclamation-circle"></i>You didn't register any lectures or sections yet !</span>
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
                            @foreach($timetables as $timetable)
                                @if($timetable->day == $day && $timetable->time == $time)
                                            @if($timetable->timetableable_type=='Doc')
                                                <td class="timetable_table_tt_lec"> {{ $timetable->Subject->Subject->name }} lecture <br>
                                                    {{ 'Dr.'.$timetable->timetableable->User[0]->name_en }}
                                                    <div class="timetable_table_place_hall">{{ $timetable->Place->name}}</div>
                                            @else
                                                <td class="timetable_table_tt_sec"> {{ $timetable->Subject->Subject->name }} section <br>
                                                    {{ 'Eng.'.$timetable->timetableable->User[0]->name_en }}
                                                    <div class="timetable_table_place_lab">{{ $timetable->Place->name}}</div>
                                            @endif
                                                </td>
                                            @php ($empty=1)
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