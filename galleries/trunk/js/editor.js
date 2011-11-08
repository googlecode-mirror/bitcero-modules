// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
    $("#gs-tab-content").height($(document).height()-100);
    $(".tab").hide();
    $(".tab").height($("#gs-tab-content").height()-10);
    $("#tab-gals").show();
    
    $("#gs-tabs li a").click(function(){
        
        $("#gs-tab-content .tab").hide();
        $("#gs-tabs li").removeClass("selected");
        $(this).parent().addClass("selected");
        
        var id = 'tab-'+$(this).attr("class").replace("insert-",'');
        $("#"+id).show(0);
        
    });
    
    $("a.insert-gals").click(function(){
        
        gsController.load_galleries(1);
        
    });
    
});

var gsController = {
    
    load_galleries: function(p){
        $("#tab-gals").addClass("gs_loading");
        $("#tab-gals").html('<img src="'+gs_url+'/images/loader.gif" alt="" />');
        
        params = {
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            action: 'load_galleries',
            page: p
        };
        
        $.post(gs_url+'/include/ajax-functions.php', params, function(data){
           
           $("#tab-gals").removeClass("gs_loading");
           $("#tab-gals").html(data);
           
        },'html');
        
    },
    
    load_pictures: function(p){
        
    }
    
}