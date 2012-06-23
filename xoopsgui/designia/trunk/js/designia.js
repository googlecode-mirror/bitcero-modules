$(document).ready(function(){
    
    var navWidth = 0;
    $("#des-nav ul li").each(function(){
        navWidth += ($(this).width()+10);
    });
    
    $("#des-content").css('height',$(window).height()-300+'px');
    /*params = {
        'XOOPS_TOKEN_REQUEST': $("#xtoken").html()
    };
    
    $.get(designia_url+'/ajax/modules.php', params, function(data){
        $("#des-nav-modules").html(data);
    },'html');*/
    
    $(".des-modules-nav").click(function(){
        $("#des-nav-modules").slideToggle('fast', function(){
            if($("#des-nav-modules").is(":visible"))
                $(".des-modules-nav").addClass('select');
            else
                $(".des-modules-nav").removeClass('select');
        });
        $(window).resize();
        return false;
    });
    
    $("#designia-about").click(function(){
        
        $('body').append("<div id='designia-blocker'></div>");
        $("#designia-blocker").fadeIn('fast');
        
        $('body').append('<div id="des-about-content"></div>');
        
        $("#designia-blocker").click(function(){
            $("#des-about-content").fadeOut('fast');
            $(this).fadeOut('fast', function(){
                $("#designia-blocker").remove();
                $("#des-about-content").remove();
            });
        });
        
        $.get(designia_url+'/ajax/about.php', {}, function(data){
            
            $("#des-about-content").html(data);
            $("#des-about-content").fadeIn('fast');
            
        }, 'html');
        
    });
    
    $(window).resize(function(){
        var nav = $("#des-nav ul");
        var documentWidth = $(document).width()-100;
        if(documentWidth>=navWidth){
            if(!$("#des-nav").is(":visible")){
                $("#des-nav").show();
                $("#des-vertical").hide();
                $("#right-container").removeClass('des_reduced');
                $("#des-header .header_user").hide('fast');
                $("#des-header .header_user").html('');
                $("#des-footer").css("margin", '20px 50px');
                
            }
        } else {
            if($("#des-nav").is(":visible")){
                $("#des-nav").hide();
                $("#des-vertical").show();
                $("#right-container").addClass('des_reduced');
                $("#des-header .header_user").html($("#des-nav ul li:first-child").html());
                $("#des-header .header_user").show('fast');
                $("#des-header .header_user img").attr('src',$("#des-header .header_user img").attr('src').replace("s=50",'s=40'));
                $("#des-footer").css("margin", '20px 10px 20px '+($("#des-vertical").width()+20)+'px');
            }
        }
        
        if($("#des-vertical").is(":visible"))
            $("#des-vertical").css('min-height',($(document).height()-10)+'px');
        
    });
    
    $('.msg-close').click(function(){
        
        $(this).parent().slideUp('fast');
        
    });
    
    $(window).resize();
    
    $(".rmc_options:not(#table-blocks .rmc_options)").before('<br class="littlejump" />');
    
    $("a.rm_help_button").click(function(){
        
        $.window({
            title: $(this).attr('title'),
            headerClass: "th bg_dark",
            url: $(this).attr('href'),
            width: 500,
            minWidth: 500,
            height: 600,
            resizable: true,
            maximizable: false,
            minimizable: false,
            y: 10,
            x: $(window).width()-510
        });
        return false;
    });
    
});