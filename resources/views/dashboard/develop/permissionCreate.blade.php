
    <link rel="stylesheet" type="text/css" href="/assets/js/bootstrap-select/bootstrap-select.min.css">

    <div class="row">
        <div class="col-lg-12">

            <div class="mb-3">
                <label for="" class="form-label">页面名称</label>
                <input class="form-control" type="text" id="name" value="">
            </div>

            <div class="mb-3">
                <label for="example-3">URI</label>
                <input class="form-control" type="text" id="uri" value="/dashboard/">
                <code>
                    URI允许使用正则表达式，如：/dashboard/develop/role/(\d+)<br>
                </code>
            </div>

            <div class="mb-3">
                <label for="example-3">请求方式</label>
                <select class="form-control selectpicker" data-live-search="true"  id="methods" multiple>
                    <option value="*" selected>任何</option>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                    <option value="OPTIONS">OPTIONS</option>
                    <option value="HEAD">HEAD</option>
                </select>
            </div>

        </div>
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
    'name' => '页面名称',
    'uri' => 'URI',
    'methods' => '请求方式',
];

?>
{!! Setting::autoSave($saveParam,url(route('develop.permission.create.submit')),true) !!}

