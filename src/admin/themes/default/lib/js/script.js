/**
 * Copyright 2020 by weDevCode
 * All Right Reserved
 * wdcDoc is a product own by weDevCode
 */
$(function () {
    // Gắn chữ thông báo
    $('main').append('<div id="notice">Thanks for using wdcDoc</div>');
    // Lấy giá trị của menu
    let wdc_ad_nav2_width = $('#wdc_ad_nav2').width();
    // Chiều dài màn hình
    let windowWidth = $(window).width();
    $('main').width(windowWidth-wdc_ad_nav2_width);
    $('#wdc_ad_nav2 ul li > a span').css('display', 'inline');
    // hàm chuyển dạng Xpx sang X
    function pxToInt(val) {
        return parseInt(val.replace('px', ''));
    }
    $('#adminInfo ~ ul').width(pxToInt($('#adminInfo').css('paddingRight'))+pxToInt($('#adminInfo').css('paddingLeft'))+$('#adminInfo').width());
    let collapseCol = false;
    $('#wdc_collapseActivate').on('click', function (){
        switch (collapseCol) {
            case false:
            $('#wdc_ad_nav2').addClass('wdc_ad_nav2_collapsedMenu');
            $('#wdc_ad_nav2>ul>li>ul').addClass('collapsedSubMenu_01');
            $('#wdc_collapseActivate #wdc_collapseActivateIcon > svg').css('transform', 'rotateZ(180deg)');
            $('main').animate({left: '45px'},'fast');
            $('main').width(windowWidth-45);
            collapseCol = true;
            break;
            
            default:
            $('#wdc_ad_nav2').removeClass('wdc_ad_nav2_collapsedMenu');
            $('#wdc_ad_nav2>ul>li>ul').removeClass('collapsedSubMenu_01');
            $('#wdc_collapseActivate #wdc_collapseActivateIcon > svg').css('transform', 'rotateZ(0)');
            $('main').animate({left: '200px'},'fast');
            $('main').width(windowWidth-wdc_ad_nav2_width);
            collapseCol = false;
            break;
        }
        if (collapseCol) {
            setTimeout(function (){
                $('#wdc_ad_nav2 ul li > a span').css('display', '');
            },150);
        } else {
            setTimeout(function (){
                $('#wdc_ad_nav2 ul li > a span').css('display', 'inline');
            },150);
        }
    })
    tinymce.init({
        id: 'textarea',
        selector: '#textarea',
        height: 500,
        menubar: false,
        plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code',
        'insertdatetime media table paste code help wordcount codesample'
        ],
        toolbar: 'undo redo | formatselect image link | ' +
        'bold italic underline backcolor codesample | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat',
        branding: false,
    });
    window.onbeforeunload = function (){
        return;
    }
});