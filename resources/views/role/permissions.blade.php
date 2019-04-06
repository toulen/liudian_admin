@extends('admin::layouts.app')
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" class="checkColorForm theForm" id="editForm" data-index="{{route('admin_role_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">分组名称</label>
                            <div class="col-sm-10">
                                @php $permissionIds = $data->permissionIds->pluck('permission_id') @endphp
                                @foreach($permissions as $permission)
                                    <div style="padding-left: {{$permission->depth * 20}}px">
                                    <label class="col-form-label">
                                        <input type="checkbox" name="param[permissions][]" data-lk="{{$permission->left_key}}" data-rk="{{$permission->right_key}}" class="perInput" value="{{$permission->id}}" {!! $permissionIds->contains($permission->id) ? 'checked' : '' !!} /> {{$permission->name}}
                                    </label>
                                    </div>
                                @endforeach
                            </div>
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
    <script>
        $(function (){
            $('.perInput').on('change', function (){
                var checked = $(this).prop('checked'),
                    lk = $(this).data('lk'),
                    rk = $(this).data('rk');

                $('.perInput').each(function (){
                    // 下级
                    if($(this).attr('data-lk')>lk && $(this).attr('data-rk') < rk){
                        $(this).prop('checked', checked);
                    }
                    // 上级
                    if($(this).attr('data-lk') < lk && $(this).attr('data-rk') > rk){
                        if(checked){
                            $(this).prop('checked', checked);
                        }
                    }
                })

                if(!checked){
                    // 找到此上级的所有下级
                    $('.perInput').each(function (){
                        if($(this).attr('data-lk') < lk && $(this).attr('data-rk') > rk){
                            var hasCheck = 0;
                            var p = $(this)
                            plk = p.data('lk'),
                            prk = p.data('rk');

                            $('.perInput').each(function (){
                                if($(this).attr('data-lk') > plk && $(this).attr('data-rk') < prk){
                                    if ($(this).prop('checked')) {
                                        console.log($(this).parent().html())
                                        hasCheck = 1;
                                        return false;
                                    }
                                }
                            })

                            if(!hasCheck){
                                $(this).prop('checked', checked);
                            }
                        }
                    })
                }
            });
        });
    </script>
@endsection