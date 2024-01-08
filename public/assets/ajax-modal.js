$(document).ready(function() {
    $(".ajax-del").click(function(e) {
        // alert(1)
        url = $(this).attr("link");
        text = $(this).attr("text");
        ts = $(this).attr("ts");
        if (url == '' || text == '') return;
        var modal = function(title, text) {
            $("body").append('<div id="modal"><div class="modal fade" id="ajax_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h6 class="modal-title" id="exampleModalLabel">' + title + '</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">' + text + '</div><div class="modal-footer"><button class="btn btn-default ajax_delete_close">关闭</button><button id="confirm" class="btn btn-primary">确定</button></div></div></div></div></div>');

            $("#ajax_delete").modal({
                backdrop: "static",
                keyboard: false,
            });
            $("#ajax_delete").modal("show");
        }
        if (ts == undefined) t = '确认删除';
        else t = ts;
        modal(t, text);
        $("#confirm").click(function() {
            load = layer.msg('正在进行...', {
                icon: 16,
                shade: 0.3,
            });
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                success: function(data) {
                    if (data.code == 200) {

                        layer.close(load);
                        layer.msg('操作成功', {
                            icon: 1,
                            time: 500
                        }, function() {
                            // 关闭
                            $("#ajax_delete").modal("hide");
                            window.location.reload();
                        });
                    } else {

                        layer.close(load);
                        layer.msg(data.msg, {
                            icon: 2,
                            time: 1000
                        });
                    }
                },
                error: function(data) {
                    layer.close(load);
                    layer.msg("网络错误", {
                        icon: 2,
                        time: 1000
                    });
                }
            });
        });

        $(document).on("click", ".ajax_delete_close", function() {
            $("#ajax_delete").modal("hide");
        });
        // 获取关闭事件
        $(document).on("hidden.bs.modal", "#ajax_delete", function() {
            // 先关闭modal
            $("#ajax_delete").modal("hide");
            setTimeout(() => {
                $("#modal").remove();
            }, 300);
        });


    })

    $(".ajax-modal").click(function(e) {
        load = layer.msg('正在进行...', {
            icon: 16,
            shade: 0.3,
        });
        url = $(this).attr("link");
        text = $(this).attr("title");
        maxModal = $(this).attr("maxModal");
        style = ''
        // 如果包含maxModal
        if (maxModal != undefined) {
            style = 'width:800px'
        }
        if (url == undefined || text == undefined) return;
        // history.pushState(null, null, url);
        var modal = function(title, content) {
            $("body").append('<div id="modal"><div class="modal fade " id="ajax_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"   role="document"><div class="modal-content" style="'+style+'"><div class="modal-header"><h6 class="modal-title" id="exampleModalLabel">' + title + '</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">' + content + '</div></div></div></div></div>');

            $("#ajax_modal").modal({
                backdrop: "static",
                keyboard: false,
            });
            $("#ajax_modal").modal("show");
        }
        $.ajax({
            type: "get",
            url: url,
            headers: {
                'type': 'ajax-modal'
            },
            data: {
                _: new Date().getTime()
            },
            cache: false,
            dataType: "html",
            success: function(response) {
                layer.close(load);
                modal(text, response);

            },
            error: function(response) {
                layer.close(load);
                var html = response.responseText;
                modal(text, html);
            }
        });


        // 监听ajax-modal-close
        $(document).on("click", ".ajax-modal-close", function() {
            $("#ajax_modal").modal("hide");
        });
        // 获取关闭事件
        $(document).on("hidden.bs.modal", "#ajax_modal", function() {
            // 先关闭modal
            $("#ajax_modal").modal("hide");
            setTimeout(() => {
                $("#modal").remove();
            }, 300);
        });
    });
});

function open_ajax_modal(title,url,maxModal=undefined){
    load = layer.msg('正在进行...', {
        icon: 16,
        shade: 0.3,
    });
    text = title;
    style = ''
    // 如果包含maxModal
    if (maxModal != undefined) {
        style = 'width:800px'
    }
    if (url == undefined || text == undefined) return;
    // history.pushState(null, null, url);
    var modal = function(title, content) {
        $("body").append('<div id="modal"><div class="modal fade " id="ajax_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"   role="document"><div class="modal-content" style="'+style+'"><div class="modal-header"><h6 class="modal-title" id="exampleModalLabel">' + title + '</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">' + content + '</div></div></div></div></div>');

        $("#ajax_modal").modal({
            backdrop: "static",
            keyboard: false,
        });
        $("#ajax_modal").modal("show");
    }
    $.ajax({
        type: "get",
        url: url,
        headers: {
            'type': 'ajax-modal'
        },
        data: {
            _: new Date().getTime()
        },
        cache: false,
        dataType: "html",
        success: function(response) {
            layer.close(load);
            modal(text, response);

        },
        error: function(response) {
            layer.close(load);
            var html = response.responseText;
            modal(text, html);
        }
    });
}

function open_ajax_del(text,url,ts) {
    if (url == '' || text == '') return;
    var modal = function (title, text) {
        $("body").append('<div id="modal"><div class="modal fade" id="ajax_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h6 class="modal-title" id="exampleModalLabel">' + title + '</h6><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">' + text + '</div><div class="modal-footer"><button class="btn btn-default ajax_delete_close">关闭</button><button id="confirm" class="btn btn-primary">确定</button></div></div></div></div></div>');

        $("#ajax_delete").modal({
            backdrop: "static",
            keyboard: false,
        });
        $("#ajax_delete").modal("show");
    }
    if (ts == undefined) t = '确认删除';
    else t = ts;
    modal(t, text);
    $("#confirm").click(function () {
        load = layer.msg('正在进行...', {
            icon: 16,
            shade: 0.3,
        });
        $.ajax({
            url: url,
            type: "get",
            dataType: "json",
            success: function (data) {
                if (data.code == 200) {

                    layer.close(load);
                    layer.msg('操作成功', {
                        icon: 1,
                        time: 500
                    }, function () {
                        // 关闭
                        $("#ajax_delete").modal("hide");
                        window.location.reload();
                    });
                } else {

                    layer.close(load);
                    layer.msg(data.msg, {
                        icon: 2,
                        time: 1000
                    });
                }
            },
            error: function (data) {
                layer.close(load);
                layer.msg("网络错误", {
                    icon: 2,
                    time: 1000
                });
            }
        });
    });

    $(document).on("click", ".ajax_delete_close", function () {
        $("#ajax_delete").modal("hide");
    });
    // 获取关闭事件
    $(document).on("hidden.bs.modal", "#ajax_delete", function () {
        // 先关闭modal
        $("#ajax_delete").modal("hide");
        setTimeout(() => {
            $("#modal").remove();
        }, 300);
    });
}
