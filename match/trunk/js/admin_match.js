$(document).ready(function(){
    
    $("#bulk-top").change(function(){
        $("#bulk-bottom").val($(this).val());
    });
    
    $("#bulk-bottom").change(function(){
        $("#bulk-top").val($(this).val());
    });
    
    $(".teams-category").change(function(){
        window.location.href = 'teams.php?category='+$(this).val()+'&page='+$("#team-page").val();
    });
    
    $(".mch_filters").click(function(){
        $(".players_filters").slideToggle('slow');
        return false;
    });
    
    // Role play
    $("#champ").change(function(){
        
        if($(this).val()<=0){
            $(".mch_bc_role").html('&raquo; <span class="msg">Select Championship</span>');
            return;    
        }
        
        if($("#category").val()<=0){
            $(".mch_bc_role").html('&raquo; <span class="msg">Select Category</span>');
            return;
        }
        
        $("#frm-filter").submit();
        
    });
    
    $("#category").change(function(){
        
        if($(this).val()<=0){
            $(".mch_bc_role").html('&raquo; <span class="msg">Select Category</span>');
            return;
        }
        
        if($("#champ").val()<=0) return;
        
        $("#frm-filter").submit();
        
    });
    
    $("#team").change(function(){
              
        if($("#champ").val()<=0 || $("#category").val()<=0) return;
        
        $("#frm-filter").submit();
        
    });
    
    $("#sday").change(function(){
              
        if($("#champ").val()<=0 || $("#category").val()<=0) return;
        
        $("#frm-filter").submit();
        
    });
    
    $("#local-team").change(function(){
        
        $("#visitor-team option").removeAttr("disabled");
        $("#visitor-team option").removeClass("disabled");
        
        if($(this).val()==0){
            $("#local-data").html('');
            return;
        }
        
        $("#visitor-team option[value='"+$(this).val()+"']").attr("disabled",'disabled');
        $("#visitor-team option[value='"+$(this).val()+"']").addClass("disabled");
        
    });
    
    $("#visitor-team").change(function(){
        
        if($(this).val()==0){
            $("#visitor-data").html('');
            return;
        }
        
    });
    
    $("a.set_score").click(function(){
        
        var id = $(this).attr("id").replace('score-','');
        
        params = {
            action: 'get-score',
            id: id
        };
        
        $(".score_editor").remove();
        $("td.empty").removeClass('empty');
        
        $.get('../include/utils.php', params, function(data){
            
            $("#role-"+id).after(data);
            $("#score-editor-"+id+" input[name='local']").focus();
            
        },'html');
        
        return false;
    })
    
});

function before_submit(id){

	var types = $("#"+id+" input[name='ids[]']");
	var go = false;

	for(i=0;i<types.length;i++){
		if ($(types[i]).is(":checked"))
			go = true;
	}
	
	if (!go){
		alert(mch_select_message);
		return false;
	}
	
	if ($("#bulk-top").val()=='delete'){
		if (confirm(mch_message))
			$("#"+id).submit();
	} else {
		$("#"+id).submit();
	}
	
}

function select_option(id,action,form){
	
    form = form==undefined || form==''?'frm-types':form;
    
	if(action=='edit'){
		$("#bulk-top").val('edit');
		$("#bulk-bottom").val('edit');
		$("#"+form+" input[type=checkbox]").removeAttr("checked");
		$("#item-"+id).attr("checked","checked");
		$("#"+form).submit();
	}else if(action=='delete'){
		$("#bulk-top").val('delete');
		$("#bulk-bottom").val('delete');
		$("#"+form+" input[type=checkbox]").removeAttr("checked");
		$("#item-"+id).attr("checked","checked");
		if (confirm(mch_message))
			$("#"+form).submit();
	}
	
}

function set_score(id){
    
    $("#score-editor-"+id+" td.empty").html('<img src="../images/loadb.gif" alt="" />');
    
    var local, visitor, other, comments, token, win;
    
    $("#score-editor-"+id+" input").each(function(i){
        switch($(this).attr("name")){
            case 'local':
                if($(this).val()=='' || isNaN($(this).val()) || $(this).val()<0){
                    $(this).css({background:'#f00',color:'#FFF'});
                    $(this).focus();
                    return;
                } else {
                    $(this).css({background:'',color:''});
                }
                local = $(this).val();
                break;
            case 'visitor':
                if($(this).val()=='' || isNaN($(this).val()) || $(this).val()<0){
                    $(this).css({background:'#f00',color:'#FFF'});
                    return;
                } else {
                    $(this).css({background:'',color:''});
                }
                visitor = $(this).val();
                break;
            case 'other':
                if($(this).is(":checked"))
                    other = $(this).val();
                break;
            case 'comments':
                comments = $(this).val();
                break;
            case 'token':
                token = $(this).val();
                break;
            case 'win':
                if($(this).is(":checked"))
                    win = $(this).val();
                break;
        }
    });
    
    var params = {
        id: id,
        local: local,
        visitor: visitor,
        other: other,
        comments: comments,
        token: token,
        action: 'set-score',
        win: win
    };
    
    $.post('../include/utils.php', params, function(data){
        
        if(data.error){
            alert(data.msg);
        }
        
        $(".score_editor").fadeOut('fast', function(){
            $(".score_editor").remove();
        });
        
    },'json');
    
    
}

function hide_score(){
    $(".score_editor").fadeOut('fast', function(){
            $(".score_editor").remove();
        });
}