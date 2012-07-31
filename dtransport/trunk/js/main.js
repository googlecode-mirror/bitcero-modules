var toi = 0, direction = 'left';

$(document).ready(function(){

    if($(".dt_scroller").lengt>0)
        toi = setTimeout('dt_scroll()',500);

    $(".dt_scroller .container img").hover(function(){

        clearTimeout(toi);

    });

    $(".dt_scroller img.control").hover(function(){

        $(this).css('opacity', '0.7');

    });

    $(".dt_scroller img.control").mouseout(function(){

        $(this).css('opacity', '0.2');

    });

    $(".dt_scroller .dt_backward").click(function(){clearTimeout(toi);direction='left';dt_scroll();});
    $(".dt_scroller .dt_forward").click(function(){clearTimeout(toi);direction='right';dt_scroll();});

    if($("#will-start").length>0)
        toi = setTimeout('delay_download()', 1000);

});

function delay_download(){

    var ele = $("#will-start .message");

    var msg = down_message.replace("{x}", '<strong>'+timeCounter+'</strong>');
    ele.html(msg);

    if(timeCounter<=0){
        window.location.href = dlink;
        return;
    }

    timeCounter--;

    toi = setTimeout('delay_download()', 1000);

}

function dt_scroll(){

    var ele = $("#dt-screens-row div.dt_scroller div.container div");
    var pos = ele.position();
    var cw = $("#dt-screens-row .dt_scroller div.container").width(); // Scroller width
    var imgw = $("#dt-screens-row div.dt_scroller div img:first-child").width() + 6;

    if(ele.width() < cw){
        var left = (cw - ele.width());
        left = left / 2;
        ele.animate({
            left: left+'px',
            speed: 500
        });
        return;
    }

    if((ele.width()-(pos.left*-1))<cw && direction=='left'){
        direction = 'right';
    }else if(pos.left>=0 && direction=='right'){
        direction = 'left';
    }

    ele.animate({
        left: direction=='left'?pos.left-imgw+'px':pos.left+imgw+'px',
        speed: 800
    });

    toi = setTimeout('dt_scroll()', 1000);

}