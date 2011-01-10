$(document).ready(function(){
	
    if ($("#rmc-left-widgets").height() > $(window).height()-240){
        $("#rmc-center-content").css('min-height', ($("#rmc-left-widgets").height())+'px');
    } else {
	    $("#rmc-center-content").css('min-height', ($(window).height()-240)+'px');
    }
	
    $(".toggle").click(function(){
        id = $(this).attr("id").replace("switch-",'');
        $("#container-"+id).slideToggle('slow');
    });
    
    $("form table td.head").removeAttr('style');
    $("form table td.head").css('width','300px');
    $("table.outer").attr("cellspacing",'0');
    
    $(".msg-close").click(function(){
        $(this).parent(".errorMsg").slideUp();
        $(this).parent(".infoMsg").slideUp();
    });
    
    //setTimeout("hideMessages();", 10000);
});

function hideMessages(){
    $("div.errorMsg").slideUp('slow');
    $("div.infoMsg").slideUp('slow');
}