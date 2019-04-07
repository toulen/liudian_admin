<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{csrf_token()}}">

    <title>{{config('liudian_admin.backend_name')}}</title>
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/codemirror/codemirror.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/codemirror/ambiance.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
    <style>
        .nav-line{
            height: 1px;
            background: #676a6c;
            margin: 4px 20px 4px 25px;
        }
        .layui-layer-btn a{
            color: #fff;
        }
        .navbar-top-links .active{
            border-left: 0!important;
            border-bottom: 2px solid #19aa8d;
            background: #f9f9f9!important;
        }
        .navbar-top-links .active > a{
            background: #fff;
            border-left: 0;
            color: #000!important;
        }
        .navbar-top-links li a{
            padding-left: 15px;
            padding-right: 15px;
        }
        .navbar-top-links li a:hover{
            background: #f9f9f9!important;
        }
    </style>
    @yield('css')

    <script src="{{asset('admin/js/jquery-3.1.1.min.js')}}"></script>
</head>

<body class="fixed-sidebar no-skin-config full-height-layout {{ !isset($layout) || !$layout ? 'mini-navbar' : '' }}">

<div id="wrapper">

    @if(isset($layout) && $layout)
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <img alt="image" class="rounded-circle pull-left" src="{{\AdminAuth::user()->head_img ? :asset('admin/img/default_head.png')}}" width="50" height="50"/>
                            <a data-toggle="dropdown" class="dropdown-toggle pull-left" href="#" style="padding-left: 15px">
                                <span class="block m-t-xs font-bold">{{\AdminAuth::user()->nickname}}</span>
                                <span class="text-muted text-xs block">点击操作 <b class="caret"></b></span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs text-center">
                                <li><a class="dropdown-item text-center" href="{{route('admin_account_edit', \AdminAuth::user()->id)}}">个人资料</a></li>
                                <li><a class="dropdown-item text-center" href="{{route('admin_account_edit_password', \AdminAuth::user()->id)}}">修改密码</a></li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <form method="post" action="{{route('admin_logout')}}">{!! csrf_field() !!}<button type="submit" class="btn" style="width: 100%">退出登录</button></form>
                                    </li>
                            </ul>
                        </div>
                    </li>
                    @php $nowLevel = 0; $levelText = ['', 'second', 'third'] @endphp
                    @foreach($leftNavs as $key => $nav)
                        @php $next = isset($leftNavs[$key + 1]) ? $leftNavs[$key + 1] : null @endphp
                        @if(\AdminAuth::user()->can($nav->id))
                            <li {!! in_array($nav->id, $pageActive) ? 'class="active"' : '' !!}>
                                <a href="{{$nav->route_name ? route($nav->route_name) : ''}}" {{$nav->left_key != $nav->right_key - 1 ? 'aria-expanded="false"' : ''}}>@if($nav->depth == 1)<i class="fa fa-{{$nav->icon}}"></i>@endif{{$nav->name}}
                                    @if($next && $next->parent_id == $nav->id)
                                    <span class="fa arrow"></span>
                                    @endif
                                </a>
                                @if($next && $next->parent_id == $nav->id)
                                    @php $nowLevel += 1 @endphp
                                    <ul class="nav nav-{{isset($levelText[$nowLevel]) ? $levelText[$nowLevel] : 'third'}}-level collapse">
                                @endif

                                @if(isset($next) && $next->depth < $nav->depth)
                                    @for($i = 0; $i < $nav->depth - $next->depth; $i++)
                                    </ul>
                                    @endfor
                                    @php $nowLevel -= $nav->depth - $next->depth @endphp
                                @endif
                            </li>

                        @endif
                    @endforeach
                </ul>

            </div>
        </nav>
    @endif

    <div id="page-wrapper" class="gray-bg" style="height: auto">
        @if(isset($layout) && $layout)
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                        <ul class="nav navbar-top-links" style="padding-left: 50px">
                            @foreach($firstDepthNav as $nav)
                                @if(\AdminAuth::user()->can($nav->id))
                            <li class="{{in_array($nav->id, $pageActive) ? 'active' : ''}}">
                                <a class="" href="{{route($nav->route_name)}}">
                                    {{$nav->name}}
                                </a>
                            </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <ul class="nav navbar-top-links pull-right">
                        <li>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i>
                                {{--<span class="label label-warning">16</span>--}}
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a class="dropdown-item float-left" href="profile.html">
                                        </a>
                                        <div class="media-body">
                                            <small class="float-right">46h ago</small>
                                            <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a class="dropdown-item float-left" href="profile.html">
                                        </a>
                                        <div class="media-body ">
                                            <small class="float-right text-navy">5h ago</small>
                                            <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a class="dropdown-item float-left" href="profile.html">
                                        </a>
                                        <div class="media-body ">
                                            <small class="float-right">23h ago</small>
                                            <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                            <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="mailbox.html" class="dropdown-item">
                                            <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <form method="post" action="{{route('admin_logout')}}">
                                {!! csrf_field() !!}
                                <button class="btn-danger">
                                    <i class="fa fa-sign-out"></i> 退出登录
                                </button>
                            </form>
                        </li>
                    </ul>

                </nav>
            </div>
        @endif


        <div class="wrapper wrapper-content">
            <div class="row wrapper border-bottom page-heading">
                    <div class="col-lg-12">
                        <h2 class="pull-left">{{$pageTitle}}</h2>
                        <ol class="breadcrumb pull-right" style="margin-top: 20px; background: #f3f3f4">
                            <li class="breadcrumb-item">
                                <a href="{{route('admin_index')}}">首页</a>
                            </li>
                            @foreach($pageBreadcrumb as $key => $breadcrumb)
                                <li class="breadcrumb-item {!! $key == count($pageBreadcrumb) -1 ? 'active' : '' !!}">
                                    <a {!! $breadcrumb['link'] ? 'href="'.$breadcrumb['link'].'"' : '' !!}>{{$breadcrumb['name']}}</a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

            @if($errors && $errors->count())
                @php $errorsInfo = $errors->get('error') @endphp
                @php $successInfo = $errors->get('success') @endphp
                @if($errorsInfo)
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{$errorsInfo[0]}}
                    </div>
                    <script>
                        $(function (){
                            setTimeout(function () {
                                $('.alert-danger').fadeOut();
                            }, 3000)
                        })
                    </script>
                @endif
                @if($successInfo)
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        {{$successInfo[0]}}
                    </div>
                    <script>
                        $(function (){
                            setTimeout(function () {
                                $('.alert-success').fadeOut();
                            }, 3000)
                        })
                    </script>
                @endif
            @endif
            @yield('content')
        </div>


        @if(isset($layout) && $layout)
            <div class="footer">
                <div class="float-right">
                    {{config('liudian_admin.backend_name')}}
                </div>
                <div>
                    <strong>Copyright</strong> {{config('liudian_admin.backend_name')}} &copy; 2019-{{date('Y') + 3}}
                </div>
            </div>
        @endif

    </div>

</div>



<!-- Mainly scripts -->
<script src="{{asset('admin/js/popper.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap.js')}}"></script>
<script src="{{asset('admin/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('admin/js/inspinia.js')}}"></script>
<script src="{{asset('admin/js/plugins/pace/pace.min.js')}}"></script>
<script src="{{asset('admin/js/plugins/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin/js/jquery.serializeJSON/jquery.serializejson.min.js')}}"></script>
<script src="{{asset('admin/js/layer/layer.js')}}"></script>
<script src="{{asset('admin/js/common.js')}}"></script>
@yield('js')
</body>

</html>
