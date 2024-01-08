<div class="tab-content">
    <link rel="stylesheet" type="text/css" href="/assets/js/bootstrap-select/bootstrap-select.min.css">

    <div class="mb-3">
        <label for="" class="form-label">角色名称</label>
        <input class="form-control" type="text" id="name" value="{{$row->name}}">
    </div>

    <div class="mb-3">
        <label for="example-3">关联菜单</label>
        <select class="form-control selectpicker" data-live-search="true"  id="menus" multiple>
            @foreach(\App\Models\Menu::get() as $val)
                <option value="{{$val->id}}" @if(in_array($val->id,$role_menus)) selected @endif>{{$val->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="example-3">关联页面权限</label>
        <select class="form-control selectpicker" data-live-search="true"  id="permissions" multiple>
            @foreach(\App\Models\Permission::get() as $val)
                <option value="{{$val->id}}" @if(in_array($val->id,$role_permissions)) selected @endif>{{$val->name}}</option>
            @endforeach
        </select>
    </div>

    <input type="hidden" name="" id="id" value="{{$row->id}}">

</div>
@modalSave
<!--引入下拉插件js-->
<script type="text/javascript" src="/assets/js/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-select/i18n/defaults-zh_CN.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // 默认
        $('.selectpicker').selectpicker();
    });
</script>
<?php
$saveParam = [
    'name' => '角色名称',
    'menus' => '关联菜单',
    'id' => 'id',
    'permissions' => '关联页面权限',
];

?>
{!! Setting::autoSave($saveParam,url(route('develop.role.edit.submit')),true) !!}

