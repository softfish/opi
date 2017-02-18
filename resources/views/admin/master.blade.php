<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.js"></script> -->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/general.css') }}">
	@yield('htmlheader')
</head>
<body>
<header class="col-sm-12px;">
	<img width="100" src="{{ asset('img/laravel_logo.png') }}" />
	<img width="100" src="{{ asset('img/jQuery_logo.png') }}" />
	<img width="100" src="{{ asset('img/bootstrap_logo.png') }}" />
</header>
    <nav class="navbar navbar-default">
    	<div class="container-fluid">
    		<div class="navbar-header">
              <a class="navbar-brand" href="#">
              </a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="{{ url('order/list') }}">Orders</a></li>
                <li><a href="{{ url('product/list') }}">Products</a></li>
                <li><a href="{{ url('item/list') }}">Items</a></li>
            </ul>
    	</div>
    </nav>
	@yield('body')
<footer class="col-sm-12">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	@yield('footer')
	<div class="text-center">
		&copy 2017-{{ date('Y') }}, Developed by Fei Kwok (<a href="mailto:fkwok00@gmail.com">fkwok00@gmail.com</a>)
	</div>
</footer>
</body>
</html>