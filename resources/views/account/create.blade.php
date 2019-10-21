@extends('admin::layouts.app')
@section('css')
    <style>
        .logoFormBtn{
            display: block;
            width: 160px;
            height: 160px;
            border: 1px solid #ddd;
            padding: 2px;
            position: relative;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" class="checkColorForm theForm" id="createdForm" data-index="{{route('admin_account_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">登录账号</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[username]" placeholder="最少5个字符！（字母，数字，下划线）" value="" required></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">姓名</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[nickname]" value="" placeholder="必填" required></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">手机号码</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[phone]" value="" placeholder="可选"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">EMAIL</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[email]" value="" placeholder="可选"></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">密码</label>
                            <div class="col-sm-10"><input type="password" class="form-control" id="passwordField" name="param[password]" value="" required minlength="6" placeholder="最少6位（字母，数字，下划线）"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">确认密码</label>
                            <div class="col-sm-10"><input type="password" class="form-control" name="param[password_confirmation]" value="" required minlength="6"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">账号状态</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[status]">
                                    <option value="1">开启</option>
                                    <option value="0">关闭</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">登录后跳转页面</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[default_route]">
                                    @foreach($permissions as $permission)
                                        @if($permission->nav_show)
                                            <option value="{{$permission->route_name}}" {!! $permission->id == 2 ? 'selected' : '' !!}>@for($i = 0; $i < $permission->depth; $i++) &nbsp; &nbsp; &nbsp;@endfor{{$permission->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">头像</label>
                            <div class="col-sm-10">
                                <div class="logoFormBtn uploadBtn text-center">
                                    <h4 style="padding-top: 60px">选择图片</h4>
                                    <h5>220 * 220</h5>
                                </div>
                                <input type="hidden" class="form-control theHeadImg" name="param[head_img]" value="">
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
    <script src="{{asset('admin/js/jquery.html5upload.js')}}"></script>
    <script>
        $(".theForm").validate({
            rules:{
                "param[phone]": {
                    isphone: ''
                },
                "param[re_password]": {
                    equalTo:"#passwordField"
                }
            }
        });
        $('.uploadBtn').h5upload({
            fileTypeExts: 'jpg,png,gif,jpeg',
            url: "{!! URL::route('upload_ajax') !!}",
            fileObjName: 'upload',
            fileSizeLimit: 10 * 1024 * 1024,
            formData: {'_token': '{{ csrf_token() }}', 'type': 'image'},

            //进度监控
            onUploadProgress: function (file, data) {
                $('.uploadBtn').html('<p style="padding-top: 50px;">上传中...</p>');
            },

            // 上传成功的动作
            onUploadSuccess: function (file, res) {
                res = JSON.parse(res);
                if(res.status == 1){
                    var img = $('<img src="'+ res.data.url +'" width="160" height="160" />');
                    $('.uploadBtn').html(img);
                    $('.theHeadImg').val(res.data.url);
                }else{
                    layer.msg('上传失败了！');
                }
            }
        });
    </script>

@endsection