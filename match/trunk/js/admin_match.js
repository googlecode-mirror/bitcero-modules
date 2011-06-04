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