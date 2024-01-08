<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="yinq">
    <title>{{ Setting::get('dashboard_title','后台管理系统') }} - 登录</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="stylesheet" type="text/css" href="/assets/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.min.css">
    <style>
    </style>
</head>

<body class="bg-white overflow-x-hidden">
<div class="row bg-white vh-100">
    <div class="col-md-6 col-lg-7 col-xl-8 d-none d-md-block"
         style="background-color: #9f78ff; background-size: cover;">

        <div class="d-flex vh-100">
            <div class="p-5 align-self-end">

                <br><br><br>
                <p class="text-white">欢迎使用{{ Setting::get('dashboard_title','后台管理系统') }}。</p>
            </div>
        </div>

    </div>

    <div class="col-md-6 col-lg-5 col-xl-4 align-self-center">
        <div class="p-5">

            <p class="text-center text-muted">
                <small>欢迎使用{{ Setting::get('dashboard_title','后台管理系统') }}</small></p>

            <div action="#!" method="post" class="signin-form needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username">用户名</label>
                    <input type="text" class="form-control" id="username" placeholder="请输入您的用户名" required>
                </div>

                <div class="mb-3">
                    <label for="password">密码</label>
                    <input type="password" class="form-control" id="password" placeholder="请输入您的密码" required>
                </div>


                @if(Setting::get('dashboard_capcha',false))

                    <div class="mb-3  row">

                        <label for="password">验证码</label>
                        <div class="col-7">

                            <input type="text" id="captchaResult" name="captcha" class="form-control" placeholder="验证码"
                                   required="">
                        </div>
                        <div class="col-5 text-right">
                            <img src="{{captcha_src()}}" class="pull-right" id="captcha" style="cursor: pointer;"
                                 onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新" alt="captcha">
                        </div>
                    </div>
                @endif


                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rememberme">
                        <label class="form-check-label not-user-select" for="rememberme">5天内自动登录</label>
                    </div>
                </div>


                <div class="mb-3 d-grid">
                    <button class="btn btn-primary" id="sub" type="submit">登录</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/popper.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/lyear-loading.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-notify.min.js"></script>
<script src="/assets/layer-v3.5.1/layer/layer.js"></script>
<script type="text/javascript">
    $("#sub").click(function () {
        var username = $("#username").val();
        var password = $("#password").val();
        var captcha = $("#captchaResult").val();
        var _token = "{{csrf_token()}}";
        var next = "{{Request::input('next','/dashboard/web')}}";
        if (username == '') {
            layer.msg('用户名不能为空', {icon: 2});
            return false;
        }
        if (password == '') {
            layer.msg('密码不能为空', {icon: 2});
            return false;
        }
        @if(Setting::get('dashboard_capcha',false))
        if (captcha == '') {
            layer.msg('验证码不能为空', {icon: 2});
            return false;
        }
        @endif

        var layer_loader = layer.msg('正在登录...', {icon: 16, shade: 0.3, time: 0});
        $.ajax({
            url: "{{url(route('auth.login.submit'))}}",
            type: 'post',
            data: {name: username, password: password, captcha: captcha, _token: _token},
            dataType: 'json',
            success: function (res) {
                layer.close(layer_loader);
                if (res.code == 200) {
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function () {
                        window.location.href = next;
                    }, 1000);
                } else {
                    layer.msg(res.msg, {icon: 2});
                }
            },
            error: function (res) {
                layer.close(layer_loader);
                layer.msg('登录失败：网络出现了一些错误。', {icon: 2});
            }
        })


    })
</script>
</body>
</html>
