@extends('/layouts/layout')

{{--start Style--}}
@section('cssStyle')
    <link href="{{ asset('/css/events.css') }}" rel="stylesheet">
@endsection
{{--End Style--}}


{{--start section--}}

@section('content')

<div class="divider"></div>
	
	<div class="content">
		<div class="container  event-view">

			<div class="main-content">

                @if(Auth::check())
                    @if(Auth::user()->userable_type=='Adm')
                        <button class="btn add-event-btn" onclick=""><a href=" {{ '/events/create' }} ">Add Event</a></button>
                        {{--<a href=" {{ '/events/create' }} " class="btn add-event-btn"> Add Event </a>--}}
                    @endif
                @endif

                    {{--@if(!$events->count())--}}
                    @if($upcoming)
                        {{--title--}}
                        <h1 class="main-title"><i class="fa fa-bookmark"></i>Upcoming event</h1>
                        <hr>
                        {{--@foreach($events as $event)--}}
                            <div class="col-12 event-bar">
                                {{--Calender--}}
                                <div class="col-3 event-bar-cal">
                                    <div class="col-12 event-bar-calDay">
                                        @php( $m = date("m",strtotime($upcoming->date)) )
                                        <h4>{{ date("d",strtotime($upcoming->date)) }}</h4>
                                    </div>
                                    <h6>{{ date('M', strtotime($m . '01')) }}</h6>
                                </div>
                                {{--Information--}}
                                <div class="col-4 event-bar-info">
                                    <span class="event-title"> {{ $upcoming->name }} </span>
                                    <span class="event-time"><i class="fa fa-map-marker"></i>{{ $upcoming->place }}</span>
                                    <span class="event-place"><i class="fa fa-calendar"></i>{{ date("H:i",strtotime($upcoming->date)) }}</span>
                                </div>
                                {{--View Button--}}
                                <div class="col-4 event-bar-view">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <div class="btn btn-1" data-toggle="modal" data-target="#eventModal" onclick="showEvent('{{ $upcoming->id }}')">
                                        <p>View Event</p>
                                    </div>
                                </div>
                            </div>
                        {{--@endforeach--}}
                    @endif

                    @if(!$events->count())

                        <div class="clearfix"></div>
                        <div class="Not_Found"> No events to show!</div>
                        <hr>

                    @else
                        {{--title--}}
                    <div id="all-events">
                        <h1 class="main-title">All events</h1>
                        <hr>

                        <div class="all-card-container row">

                            @foreach($events as $event)
                                <div class="card-container">
                                    <div class="card">
                                        <div class="front">
                                            <span class="event-title"> {{ $event->name }} </span>
                                            @php( $m = date("m",strtotime($event->date)) )
                                            <h4>{{ date("d",strtotime($event->date)) }}</h4>
                                            <h6>{{ date('M', strtotime($m . '01')) }}</h6>
                                        </div>
                                        <div class="back">
											<meta name="csrf-token" content="{{ csrf_token() }}">
											<button class="btn card-btn" data-toggle="modal" data-target="#eventModal" onclick="showEvent('{{ $event->id }}')"> VIEW EVENT </button>
                                            @if(Auth::check())
                                                @if(Auth::user()->userable_type=='Adm')
                                                    <button class="btn card-btn rmv-btn" onclick=" deleteEvent('{{ $event->id }}') "> <i class="fa fa-times"></i> </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
			</div>

            <!--Start of Event Modal -->
            <div id="eventModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div id="eventModalContent" class="modal-content">
                        {{-- Response From Ajax Function--}}
                    </div>

                </div>
            </div>
            <!--End of Event Modal -->
			<aside id="sidebar">
				<div class="widget clearfix calendar">
					<h2>Event calendar</h2>
					<div class="head">
						<a class="prev" href="#"></a>
						<a class="next" href="#"></a>
						<h4>April 2014</h4>
					</div>
					<div class="table">
						<table>
							<tr>
								<th class="col-1">Mon</th>
								<th class="col-2">Tue</th>
								<th class="col-3">Wed</th>
								<th class="col-4">Thu</th>
								<th class="col-5">Fri</th>
								<th class="col-6">Sat</th>
								<th class="col-7">Sun</th>
							</tr>
							<tr>
								<td class="col-1 disable"><div>31</div></td>
								<td class="col-2"><div>1</div></td>
								<td class="col-3"><div>2</div></td>
								<td class="col-4"><div>3</div></td>
								<td class="col-5 archival"><div>4</div></td>
								<td class="col-6"><div>5</div></td>
								<td class="col-7"><div>6</div></td>
							</tr>
							<tr>
								<td class="col-1"><div>7</div></td>
								<td class="col-2"><div>8</div></td>
								<td class="col-3 archival"><div>9</div></td>
								<td class="col-4"><div>10</div></td>
								<td class="col-5"><div>11</div></td>
								<td class="col-6"><div>12</div></td>
								<td class="col-7"><div>13</div></td>
							</tr>
							<tr>
								<td class="col-1"><div>14</div></td>
								<td class="col-2 upcoming"><div><div class="tooltip"><div class="holder">
									<h4>Omnis iste natus error sit voluptatem dolor</h4>
									<p class="info-line"><span class="time">10:30 AM</span><span class="place">Lincoln High School</span></p>
									<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident similique.</p>
								</div></div>15</div></td>
								<td class="col-3"><div>16</div></td>
								<td class="col-4 upcoming"><div><div class="tooltip"><div class="holder">
									<h4>Omnis iste natus error sit voluptatem dolor</h4>
									<p class="info-line"><span class="time">10:30 AM</span><span class="place">Lincoln High School</span></p>
									<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident similique.</p>
								</div></div>16</div></td>
								<td class="col-5"><div>18</div></td>
								<td class="col-6"><div>19</div></td>
								<td class="col-7"><div>20</div></td>
							</tr>
							<tr>
								<td class="col-1"><div>21</div></td>
								<td class="col-2"><div>22</div></td>
								<td class="col-3"><div>23</div></td>
								<td class="col-4"><div>24</div></td>
								<td class="col-5 upcoming"><div><div class="tooltip"><div class="holder">
									<h4>Omnis iste natus error sit voluptatem dolor</h4>
									<p class="info-line"><span class="time">10:30 AM</span><span class="place">Lincoln High School</span></p>
									<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident similique.</p>
								</div></div>25</div></td>
								<td class="col-6"><div>26</div></td>
								<td class="col-7"><div>27</div></td>
							</tr>
							<tr>
								<td class="col-1"><div>28</div></td>
								<td class="col-2 upcoming"><div><div class="tooltip"><div class="holder">
									<h4>Omnis iste natus error sit voluptatem dolor</h4>
									<p class="info-line"><span class="time">10:30 AM</span><span class="place">Lincoln High School</span></p>
									<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident similique.</p>
								</div></div>29</div></td>
								<td class="col-3"><div>30</div></td>
								<td class="col-4 disable"><div>1</div></td>
								<td class="col-5 disable"><div>2</div></td>
								<td class="col-6 disable"><div>3</div></td>
								<td class="col-7 disable"><div>4</div></td>
							</tr>
						</table>
					</div>
					<div class="note">
						<p class="upcoming-note">Upcoming event</p>
						<p class="archival-note">Archival event</p>
					</div>
				</div>
			</aside>
			<!-- / sidebar -->

		</div>
		<!-- / container -->
	</div>

@endsection
{{--end section--}}

@section('scripts')
    <script src=" {{ asset('/js/frontend/events.js') }}"></script>
@endsection