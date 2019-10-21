<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{csrf_token()}}">
    <title>登录{{config('liudian_admin.backend_name')}}</title>

    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
    <style>
        label.error{
            display: block;
            text-align: left;
        }
        .logo-name{
            font-size: 40px;
            letter-spacing: -4px;
        }
    </style>

</head>

<body class="">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <div class="logo-name text-center">LOGIN</div>
        </div>
        <div class="gray-bg " style="padding: 20px">
        <h3>欢迎登录{{config('liudian_admin.backend_name')}}</h3>
        <p>登录过程中遇到问题，请联系管理员！</p>
        <form class="loginForm" role="form">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="登录账号" required="">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="登录密码" required="" minlength="6">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-7">
                        <input type="text" name="captcha" class="form-control" placeholder="验证码" required="" minlength="4">
                    </div>
                    <div class="col-md-5">
                        <img class="captcha-image" onclick="updateCaptcha($(this))" src="{{route('admin_public_captcha')}}" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary pull-left">
                    <input id="checkbox2" name="remember" value="1" type="checkbox">
                    <label for="checkbox2">
                        记住登录状态
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">确认登录</button>

        </form>
        <p class="m-t"> <small>{{config('liudian_admin.backend_name')}} &copy; 2019 - 2022</small> </p>
        </div>
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{asset('admin/js/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('admin/js/popper.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap.js')}}"></script>
<script src="{{asset('admin/js/plugins/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('admin/js/layer/layer.js')}}"></script>
<script src="{{asset('admin/js/common.js')}}"></script>
<script src="{{asset('admin/js/jquery.serializeJSON/jquery.serializejson.js')}}"></script>
<script>

    function updateCaptcha($obj){
        console.log(1);
        $obj.attr('src', '{{route('admin_public_captcha')}}?v=' + Math.random());
    }

    $(".loginForm").validate({
        submitHandler: function() {
            var _data = $('.loginForm').serializeJSON()

            postRequest('{{route('admin_login')}}', _data, true, function (res){
                {{--var url = '{{ isset($prev_url) && $prev_url ? $prev_url : "" }}';--}}

                {{--if(!url){--}}
                    {{--url = res.data.url;--}}
                {{--}--}}
                window.location.href = '{{route("admin_index")}}'

            }, function (res){
                updateCaptcha($('.captcha-image'));
            })
            return false;
        }
    });
</script>

</body>

</html>
