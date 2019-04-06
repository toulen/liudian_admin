@extends('admin::layouts.app')
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" class="checkColorForm theForm" id="createdForm" data-index="{{route('admin_permission_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">上级菜单</label>
                            <div class="col-sm-10">
                                <select name="param[parent_id]" class="form-control">
                                    <option value="0">一级菜单</option>
                                    @foreach($permissions as $permission)
                                    <option value="{{$permission->id}}">@for($i=0;$i<$permission->depth;$i++)&nbsp; &nbsp;@endfor{{$permission->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">菜单名称</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[name]" placeholder="" value="" required></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">菜单路由</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[route_name]" placeholder="" value=""></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">菜单图标</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[icon]" placeholder="" value="navicon"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">是否显示</label>
                            <div class="col-sm-10">
                                <select name="param[nav_show]" class="form-control">
                                    <option value="1">显示</option>
                                    <option value="0">不显示</option>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="reset">重置表单</button>
                                <button class="btn btn-primary" type="submit">确认创建</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection