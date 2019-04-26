//dom加载完成后执行的js
$(function () {
    // ajax post 请求
    $('.ajax-post').click(function () {
        var data = $('form').serialize();
        var url = $(this).attr('data-url');

        $.post(url, data, function (data) {
            if (data.status == 1) {
                sAlert.success(data.info);
                if (data.url != undefined || data.url != '') {
                    function go() {
                        window.location.href = data.url;
                    }

                    setTimeout(go, 1000);
                }
            }
            if (data.status == 0) {
                sAlert.error(data.info);
                return false;
            }
            if (data.target == 'back') {
                function back() {
                    history.back(-1);
                }

                setTimeout(back, 1000);
                return false;
            }
        }, 'json');
    });
    //更新请求
    $('.ajax-put').click(function () {
        var data = $('form').serialize();
        var url = $(this).attr('data-url');
        $.ajax({
            url: url,
            data: data,
            dataType: 'json',
            method: 'put',
            success: function (data) {
                if (data.status == 1) {
                    sAlert.success(data.info);
                }
                if (data.status == 0) {
                    sAlert.error(data.info);
                    return false;
                }
                if (data.target == 'back') {
                    function back() {
                        history.back(-1);
                    }
                    setTimeout(back, 1000);
                    return false;
                }
                if (data.url != undefined || data.url != '') {
                    function go() {
                        window.location.href = data.url;
                    }
                    setTimeout(go, 1000);
                }
            }
        })
    });
    $('.ajax-get').click(function () {
        var data = $(this).attr('data-id');
        var url = $(this).attr('data-url');
        $.get(url, { 'admin_id': data }, function (data) {
            if (data.status == 1) {
                sAlert.success(data.info);
            }
            if (data.status == -1) {
                sAlert.error(data.info);
                return false;
            }
            if (data.target == 'back') {
                history.back(-1);
                return false;
            }
            if (data.target = 'reload') {
                function reload() {
                    location.reload()
                }

                setTimeout(reload, 1000);
                return false;
            }
            if (data.url != undefined || data.url != '') {
                function go() {
                    window.location.href = data.url;
                }

                setTimeout(go, 1000);
            }
        }, 'json');
    });

    // ajax delete 一个 请求
    $('.ajax-delete-one').click(function () {
        var id = $(this).attr('data-id');
        var url = $(this).attr('data-url');
        var title = $(this).attr('data-title');
        sAlert.confirm(url, id, title);
    });

    // ajax delete 全部 请求
    $('.ajax-delete-many').click(function () {
        var id = [];
        $('.ids').each(function (index) {
            if ($(this).is(':checked')) {
                id[index] = $(this).val();
            }
        });
        var url = $(this).attr('data-url');
        var title = $(this).attr('data-title');
        sAlert.confirm(url, id, title);
    });

});

function highlight_subnav(url) {
    $('#subnav').find("a[href='" + url + "']").css({ 'color': '#738399' }).parents('.content').addClass('active');
}

function reload() {
    location.reload()
}

function back() {
    history.back(-1);
}

//定义公共的弹窗
var sAlert = {};
//操作成功的弹框
sAlert.error = function (code) {
    swal({
        title: "",
        text: code,
        timer: 2000,
        type: 'error',
        showConfirmButton: false,
        allowOutsideClick: false,
        animation: false
    });
};
//操作失败的弹窗
sAlert.success = function (code) {
    swal({
        title: "",
        text: code,
        timer: 1500,
        type: 'success',
        showConfirmButton: false,
        allowOutsideClick: false,
        animation: false
    });
};
//删除的弹框
sAlert.confirm = function (url, id, title) {
    swal({
            title: '',
            text: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定删除！",
            allowOutsideClick: true,
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { id: id },
                success: function (data, status) {
                    if (data.status == 1) {
                        sAlert.success(data.info);
                        setTimeout(reload(), 1500);
                    } else {
                        sAlert.error(data.info);
                    }

                }
            });
        });
};
//导航高亮
function highlight_subnav(url) {
    $('.sidebar').find("a[href='" + url + "']").addClass('active').parents('.content').addClass('active');
    $('.sidebar').find("a[href='" + url + "']").addClass('active').parents('.content').prev('.item.title').addClass('active');
    $('.sidebar').find("a[href='" + url + "']").addClass('active').parents('.content').prev('.item.title').find('.chevron').removeClass('down').addClass('up');
}
//全选节点
$('.check-all').on('change', function () {
    $(this).parents('table').find('tbody tr').find('.ids').prop('checked', this.checked);
});
//blockUi
// $.unblockUI();
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);


