
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="stylesheet" type="text/css" href="/assets/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <!--tags插件css-->
    <link rel="stylesheet" type="text/css" href="/assets/js/jquery-tagsinput/jquery.tagsinput.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.min.css">
</head>

<body>
<div class="container-fluid">

    <div class="row">
        @yield('content')


    </div>

</div>

<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/popper.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<!--tags插件-->
<script type="text/javascript" src="/assets/js/jquery-tagsinput/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/js/main.min.js"></script>

<script src="/assets/layer-v3.5.1/layer/layer.js"></script>
<script src="/assets/ajax-modal.js?t={{time()}}"></script>

@foreach(isset($js) ? $js : [] as $item)
    <script src="{{$item}}"></script>

@endforeach

@yield('js')
</body>
</html>
