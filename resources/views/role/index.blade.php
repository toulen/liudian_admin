@extends('admin::layouts.app')

@section('css')
    <link href="{{asset('admin/js/layui/css/layui.css')}}" rel="stylesheet" rev="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="ibox-content m-b-sm border-bottom">
        <form id="searchForm">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="phone">分组名称</label>
                        <input type="text" id="name" name="name" value="" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                </div>
                <div class="col-sm-6">
                    <div class="form-group pull-right">
                        <label class="col-form-label">&nbsp;</label>
                        <div>
                            <a href="javascript:;" id="searchFromBtn" class="btn btn-success">搜索</a>
                            @component('admin::layouts.btn', ['route' => 'admin_role_create'])
                                <a href="{{route('admin_role_create')}}" class="btn btn-primary">创建管理分组</a>
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table id="dataTable" lay-filter="list"></table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="bar">
        @component('admin::layouts.btn', ['route' => 'admin_role_permissions'])
        <a href="/{{config('liudian_admin.route_prefix')}}/role/permissions/@{{ d.id }}" class="btn btn-info btn-xs">权限管理</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_role_edit'])
        <a href="/{{config('liudian_admin.route_prefix')}}/role/edit/@{{ d.id }}" class="btn btn-info btn-xs">编辑</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_role_delete'])
        <a href="javascript:;" class="btn btn-danger btn-xs" lay-event="delete">删除</a>
        @endcomponent
    </script>
@endsection
@section('js')
    <script src="{{asset('admin/js/layui/layui.js')}}"></script>
    <script>
        layui.use('table', function(){
            var table = layui.table;

            //第一个实例
            table.render({
                elem: '#dataTable'
                ,url: '{{route('admin_role_index')}}' //数据接口
                ,method: 'post'
                ,where:{_token:'{{csrf_token()}}'}
                ,data: {'_token':'{{csrf_token()}}'}
                ,page: {
                    layout: ['prev', 'page', 'next']
                } //开启分页
                ,limit: 10
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, fixed: 'left'},
                    {field: 'name', title: '分组名称'},
                    {field:'', title: '操作', align:'center', width: 180, templet: '#bar',fixed: 'right'}
                ]]
            });

            //监听工具条
            table.on('tool(list)', function(obj){
                console.log(obj)
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;

                if(layEvent === 'delete'){ //删除
                    layer.confirm('确认删除当前数据？删除后无法撤回！', function(index){
                        postRequest('/{{config('liudian_admin.route_prefix')}}/role/delete/' + data.id, {}, true, function (res){
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                            layer.close(index);
                        }, function (){});
                    });
                }
            });


            $('#searchFromBtn').on('click', function (){
                var data = $('#searchForm').serializeJSON();
                table.reload('dataTable', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        param: data
                    }
                });
            });

            $('#searchForm').submit(function (){
                var data = $(this).serializeJSON();
                return false;
            });

        });
    </script>
@endsection