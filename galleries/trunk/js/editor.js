// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

var itype = '';
var token = '';

$(document).ready(function(){
    $("#gs-tab-content").height($(document).height()-100);
    $(".tab").hide();
    $(".tab").height($("#gs-tab-content").height()-10);
    $("#tab-gals").show();
    token = $("#XOOPS_TOKEN_REQUEST").val();
    gsController.load_galleries(1,'');
    
    $("#gs-tabs li a").click(function(){
        
        $("#gs-tab-content .tab").hide();
        $("#gs-tabs li").removeClass("selected");
        $(this).parent().addClass("selected");
        
        var id = 'tab-'+$(this).attr("class").replace("insert-",'');
        $("#"+id).show(0);
        
    });
    
    $("a.insert-gals").click(function(){
        
        $("#tab-imgs").html('');
        itype = 'galleries';
        gsController.load_galleries(1,'');
        
    });
    
    $("a.insert-imgs").click(function(){
        
        $("#tab-gals").html('');
        itype = 'images';
        gsController.load_images(1,'');
        
    });
    
});

var gsController = {
    
    load_galleries: function(p,s){
        
        params = {
            XOOPS_TOKEN_REQUEST: token,
            action: 'load_galleries',
            page: p,
            search: s
        };
        
        $("#tab-gals").addClass("gs_loading");
        $("#tab-gals").html('<img src="'+gs_url+'/images/loader.gif" alt="" />');
        
        $.post(gs_url+'/include/ajax-functions.php', params, function(data){
           
           $("#tab-gals").removeClass("gs_loading");
           $("#tab-gals").html(data);
           gsController.register_clicks();
           
        },'html');
        
    },
    
    load_images: function(p,s){
        
        params = {
            XOOPS_TOKEN_REQUEST: token,
            action: 'load_images',
            page: p,
            search: s
        };
        
        $("#tab-imgs").addClass("gs_loading");
        $("#tab-imgs").html('<img src="'+gs_url+'/images/loader.gif" alt="" />');
        
        $.post(gs_url+'/include/ajax-functions.php', params, function(data){
           
           $("#tab-imgs").removeClass("gs_loading");
           $("#tab-imgs").html(data);
           gsController.register_clicks();
           
        },'html');
        
    },
    
    register_clicks: function(){
        
        token = $("#XOOPS_TOKEN_REQUEST").val();
        
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
            
            if($("#search-inp").val()!=''){
               if(itype=='galleries')
                   gsController.load_galleries(id,$("#search-inp").val());
               else
                   gsController.load_images(id,$("#search-inp").val());
            }else{
               if(itype=='galleries')
                   gsController.load_galleries(id,'');
               else
                   gsController.load_images(id,'');
            }
           
            
        });
        
        $("#gs-search-gb").click(function(){
        
            if($("#gs-search-gk").val()=='') return;
            if(itype=='galleries')
                gsController.load_galleries(1,$("#gs-search-gk").val());
            else
                gsController.load_images(1,$("#gs-search-gk").val());

        });
        
        $(".gs_img_item").click(function(){
            gsController.show_image_props($(this));
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
        
    },
    
    show_image_props: function(e){
        
        $("body").append('<div class="blocker"></div>');
        $("body").append('<div id="insert-wdw"></div>');
        
        var image = $(e).children("span.image").html();
        var thumbnail = $(e).children("span.thumbnail").html();
        var user = $(e).children("span.user").html();
        var search = $(e).children("span.search").html();
        var title = $(e).children('img').attr("title");
        var text = $(e).children("span.desc").html();
        
        var html = '<img src="'+image+'" alt="" />';
        html += '<span><input type="radio" name="what" value="'+image+'" /><br />'+lang_image+'</span>';
        if(thumbnail!='') html += '<span><input type="radio" name="what" value="'+thumbnail+'" /><br />'+lang_thumb+'</span>';
        if(user!='') html += '<span><input type="radio" name="what" value="'+user+'" /><br />'+lang_user+'</span>';
        if(search!='') html += '<span><input type="radio" name="what" value="'+search+'" /><br />'+lang_search+'</span>';
        html += '<span><select name="align" id="alignment"><option value="" selected="selected">'+lang_align+'</option>';
        html += '<option value="left">'+lang_left+'</option>';
        html += '<option value="right">'+lang_right+'</option>';
        html += '<option value="center">'+lang_center+'</option></select></span>';
        html += '<div><label><input type="checkbox" name="desc" value="1" '+(gedt=='xoops'?' disabled="disabled"': '')+' /> '+lang_desc+'</label>';
        html += '<input type="button" id="gs-ins-now" value="'+lang_insert+'" /></div>';
        $("#insert-wdw").html(html);
        
        $(".blocker").click(function(){
            
            $(this).hide();
            $("#insert-wdw").hide();
            $(this).remove();
            $("#insert-wdw").remove();
            
        });
        
        $("#gs-ins-now").click(function(){
            
            var ins = '';
            var w = $("#insert-wdw span input:checked");
            if(w==undefined || w.length<=0) return;            
            var desc = $("#insert-wdw div input:checked").length;
            var align = $("#alignment").val();
            
            if(gedt=='tiny'){
                if(desc==1 && text!=''){
                    ins = '<div';
                    if(align!='') ins += ' class="'+align+'"';
                    ins += '>';
                }
                
                ins += '<img src="'+w.val()+'" alt="'+title+'" title="'+title+'"';
                if(align!='' && (desc<=0 || text=='')) ins += ' class="'+align+'" />';
                ins += ' />';
                
                if(desc==1 && text!='') ins += '<br />'+text+'</div>';
                ed = tinyMCEPopup.editor;
                ed.execCommand("mceInsertContent", true, ins);
                tinyMCEPopup.close();
            }else if(gedt=='xoops'){
                
                var insert = '[img'+(align!=''?' align='+align:'')+']'+w.val()+'[/img]';
                
                exmPopup.insertText(insert);
                exmPopup.closePopup();
            }
            
        });
        
    }
    
}