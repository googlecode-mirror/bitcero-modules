var blocksAjax = {

    loadForm: function(id,module){
        
        $("#tr-block-form").remove();
        $("tr.bk_hightlight").removeClass("bk_hightlight");
        
        var params = {
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            module: module,
            block: id,
            action: 'settings'
        };
        
        $.post('ajax/blocks.php', params, function(data){
            
            if(data.error){  
                $("#bk-messages").removeClass("infoMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("errorMsg");
                $("#bk-messages").slideDown('slow');
                if(data.token==null || data.token==''){
                    window.location.reload();
                } else {
                    $("#XOOPS_TOKEN_REQUEST").val(data.token)
                }
                return;
            }
            
            if(data.message!=null && data.message!=''){
                $("#bk-messages").removeClass("errorMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("infoMsg");
                $("#bk-messages").slideDown('slow');
            }
            
            if(data.token==null || data.token==''){
                window.location.reload();
            } else {
                $("#XOOPS_TOKEN_REQUEST").val(data.token);
            }
            
            $("#tr-"+id).after('<tr id="tr-block-form" class="even bk_trform" valign="top" style="display: none;"><td colspan="5">'+data.content+'</td></tr>');
            
            $("#tr-block-form").fadeIn("slow");
            $("#tr-"+id).addClass("bk_hightlight");
            
            blocksAjax.eventChange();
            
        }, 'json');
        
        blocksAjax.scrollId("tr-"+id);
        
    },

    scrollId: function(id){
        
        var pos = $("#"+id).position();
        
        $("html, body").animate({
            scrollTop: pos.top
        }, 2000);
        
    },
    
    eventChange: function(){
        $.getScript("include/js/modules_field.js");
    }
    
}

$(document).ready(function(){

    $("#exspos").click(function(){
        $("#existing-positions").slideToggle('slow', function(){
            if($("#existing-positions").is(":visible")){
                $("#exspos span").html("&#916;");
                $(this).effect('highlight',{}, 1000);
            } else {
                $("#exspos span").html("&#8711");
            }
        });        
    });
    
    $("#add-pos-menu").click(function(){
        $("#exspos").click();
    });

    jkmegamenu.definemenu("newban", "megamenu1", "click")
    
    $("#newpos").click(function(){
        $("#form-pos").toggle('slow', function(){
            if($(this).is(":visible")){
                $(this).effect('highlight', {}, 1000);
            }
        });
    });
    
    $("#megamenu1 li a").click(function(){
        var block = $(this).attr("id").replace("block-",'');
        
        var block = block.split("-");
        
        mod = block[0];
        id = block[1];
        
        var params = {
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            module: mod,
            block: id,
            action: 'insert'
        };
        
        $("#wait-buttons").fadeIn('slow');
        
        $.post('ajax/blocks.php', params, function(data){
            
            $("#wait-buttons").fadeOut('slow');
            
            if(data.error){  
                $("#bk-messages").removeClass("infoMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("errorMsg");
                $("#bk-messages").slideDown('slow');
                if(data.token==null || data.token==''){
                    window.location.reload();
                } else {
                    $("#XOOPS_TOKEN_REQUEST").val(data.token)
                }
                return;
            }
            
            if(data.message!=null && data.message!=''){
                $("#bk-messages").removeClass("errorMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("infoMsg");
                $("#bk-messages").slideDown('slow');
            }
            
            if(data.token==null || data.token==''){
                window.location.reload();
            } else {
                $("#XOOPS_TOKEN_REQUEST").val(data.token);
            }
            
            if($("#tr-empty").length>0){
                $("#tr-empty").slideUp('slow', function(){
                    $("#tr-empty").remove();
                });
            }
            
            var tr = '<tr valign="top" class="even" id="tr-'+data.id+'" style="dislay: none;">';
            tr += '<td align="center"><input type="checkbox" name="ids[]" id="item-'+data.id+'" /></td>';
            tr += '<td><strong>'+data.name+'</strong>';
            tr += '<span class="description">'+data.description+'</span>';
            tr += '<td align="center">'+data.canvas.name+'</td>';
            tr += '<td align="center">'+data.weight+'</td>';
            tr += '</td><td align="center">'+data.module+'</td>';
            $("#table-blocks").append(tr);
            
            $("#tr-"+data.id).slideDown('slow', function(){
                $("#tr-"+data.id).effect('highlight',{}, 1000);
                blocksAjax.loadForm(data.id,data.module);
            });
            
        }, 'json');
        
    });
    
    $("a.bk_edit").click(function(){
        var id = $(this).attr("id").replace("edit-",'');
        
        blocksAjax.loadForm(id,'');
        
    });

});