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
    
    $(window).resize(function(){
        var nav = $("#des-nav ul");
        var documentWidth = $(document).width()-100;
        if(documentWidth>=navWidth){
            if($("#des-nav").is(":visible")){
                return false;
            }
            else{
                $("#des-nav").show();
                $("#des-vertical").hide();
                $("#right-container").removeClass('des_reduced');
                $("#des-header .header_user").hide('fast');
                $("#des-header .header_user").html('');
                
            }
        } else {
            if($("#des-nav").is(":visible")){
                $("#des-nav").hide();
                $("#des-vertical").show();
                $("#right-container").addClass('des_reduced');
                $("#des-header .header_user").html($("#des-nav ul li:first-child").html());
                $("#des-header .header_user").show('fast');
                $("#des-header .header_user img").attr('src',$("#des-header .header_user img").attr('src').replace("s=50",'s=40'));
            }
            else{
                return false;
            }
        }
        
        if($("#des-vertical").is(":visible"))
            $("#des-vertical").css('min-height',$(document).height()+'px');
        
    });
    
    $('.msg-close').click(function(){
        
        $(this).parent().slideUp('fast');
        
    });
    
    $(window).resize();
    
    $(".rmc_options:not(#table-blocks .rmc_options)").before("<br />");
    
});