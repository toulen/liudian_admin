@extends('admin::layouts.app')

@section('content')

    <div class="wrapper wrapper-content">
        <div class="middle-box text-center animated fadeInRightBig">
            <h3 class="font-bold">您暂无权限访问该页面</h3>
            <div class="error-desc">
                请联系管理员开通此页面访问权限！
                <br/><a href="{{route('admin_index')}}" class="btn btn-primary m-t">管理首页</a>
            </div>
        </div>
    </div>
@endsection