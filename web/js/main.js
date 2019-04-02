$(function () {
    /**
     * 控制隐藏左侧菜单
     */
    function show_left_men() {
        if ($.cookie('controller_left_menu') == '2') {
            $.cookie('controller_left_menu', '2');
            $('.content').css('margin-left', '0px');
            $('.sidebar-nav').css('display', 'none');
        } else {
            $.cookie('controller_left_menu', '1');
            $('.sidebar-nav').show();
            $('.content').css('margin-left', '240px');
        }
    }

    show_left_men();

    $('.show_left_menu').click(function () {

        if ($.cookie('controller_left_menu') == '1')
            $.cookie('controller_left_menu', '2');
        else
            $.cookie('controller_left_menu', '1');

        show_left_men();
    })
})

//关闭遮罩
function offUnselectable() {
    $("#unselectable").removeClass('unselectable');
}
//打开遮罩
function onUnselectable() {
    $("#unselectable").addClass('unselectable');
}
