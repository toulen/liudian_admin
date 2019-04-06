@extends('admin::layouts.app')
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" class="checkColorForm theForm" id="editForm" data-index="{{route('admin_account_index')}}">

                        <div class="form-group  row"><label class="col-sm-2 col-form-label">登录账号</label>
                            <div class="col-sm-10">
                                <label class="col-form-label">{{$data->username}}</label>
                            </div>
                        </div>
                        @if($data->id == \AdminAuth::user()->id)
                            <div class="form-group  row"><label class="col-sm-2 col-form-label">旧密码</label>
                                <div class="col-sm-10"><input type="password" class="form-control" id="passwordField" name="param[old_password]" value="" required minlength="6"></div>
                            </div>
                        @endif
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">新密码</label>
                            <div class="col-sm-10"><input type="password" class="form-control" id="passwordField" name="param[password]" value="" required minlength="6" placeholder="最少6位（字母，数字，下划线）"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">确认新密码</label>
                            <div class="col-sm-10"><input type="password" class="form-control" name="param[password_confirmation]" value="" required minlength="6"></div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="reset">重置表单</button>
                                <button class="btn btn-primary" type="submit">确认修改</button>
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