<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="app">

	<div class="container">
		@yield('content')
	</div>

</body>
</html>