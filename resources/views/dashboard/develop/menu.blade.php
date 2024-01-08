@extends('layouts.basic',['title' => '工作区'])

@section('content')
    <link rel="stylesheet" type="text/css" href="/assets/js/zTree_v3/css/materialDesignStyle/materialdesign.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/fontIconPicker/css/jquery.fonticonpicker.min.css">
    <link rel="stylesheet" type="text/css"
          href="/assets/js/fontIconPicker/themes/bootstrap-theme/jquery.fonticonpicker.bootstrap.min.css"/>

    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <div class="card-title">菜单结构</div>
            </header>
            <div class="card-body">
                <button class="btn btn-primary btn-sm" id="saveTree">保存层级</button>
                <br><br>
                <ul id="treeDemo" class="ztree"></ul>

            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <header class="card-header">
                <div class="card-title">创建菜单</div>
            </header>
            <div class="card-body">

                <div class="mb-3">
                    <label for="" class="form-label">菜单标题</label>
                    <input class="form-control" type="text" id="name">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">菜单图标</label><br>
                    <input type="text" id="icon" name="icon"/>
                    <span id="show-mdi"></span>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">父级</label>
                    <select class="form-control" id="parent_id">
                        <option value="0">无</option>
                        @foreach($menus as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">菜单链接</label>
                    <input class="form-control" type="text" id="path">
                </div>

                <button class="btn btn-xs btn-primary" id="save">创建</button>


            </div>
        </div>
    </div>
@endsection

@section('js')


    <?php
    $saveParam = [
        'name' => '菜单标题',
        'icon' => '菜单图标',
        'parent_id' => '父级',
        'path' => '菜单链接',
    ];

    ?>

    {!! Setting::autoSave($saveParam,url(route('develop.menu.create')),true) !!}


    <script type="text/javascript" src="/assets/js/zTree_v3/js/jquery.ztree.all.min.js"></script>
    <script type="text/javascript" src="/assets/js/fontIconPicker/jquery.fonticonpicker.min.js"></script>
    <script type="text/javascript">
        var setting = {
            view: {
                addHoverDom: false,
                removeHoverDom: false,
                selectedMulti: false
            },
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            edit: {
                enable: true,
                showRemoveBtn: true,
                showRenameBtn: false,
                drag: {
                    isCopy: false,
                    isMove: true,
                    prev: true,
                    next: true,
                    inner: true
                }
            },
            callback: {//回调函数
                onClick: function (event, treeId, treeNode) {//点击每个节点回调
                    open_ajax_modal('编辑菜单', '{{url(route('develop.menu.edit'))}}?id=' + treeNode.id);

                },
                beforeRemove: function (treeId, treeNode) {
                    name = treeNode.name;
                    open_ajax_del('你确定要删除菜单”'+name+'“以及他的子菜单吗？', '{{url(route('develop.menu.delete'))}}?id=' + treeNode.id, '删除菜单')
                    return false;
                },

            }
        };
        zNodes = @json($zNodes)

        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);

            var font_element = $('#icon').fontIconPicker({
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

            $(document).on('change', '#icon', function () {
                $('#show-mdi').html($(this).val());
            });

        });

        $('#saveTree').click(function () {
            //     获取层级信息
            var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var nodes = treeObj.getNodes();
            var data = treeObj.transformToArray(nodes);
            var data = JSON.stringify(data);
            var loader = layer.msg('正在保存...', {icon: 16, shade: 0.3, time: 0});
            $.ajax({
                url: '{{url(route('develop.menu.save'))}}',
                type: 'POST',
                dataType: 'json',
                data: {
                    data: data,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    layer.close(loader);
                    if (res.code == 200) {
                        layer.msg('保存成功', {icon: 1, time: 500});
                    } else {
                        layer.msg('保存失败：' + res.msg, {icon: 2, time: 500});
                    }
                },
                error: function (res) {
                    layer.close(loader);
                    layer.msg('保存失败', {icon: 2});
                }

            })

        });
    </script>

@endsection
