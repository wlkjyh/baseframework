<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="yinq">
    <title>{{ Setting::get('dashboard_title','OA系统') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="stylesheet" type="text/css" href="/assets/css/materialdesignicons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/bootstrap-multitabs/multitabs.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.min.css">
</head>
<style>
    #logo a {
        font-size: 22px;
        line-height: 68px;
        white-space: nowrap;
        color: #4d5259;
    }

    [data-logobg*='color_'] #logo a {
        color: #fff;
    }

    @media (min-width: 1024px) {
        .lyear-layout-sidebar.lyear-aside-open #logo a {
            display: block;
            width: 45px;
            height: 68px;
            letter-spacing: 3px;
            margin: 0 auto;
            overflow: hidden;
            text-align: center;
        }

        .lyear-layout-sidebar-close .lyear-layout-sidebar:hover #logo a {
            width: 100%;
            margin: 0;
            letter-spacing: 0px;
        }
    }
</style>
<body class="lyear-index">
<div class="lyear-layout-web">
    <div class="lyear-layout-container">
        <!--左侧导航-->
        <aside class="lyear-layout-sidebar">

            <!-- logo -->
            <div id="logo" class="sidebar-header">
                <a href="javascript:;"><h3>{{ Setting::get('dashboard_title','OA系统') }}</h3></a>
            </div>

            <div class="lyear-layout-sidebar-info lyear-scroll">

                <nav class="sidebar-main">

                    <ul class="nav-drawer">
                        <li class="nav-item active">
                            <a class="multitabs" href="/dashboard/overview" id="default-page">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span>{{ __('message.overview') }}</span>
                            </a>
                        </li>

                        @if($userrow->hasRole(['admin']) && config('app.debug'))
                            <li class="nav-item nav-item-has-subnav">
                                <a href="javascript:void(0)">
                                    <i class="mdi mdi-card-bulleted-outline"></i>
                                    <span>{{ __('message.develop_tools_menu') }}</span>
                                </a>
                                <ul class="nav nav-subnav">
                                    <li><a class="multitabs" href="{{url(route('develop.role'))}}">角色设计</a></li>
                                    <li><a class="multitabs" href="{{url(route('develop.menu'))}}">菜单管理器</a></li>
                                    <li><a class="multitabs" href="{{url(route('develop.permission'))}}">权限管理器</a>
                                    </li>
                                    <li><a class="multitabs" href="/log-viewer">日志管理器</a></li>

                                </ul>


                            </li>
                        @endif

                        <?php
                        $menus = \App\Models\Menu::getUserMenu($userrow);

                        function renderNextMenu($menus, $parent_id)
                        {
                            $html = '';
                            foreach ($menus as $val) //    重复迭代
                            {
                                //    判断还有没有下一级
                                $hasNext = false;
                                foreach ($menus as $val2) {
                                    if ($val2['parent_id'] == $val['id']) {
                                        $hasNext = true;
                                        break;
                                    }
                                }
                                if ($val['parent_id'] == $parent_id) {
                                    if ($hasNext) {
                                        $html .= '<li class="nav-item nav-item-has-subnav">';
                                        $html .= '<a href="javascript:void(0)">';
                                        $html .= '<i class="' . $val['icon'] . '"></i>';
                                        $html .= '<span>' . $val['name'] . '</span>';
                                        $html .= '</a>';
                                        $html .= '<ul class="nav nav-subnav">';
                                        $html .= renderNextMenu($menus, $val['id']);
                                        $html .= '</ul>';
                                        $html .= '</li>';
                                    } else {
                                        $html .= '<li class="nav-item">';
                                        $html .= '<a class="multitabs" href="' . $val['path'] . '">';
                                        $html .= '<i class="' . $val['icon'] . '"></i>';
                                        $html .= '<span>' . $val['name'] . '</span>';
                                        $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                }


                            }
                            return $html;
                        }

                        ?>

                        {{--                        渲染出一级菜单--}}
                        @foreach($menus as $val)

                            @if($val['parent_id'] == 0)
                                    <?php
                                    $hasNext = false;
                                    foreach ($menus as $val2) {
                                        if ($val2['parent_id'] == $val['id']) {
                                            $hasNext = true;
                                            break;
                                        }
                                    }
                                    ?>
                                @if($hasNext)
                                    <li class="nav-item nav-item-has-subnav">
                                        <a href="javascript:void(0)">
                                            <i class="{{$val['icon']}}"></i>
                                            <span>{{$val['name']}}</span>
                                        </a>
                                        <ul class="nav nav-subnav">
                                            {!! renderNextMenu($menus,$val['id']) !!}
                                        </ul>
                                    </li>

                                @else
                                    <li class="nav-item">
                                        <a class="multitabs" href="{{$val['path']}}">
                                            <i class="{{$val['icon']}}"></i>
                                            <span>{{$val['name']}}</span>
                                        </a>
                                    </li>
                                @endif

                            @endif
                        @endforeach


                    </ul>
                </nav>

                <div class="sidebar-footer">
                    <p class="copyright">
                        <span>
                            baseFramework v{{env('version')}}
                        </span>
                    </p>
                </div>
            </div>

        </aside>
        <!--End 左侧导航-->

        <!--头部信息-->
        <header class="lyear-layout-header">

            <nav class="navbar">

                <div class="navbar-left">
                    <div class="lyear-aside-toggler">
                        <span class="lyear-toggler-bar"></span>
                        <span class="lyear-toggler-bar"></span>
                        <span class="lyear-toggler-bar"></span>

                    </div>


                </div>

                <ul class="navbar-right d-flex align-items-center">


                    <!--个人头像内容-->
                    <li class="dropdown">
                        <a href="javascript:void(0)" data-bs-toggle="dropdown" class="dropdown-toggle">
                            <img class="avatar-md rounded-circle"
                                 src="{{\App\utils::Identicon()->getImageDataUri($userrow->name)}}"
                                 alt=""/>
                            <span style="margin-left: 10px;">{{$userrow->name}}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="multitabs dropdown-item" data-url="@{{url(route('web.changepassword'))}}"
                                   href="javascript:void(0)">
                                    <i class="mdi mdi-lock-outline"></i>
                                    <span>修改密码</span>
                                </a>
                            </li>

                            <li class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{url(route('auth.logout'))}}">
                                    <i class="mdi mdi-logout-variant"></i>
                                    <span>{{ __('message.logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!--End 个人头像内容-->
                </ul>

            </nav>

        </header>
        <!--End 头部信息-->

        <!--页面主要内容-->
        <main class="lyear-layout-content">

            <div id="iframe-content"></div>

        </main>
        <!--End 页面主要内容-->
    </div>
</div>

<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/popper.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-multitabs/multitabs.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.cookie.min.js"></script>
<script type="text/javascript" src="/assets/js/index.min.js?r={{csrf_token()}}">
<script src="/assets/ajax-modal.js?t={{time()}}"></script>

<script type="text/javascript" src="/assets/js/layer/layer.js"></script>
<script type="text/javascript">
    setInterval(function () {
        $.ajax({
            url: '{{url(route('web.getinfo'))}}',
            type: 'get',
            dataType: 'json',
            success: function (data) {

                if(data.code === 401){
                    window.location.reload()
                }

                if (data.code === 200) {
                    if (data.data.notify !== '') {
                        var type = 'auto'
                            text = data.data.notify;

                        layer.open({
                            type: 1
                            , offset: 'auto' //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
                            , id: 'baseframelayer_' + type //防止重复弹出
                            , content: '<div style="padding: 20px 100px;">' + text + '</div>'
                            , btn: '关闭全部'
                            , btnAlign: 'c'
                            , shade: 0
                            , yes: function () {
                                layer.closeAll();
                            }
                        });
                    }

                }
            }
        });
    }, 10000)
</script>
</body>
</html>
