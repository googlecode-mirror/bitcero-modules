$(document).ready(function(){
    
    $("#bulk-top").change(function(){
        
        $("#bulk-bottom").val($(this).val());
        
    });
    
    $("#bulk-bottom").change(function(){
        
        $("#bulk-top").val($(this).val());
        
    });
       
    $("#frm-add").validate();
    
});

function dt_check_delete(id, form){
    
    if(id<=0) return false;
    
    $("#"+form+" input[type=checkbox]").removeAttr("checked");
    $("#item-"+id).attr("checked",'checked');
    
    $("#bulk-top").val('delete');
    
    before_submit(form);
    
}

function before_submit(form){
    
    var eles = $("#"+form+" input[name='ids[]']");
    var go = false;

    for(i=0;i<eles.length;i++){
        if ($(eles[i]).is(":checked"))
            go = true;
    }
    
    if (!go){
        alert(dt_select_message);
        return false;
    }
    
    if ($("#bulk-top").val()=='delete'){
        if (confirm(dt_message))
            $("#"+form).submit();
    } else {
        $("#"+form).submit();
    }
}
