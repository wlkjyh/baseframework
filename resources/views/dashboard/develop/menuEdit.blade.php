<div class="tab-content">
    <link rel="stylesheet" type="text/css" href="/assets/js/fontIconPicker/css/jquery.fonticonpicker.min.css">
    <link rel="stylesheet" type="text/css"
          href="/assets/js/fontIconPicker/themes/bootstrap-theme/jquery.fonticonpicker.bootstrap.min.css"/>


    <div class="mb-3">
        <label for="" class="form-label">菜单标题</label>
        <input class="form-control" type="text" id="names" value="{{$row->name}}">
    </div>

    <div class="mb-3">
        <label for="" class="form-label">菜单图标</label><br>
        <input type="text" id="icons" name="icon" value="{{$row->icon}}"/>
        <span id="show-mdis"></span>
    </div>

    <div class="mb-3">
        <label for="" class="form-label">菜单链接</label>
        <input class="form-control" type="text" id="paths" value="{{$row->path}}">
    </div>



</div>
</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button><button type="button" class="btn btn-primary" id="modalsave">保存</button>

<script type="text/javascript" src="/assets/js/fontIconPicker/jquery.fonticonpicker.min.js"></script>
<script !src="">

        var font_element = $('#icons').fontIconPicker({
            theme: 'fip-bootstrap'
        });

        $.ajax({
            url: '/assets/js/fontIconPicker/fontjson/materialdesignicons.json',
            type: 'GET',
            dataType: 'json'
        }).done(function (response) {

            var fontello_json_icons = [];

            $.each(response.glyphs, function (i, v) {
                fontello_json_icons.push(v.css);
            });

            font_element.setIcons(fontello_json_icons);
        }).fail(function () {
            console.error('字体图标配置加载失败');
        });

        $(document).on('change', '#icons', function () {
            $('#show-mdis').html($(this).val());
        });


</script>
<input type="hidden" id="id" value="{{$row->id}}">
<?php
$saveParam = [
    'id' => 'id',
    'names' => '菜单标题',
    'icons' => '菜单图标',
    'paths' => '菜单链接',
];

?>
{!! Setting::autoSave($saveParam,url(route('develop.menu.edit.submit')),true,saveButtonId:'modalsave') !!}

