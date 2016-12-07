<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome to Spacy Crush Project</title>
	<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Spacy crush</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ route('play') }}">Play !</a></li>
					<li><a href="#">About</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<section id="first-section" class="first-section">

		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4 logo">
					<img id="rocket" class="animated bounceIn" src="{{ asset('img/rocket_site.png') }}" alt="rocket image">
				</div>

				<div class="col-xs-12 col-sm-6 col-md-8 presentation animated flipInX">
					<p class="lead text">
						Spacy crush is a game like CandyCrushSaga
					</p>
					<div class="buttons">
						<a id="play" href="{{ route('play') }}"><button type="button" class="btn btn-success btn-lg">Play !!</button></a>
						<a href="#"><button type="button" class="btn btn-primary btn-lg">Download sources</button></a>
					</div>
				</div>
			</div>
		</div>

	</section>

	<section id="second-section" class="second-section">
		<div class="container">
			<div class="row animated fadeInDown">
				<div class="col-md-3 info laravel">
					<img src="{{ asset('img/laravel4_logo.png') }}" alt="">
					<p class="lead">Laravel4</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum, officiis, eligendi, quasi saepe ad earum tempora beatae rerum dignissimos molestias nihil dolor ex fugiat ea perspiciatis. Earum quo cum odit!</p>
				</div>
				<div class="col-md-1 info plus-sign">
					<p>+</p>
				</div>
				<div class="col-md-3 info angularjs">
					<img src="{{ asset('img/angularjs_logo.png') }}" alt="">
					<p class="lead">AngularJS</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, voluptate, harum, provident, enim ab nihil odio corporis quibusdam earum illo quo aliquam qui fugiat excepturi iusto nobis dolores accusamus? Eveniet.</p>
				</div>
				<div class="col-md-1 info equal-sign">
					<p>=</p>
				</div>
				<div class="col-md-4 info spacycrush">
					<img src="{{ asset('img/game_site.png') }}" alt="">
					<p class="lead">SpacyCrush</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel, culpa, est, omnis voluptatibus soluta maiores necessitatibus error repudiandae totam voluptate ipsum laboriosam architecto molestias temporibus quidem nisi quisquam possimus sed.</p>
				</div>
			</div>
		</div>
	</section>

	<footer class="footer">
		<div class="container">
			<div class="row">

			</div>
		</div>
	</footer>

	<script src="bower_components/jquery/dist/jquery.js"></script>
	<script src="{{ asset('bower_components/jquery.transit/jquery.transit.js') }}"></script>

	<script>
	jQuery(document).ready(function($) {
		/* Parallax first section */
		$(window).scroll( function()
		{
			var scroll = $(window).scrollTop(), slowScroll = scroll/2;
			$('#first-section').css({ transform: "translateY(" + slowScroll + "px)" });
		});

		$('.laravel, .angularjs, .spacycrush').hover(function() {
			$(this).transition({ scale: 1.05 });
		}, function() {
			$(this).transition({ scale: 1 });
		});


		$('#play').on('click', function(event){
			event.preventDefault();

			var href = $(this).attr('href');

			$("#rocket").transition({ x: 300, y: -600 }, 1200, 'linear');

			setTimeout(function(){
				window.location = href;
			},700);
		});

	});
	</script>
</body>
</html>