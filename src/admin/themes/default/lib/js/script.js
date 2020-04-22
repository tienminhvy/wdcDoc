/**
 * Copyright 2020 by weDevCode
 * All Right Reserved
 * wdcDoc is a product own by weDevCode
 */
$(function () {
    $('main').append('<div id="notice">Thanks for using wdcDoc</div>');
    let wdc_ad_nav2_width = $('#wdc_ad_nav2').width();
    let windowWidth = $(window).width();
    $('main').width(windowWidth-wdc_ad_nav2_width);
    $('#wdc_ad_nav2 ul li > a span').css('display', 'inline');
    let collapseCol = false;
    $('#wdc_collapseActivate').on('click', function (){
        switch (collapseCol) {
            case false:
            $('#wdc_ad_nav2').addClass('wdc_ad_nav2_collapsedMenu');
            $('#wdc_collapseActivate #wdc_collapseActivateIcon > svg').css('transform', 'rotateZ(180deg)');
            $('main').animate({left: '45px'},'fast');
            $('main').width(windowWidth-45);
            collapseCol = true;
            break;
            
            default:
            $('#wdc_ad_nav2').removeClass('wdc_ad_nav2_collapsedMenu');
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
});