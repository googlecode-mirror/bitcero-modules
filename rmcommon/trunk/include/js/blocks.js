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
        
        $("#blocker").slideDown('fast', function(){
            $("#loading").fadeIn("slow");
        });
        
        $.post('ajax/blocks.php', params, function(data){
            
            $("#loading").fadeOut("fast");
            
            if(data.error){  
                $("#bk-messages").removeClass("infoMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("errorMsg");
                $("#bk-messages").slideDown('slow');
                if(data.token==null || data.token==''){
                    window.location.href = 'blocks.php';
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
            
            //$("#tr-"+id).after('<tr id="tr-block-form" class="even bk_trform" valign="top" style="display: none;"><td colspan="5">'+data.content+'</td></tr>');
            
            $("#form-window").html(data.content);
            $("#tr-"+id).addClass("bk_hightlight");
            $("#form-window").css("margin-top", "-"+$("#form-window").height()/2+"px");
            $("#form-window").show("fast", function(){
            });
            
            blocksAjax.eventChange();
            
            blocksAjax.prepareTabs();
            
        }, 'json');
        
        //blocksAjax.scrollId("tr-"+id);
        
    },

    scrollId: function(id){
        
        var pos = $("#"+id).position();
        
        $("html, body").animate({
            scrollTop: pos.top
        }, 2000);
        
    },
    
    eventChange: function(){

        //$.getScript("include/js/modules_field.js");
        $.ajax({
            url: "include/js/modules_field.js",
            dataType: 'script',
            cache: false
        });
    },
    
    close: function(){
        $("#form-window").fadeOut("fast", function(){
            $("#blocker").slideUp('fast');
            $("#table-blocks .bk_hightlight").removeClass("bk_hightlight");
        });
    },
    
    prepareTabs: function(){
        
        $(".bk_tab_titles span").click(function(){
            
            var id = $(this).attr("id").replace("tab-",'');
            if(id=='custom'){
                $("#general-content").slideUp('slow');
                $("#block-permissions").slideUp('slow');
                $("#custom-content").slideDown('slow');
                $("#tab-custom").addClass("selected");
                $("#tab-general").removeClass("selected");
                $("#tab-permissions").removeClass("selected");
            } else if(id=='general') {
                $("#custom-content").slideUp('slow');
                $("#general-content").slideDown('slow');
                $("#tab-general").addClass("selected");
                $("#tab-custom").removeClass("selected");
                $("#block-permissions").slideUp('slow');
                $("#tab-permissions").removeClass("selected");
            } else {
                $("#custom-content").slideUp('slow');
                $("#general-content").slideUp('slow');
                $("#tab-custom").removeClass("selected");
                $("#tab-general").removeClass("selected");
                $("#block-permissions").slideDown('slow');
                $("#tab-permissions").addClass("selected");
            }
            
        });
        
    },
    
    sendConfig: function(){
        
        var vars = $("#frm-block-config").serialize();
        blocksAjax.close();
        
        $("#blocker").slideDown('fast', function(){
            $("#loading").fadeIn("slow");
        });
        
        $.post("ajax/blocks.php", vars, function(data){
            $("#loading").fadeOut("fast");
            
            if(data.error){  
                $("#bk-messages").removeClass("infoMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("errorMsg");
                $("#bk-messages").slideDown('slow');
                if(data.token==null || data.token==''){
                    window.location.href = 'blocks.php';
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
            
        }, 'json');
        
    }
    
}

$(document).ready(function(){
    
    $("#add-pos-menu").click(function(){
        $("#exspos").click();
    });

    jkmegamenu.definemenu("newban", "megamenu1", "click")
    
    $("#newpos").click(function(){
        $("#form-pos").toggle('slow', function(){
            if($(this).is(":visible")){
                $("#form-pos").effect('highlight', {}, 1000);
                $("#newpos").html(lang_blocks);
            } else {
                $("#newpos").html(lang_positions);
            }
        });
        $("#blocks-positions").slideToggle(500);
        $("#blocks-list").slideToggle(500);
        $("#blocks-modpos").fadeToggle(500);
        return false;
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
        
        // Show blocks if not visible
        if(!$("#blocks-list").is(":visible")){
            $("#newpos").click();
        }
        
        $.post('ajax/blocks.php', params, function(data){
            
            $("#wait-buttons").fadeOut('slow');
            
            if(data.error){  
                $("#bk-messages").removeClass("infoMsg");
                $("#bk-messages .msg").html(data.message);
                $("#bk-messages").addClass("errorMsg");
                $("#bk-messages").slideDown('slow');
                if(data.token==null || data.token==''){
                    window.location.href = 'blocks.php';
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
                window.location.href = 'blocks.php';
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
            tr += '</td><td align="center">'+data.module+'</td>';
            tr += '<span class="description">'+data.description+'</span>';
            tr += '<span class="rmc_options">';
            tr += '<a class="bk_edit" href="#" id="edit-'+data.id+'">Settings</a> |';
            tr += '<a href="#" onclick="select_option('+data.id+',\'delete\',\'frm-blocks\');">Delete</a> |';
            tr += '<a href="#" class="bk_disable" id="disable-'+data.id+'">Disable</a></span></td>';
            tr += '<td align="center">'+data.canvas.name+'</td>';
            tr += '<td align="center"><img src="images/done.png" alt="" /></td>';
            tr += '<td align="center">'+data.weight+'</td>';
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
        return false;
    });
    
    $("#bulk-top").change(function(){
        $("#bulk-bottom").val($(this).val());
    });

    $("#bulk-bottom").change(function(){
        $("#bulk-top").val($(this).val());
    });
    
    $("#bulk-topp").change(function(){
        $("#bulk-bottomp").val($(this).val());
    });

    $("#bulk-bottomp").change(function(){
        $("#bulk-topp").val($(this).val());
    });
    
    $("a.edit_position").click(function(){
        
        var id = $(this).parent().parent().parent();
        var data = $("#"+$(id).attr("id")+" .pos_data");
        
        if($("#editor-row").length>0){
            $("#editor-row").hide();
            $("#editor-row").remove();
            $("tr").show();
        }
        
        var html = '<tr id="editor-row" style="display:none;" class="'+$(id).attr("class")+' editor" valign="top"><td>&nbsp;</td>';
        html += '<td><strong class="the_id">'+$(id).attr("id").replace("ptr-",'')+'</strong></td>';
        html += '<td><input style="width: 90%" type="text" name="name" id="ed-name" value="'+$(data).children('.name').html()+'" /><br />';
        html += '<input type="submit" value="'+lang_save+'" class="save_button" />';
        html += '<input type="button" value="'+lang_cancel+'" class="cancel_button" />';
        html += '</td>';
        html += '<td align="center"><input type="text" name="tag" id="ed-tag" value="'+$(data).children('.ptag').html()+'" /></td>';
        html += '<td align="center"><input type="checkbox" name="active" id="ed-active" value="1" '+($(data).children('.active').html()=='1'?' checked="checked"' : '')+'" /></td>';
        html += '</tr>';
        $(id).after(html);
        $(id).hide();
        $("#editor-row").show();
        
        $("#editor-row .cancel_button").click(function(){
            
            $("#editor-row").hide();
            $("#editor-row").remove();
            $("tr").show();
            
        });
        
        $("#editor-row .save_button").click(function(){
            
            var params = {
                'action': 'savepos',
                'id': $("#editor-row .the_id").html(),
                'name': $("#editor-row #ed-name").val(),
                'tag': $("#editor-row #ed-tag").val(),
                'active': $("#editor-row #ed-active").is(":checked") ? '1' : 0,
                'XOOPS_TOKEN_REQUEST': $("#XOOPS_TOKEN_REQUEST").val()
            };
            
            $.post('ajax/blocks.php', params, function(data){
                
                if(data.error){  
                    $("#bk-messages").removeClass("infoMsg");
                    $("#bk-messages .msg").html(data.message);
                    $("#bk-messages").addClass("errorMsg");
                    $("#bk-messages").slideDown('slow');
                    if(data.token==null || data.token==''){
                        window.location.href = 'blocks.php?from=positions';
                    } else {
                        $("#XOOPS_TOKEN_REQUEST").val(data.token)
                    }
                    return false;
                }
                
                if(data.message!=null && data.message!=''){
                    $("#bk-messages").removeClass("errorMsg");
                    $("#bk-messages .msg").html(data.message);
                    $("#bk-messages").addClass("infoMsg");
                    $("#bk-messages").slideDown('slow');
                }

                if(data.token==null || data.token==''){
                    window.location.href = 'blocks.php?from=positions';
                } else {
                    $("#XOOPS_TOKEN_REQUEST").val(data.token);
                }

                //$("#tr-"+id).after('<tr id="tr-block-form" class="even bk_trform" valign="top" style="display: none;"><td colspan="5">'+data.content+'</td></tr>');
                
                id = '#ptr-'+$("#editor-row .the_id").html();
                $(id+' .name').html($("#editor-row #ed-name").val());
                $(id+' .ptag').html($("#editor-row #ed-tag").val());
                if($("#editor-row #ed-active").is(":checked")){
                    $(id+" img.active").attr('src', 'images/done.png');
                    $(id+' .pos_data .active').html(1);
                } else {
                    $(id+" img.active").attr('src', 'images/closeb.png');
                    $(id+' .pos_data .active').html(0);
                }
                
                $("#editor-row").hide();
                $("#editor-row").remove();
                id = id.replace("#ptr-",'');
                $('#ptr-'+id).show();
                $('#ptr-'+id).effect('highlight',{}, 1000);
                
                return false;
            
            },'json');
            
            return false;
            
        });
        
    })

});

function before_submit(id){

	var types = $("#"+id+" input[name='ids[]']");
	var go = false;
        
        if(id=='frm-positions'){
            bt = '#bulk-topp';
            var xt = $("#"+id+" input[name='XOOPS_TOKEN_REQUEST']");
            if(xt.length>0){
                $(xt).val($("#XOOPS_TOKEN_REQUEST").val());
            } else {
                $("#"+id).append('<input type="hidden" name="XOOPS_TOKEN_REQUEST" value="'+$("#XOOPS_TOKEN_REQUEST").val()+'" />');
            }
        }else{
            bt = '#bulk-top';
        }

	for(i=0;i<types.length;i++){
		if ($(types[i]).is(":checked"))
			go = true;
	}

	if (!go){
		alert(bks_select_message);
		return false;
	}

	if ($(bt).val()=='delete'){
		if (confirm(bks_message))
			$("#"+id).submit();
	} else {
		$("#"+id).submit();
	}

}

function select_option(id,action,form){
    
    if(form=='frm-positions')
        p = 'p';
    else
        p = '';

	if(action=='edit'){
		$("#bulk-top"+p).val('edit');
		$("#bulk-bottom"+p).val('edit');
		$("#"+form+" input[type=checkbox]").removeAttr("checked");
		$("#item-"+id).attr("checked","checked");
		$("#"+form).submit();
	}else if(action=='delete'){
		$("#bulk-top"+p).val('delete'+(p=='p'?'pos':''));
		$("#bulk-bottom"+p).val('delete'+(p=='p'?'pos':''));
		$("#"+form+" input[type=checkbox]").removeAttr("checked");
		$("#item"+p+"-"+id).attr("checked","checked");
		before_submit(form);
	}

}