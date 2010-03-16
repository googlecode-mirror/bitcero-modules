$(document).ready(function(){
    
    $('#the-op-top').click(function(){
        $("#frm-categos").submit();
    });
    
    $('#the-op-bottom').click(function(){
        $("#frm-categos").submit();
    });
    
    $("#bulk-top").change(function(){
        $("#bulk-bottom").val($(this).val());
    });
    
    $("#bulk-bottom").change(function(){
        $("#bulk-top").val($(this).val());
    });
    
});

function before_submit(id){
	
	var types = $("#"+id+" input[type=checkbox]");
	var go = false;
	
	for(i=0;i<types.length;i++){
		if ($(types[0]).is(":checked"))
			go = true;
	}
	
	if (!go){
		alert(pw_select_message);
		return;
	}
	
	if ($("#bulk-top").val()=='delete'){
		if (confirm(pw_message))
			$("#"+id).submit();
	} else {
		$("#"+id).submit();
	}
	
}

function select_option(id,action){
	
	if(action=='edit'){
		$("#bulk-top").val('edit');
		$("#bulk-bottom").val('edit');
		$("#frm-types input[type=checkbox]").removeAttr("checked");
		$("#item-"+id).attr("checked","checked");
		$("#frm-types").submit();
	}else if(action=='delete'){
		$("#bulk-top").val('delete');
		$("#bulk-bottom").val('delete');
		$("#frm-types input[type=checkbox]").removeAttr("checked");
		$("#item-"+id).attr("checked","checked");
		if (confirm(pw_message))
			$("#frm-types").submit();
	}
	
}