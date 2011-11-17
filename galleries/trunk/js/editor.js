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
    
    gsController.load_galleries(1);
    
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
           gsController.register_clicks();
           
        },'html');
        
    },
    
    register_clicks: function(){
        $("div.gs_item").click(function(){
            if( $(this).children(".gtitle").children(".goptions").html()!='') return;
            $("div.gs_item").removeClass("clicked");
            $("div.gs_item .goptions").html("");
            $(this).addClass('clicked');
            $(this).children(".gtitle").children(".goptions").html('<input type="hidden" name="idgal" id="idgal" value="'+$(this).children(".gid").html()+'" />'+$("#ggoptions").html());
            $(this).children(".gtitle").children(".goptions").css('display','block');
            
            $(".g-insert").click(function(){
                gsController.insert_gallery($(this).parent());
            });
        });
        
        $(".rmc_pages_navigation_container a").click(function(){
            
            var id = 0;
            
            if ($(this).attr("id")==undefined){
                id = $(this).attr("class").replace("page-",'');
            } else {
                id = $(this).attr("id").replace("page-",'');
            }
            
            gsController.load_galleries(id);
            
        });
        
    },
    
    insert_gallery: function(e){
        
        var gal = e.children("#idgal").val();
        var num = e.children(".g-xpage").val();
        var full = e.children(".g-spage").is(":checked");
        var url = e.children(".g-surl").is(":checked");
        
        var insert = "[gallery id="+gal+" num="+num+" full="+full+" url="+url+"]";
        
        if(gedt=='tiny'){
            ed = tinyMCEPopup.editor;
            ed.execCommand("mceInsertContent", true, insert);
            tinyMCEPopup.close();
        }else if(gedt=='xoops'){
            exmPopup.insertText(insert);
            exmPopup.closePopup();
        }
        
    }
    
}