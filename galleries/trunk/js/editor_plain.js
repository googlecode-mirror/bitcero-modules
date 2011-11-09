// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function insert_galleries(){
    
    $("body").append('<div class="gs_blocker"></div>');
    $("body").append('<div class="gs_window">Hola</div>');
    
    $.post(gs_url+'/include/ajax-functions.php', {action: 'load_structure'}, function(data){
        
        alert(data);
        $(".gs_window").html(data);
        load_galleries(1);
        
    },'html');
    
    $("body .gs_blocker").click(function(){
        $(this).remove();
        $("body .gs_window").remove();
    });
    
}
function load_galleries(p){
    
    params = {
        XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
        action: 'load_galleries',
        page: p
    };
        
    $("#tab-gals").addClass("gs_loading");
    $("#tab-gals").html('<img src="'+gs_url+'/images/loader.gif" alt="" />');
        
    $.post(gs_url+'/include/ajax-functions.php', params, function(data){
           
        $("#tab-gals").removeClass("gs_loading");
        $("#tab-gals").html(data);
        //register_clicks();
           
    },'html');
    
}