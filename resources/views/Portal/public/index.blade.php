@extends('/layouts/layout')

{{--start section--}}
@section('content')

	<div class="slider">
		<ul class="bxslider">
			<li>
				<div class="container">
					<img src="{{ asset('/images/frontend/background/background4.jpg') }}">
					<div class="info">
						<h2>It’s Time to <br><span>Check our New Equipment</span></h2>
						<a href="#">Check out our new programs</a>
					</div>
				</div>
				<!-- / content -->
			</li>
			<li>
				<div class="container">
					<img src="{{ asset('/images/frontend/background/background5.jpg') }}">
					<div class="info">
						<h2> <br><span>Get back to University</span></h2>
						<a href="#">Check out our new programs</a>
					</div>
				</div>
				<!-- / content -->
			</li>
			<li>
				<div class="container">
					<img src="{{ asset('/images/frontend/background/background2.jpg') }}">
					<div class="info">
						<h2>It’s Time to <br><span>Check our New Equipment</span></h2>
						<a href="#">Check out our new programs</a>
					</div>
				</div>

				<!-- / content -->
			</li>
		</ul>
		<div class="bg-bottom"></div>
	</div>
	
	<section class="posts">
		<div class="container col-12">
			<div class="row">

				<div class="col-12 section1">
					<div class="col-lg-1 parts"></div>
					<div class="col-lg-5 parts" style="margin-bottom: 5px;">
						<div class="col-12 title1">
							<div class="title_head"><div class="col-2 pic"><img src="images/frontend/aa_arrow.png" alt="Mission"></div><span>Our Mission</span></div>
							<hr>
							<div class="col-12 description" style="height: 100%;">FCHI prepares aspirational and effective leaders of international quality who contribute to national and global progress by interweaving Helwan University Core Values into an innovative education which creates a culture of broad inquiry, intellectual engagement, and valuable societal impact.</div>
						</div>
					</div>
					<div class="col-lg-5 parts" style="margin-bottom: 5px;">
						<div class="col-12 title2">
							<div class="title_head"><div class="col-2 pic"><img src="images/frontend/aa_arrow.png" alt="Vision"></div><span>Our Vision</span></div>
							<hr>
							<div class="col-12 description" style="height: 100%;">FCIH strives to be recognized as one of the world's leading institutions in scientific discovery and innovation presenting solutions to societal challenges, and to serve as agent of change that advances inspired leaders and scholars.</div>
						</div>
					</div>
				</div>
				<div class="container col-12" style="margin: auto">
					<div class="row" style="margin: auto;">
						<div class=""></div>
						<div class="cornerpic" style="margin: auto;"><img src="images/frontend/bg_grey_arrow.png" alt=""></div>
					</div>
				</div>

					<div class="col-lg-10 section2">
						<div class="pic" style="float: left;"><img class="section2_img" src="images/frontend/aa_arrow_blue.png" alt=""></div>
						<h3 style="text-align: center; padding-bottom: 28px;" class="">About FCIH</h3>
						<span class="col-lg-6 col-md-10 about_img">
							<div class="col-xs-10 col-md-10" style="padding: 5px;"><img src="images/frontend/aa_doublepic.png" alt=""></div>
						</span>

						<span class="col-lg-5 about_us">
							FCIH primary location was in Garden City, Cairo,
							Egypt then in 2002 - 2003 the faculty have been shifted gradually to the main campus in helwan.
							Getting into the race starting from 1999. FCIH always try to keep its standard student academic level.<br>
							FCIH with all its resources serve a harmonic education system with new and pioneered education methods".<br>
							One of our goals is enhance our national and international recognition by deepening its presence,
							impact <br>and contribution to the social, cultural and economic vitality of the Egyption 2030 vision.

						</span>

					</div>
			</div>
		</div>
		<!-- / container -->
	</section>

	<section class="news">
		<div class="container">
			<div class="news_title"><h2><i class="fa fa-bullhorn"></i>Latest news</h2></div><br>

			<article class="row">
				<div class="col-lg-5" style="text-align: center;"><img src="images/frontend/aa_news_1.png" alt=""></div>
				<div class="info myinfo col-lg-7" style="text-align: center;">
					<h4>New Agreement with canadian university</h4>
					<p class="date">14 APR 2017, Ahmed Mostafa</p>
					<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores (...)</p>
					<div class="readmore"><a class="" href="#">Read more</a><div class="mypic"><img class="arrow" src="images/frontend/aa_arrow_w.png" alt=""></div></div>
				</div>
			</article>

			<article class="row">
				<div class="col-lg-5" style="text-align: center;"><img src="images/frontend/aa_news_2.png" alt=""></div>
				<div class="info myinfo col-lg-7" style="margin-top: 20px;text-align: center;">
					<h4>Held a new session about cloud computing</h4>
					<p class="date">23 NOV 2017, Omar Tarek</p>
					<p class="news_cont">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores (...)</p>
					<div class="readmore"><a class="" href="#">Read more</a><div class="mypic"><img class="arrow" src="images/frontend/aa_arrow_w.png" alt=""></div></div>
				</div>
			</article>

			<div class="btn-holder">
				<a class="btn" href="#">See archival news</a>
			</div>
		</div>
		<!-- / container -->
	</section>

	<section class="events" style="padding: 50px;">
		<div class="row" style="background-color:  #dbdbdb45 !important;">
			<h2 class="eventsTitle col-12" style="margin-top: 15px;">Our Goals</h2>
			<article class="col-lg-4 col-md-12">
				<div class="row" style="background-color: #fff;padding: 17px;">
					<div class=" col-lg-12" style="text-align: center;">
						<i class="fas fa-sort-amount-up" style="font-size: 50px; margin-bottom: 15px;color: #2c416757;text-shadow: 1px 1px 1px #2c4167, -1px -1px 1px #fff;"></i>
					</div>
					<div class="info col-12">
						<p>
							The Faculty of Computer and Information at Helwan University seeks to achieve scientific, practical and research excellence in the field of computers and information locally and regionally.
						</p>
						{{--<a class="more" href="#">Read more</a>--}}
					</div>
				</div>
			</article>
			<article class="col-lg-4 col-md-12">
				<div class="row" style="padding: 17px;">
					<div class=" col-lg-12" style="text-align: center;">
						<i class="fas fa-trophy" style="font-size: 50px; margin-bottom: 15px;color: #2c416757;text-shadow: 1px 1px 1px #2c4167, -1px -1px 1px #fff;"></i>
					</div>
					<div class="info col-12">
						<p>
							The College is working on preparing a distinguished graduate capable of competing in the local and regional labor market in the fields of computers and information, contributing to the service of the local community and enriching scientific research.
						</p>
						{{--<a class="more" href="#">Read more</a>--}}
					</div>
				</div>
			</article>
			<article class="col-lg-4 col-md-12">
				<div class="row" style="background-color: #fff;padding: 17px;">
					<div class=" col-lg-12" style="text-align: center;">
						<i class="fas fa-trophy" style="font-size: 50px; margin-bottom: 15px;color: #2c416757;text-shadow: 1px 1px 1px #2c4167, -1px -1px 1px #fff;"></i>
					</div>
					<div class="info col-12">
						<p>
							To contribute effectively to the service of the community and to allow everyone to learn the techniques of computers and information systems to serve the development issues to enter the information age by qualifying the manpower necessary for the labor market in the new era with its ability to compete in this area.
						</p>
						{{--<a class="more" href="#">Read more</a>--}}
					</div>
				</div>
			</article>
			<div class="btn-holder">
				{{--<a class="btn blue" href="#">See all upcoming events</a>--}}
			</div>
		</div>
		<!-- / container -->
	</section>
	<div class="container">
		<a href="#fancy" class="info-request">
			<span class="holder">
				<span class="title">Request information</span>
				<span class="text">Do you have some questions? Fill the form and get an answer!</span>
			</span>
			<span class="arrow"></span>
		</a>
	</div>


@endsection
{{--end section--}}


