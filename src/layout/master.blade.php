<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
	<head>
		@yield('meta')
		@yield('styles')
	</head>
	<body @yield('body_class') >
		@yield('body')
		@yield('javascript')
	</body>
</html>