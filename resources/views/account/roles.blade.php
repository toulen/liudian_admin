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
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">所属分组</label>
                            <div class="col-sm-10">
                                @php $hasRoles = $data->roleIds->pluck('role_id') @endphp
                                @foreach($roles as $role)
                                    <div>
                                        <label class="col-form-label">
                                            <input type="checkbox" name="param[role][]" value="{{$role->id}}" {!! $hasRoles->contains($role->id) ? 'checked' : '' !!} /> {{$role->name}}
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
@endsection