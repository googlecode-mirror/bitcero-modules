$(document).ready(function(){
    
    $("#bulk-top").change(function(){
        
        $("#bulk-bottom").val($(this).val());
        
    });
    
    $("#bulk-bottom").change(function(){
        
        $("#bulk-top").val($(this).val());
        
    });
    
    $("#the-op-top").click(function(){
        $("#frm-messages").submit();
    });
    
    $(".delete").click(function(){
        
        var id = $(this).attr("id").replace("delete-", '');
        if(id<=0) return;
    
        $("#frm-messages input[type=checkbox]").removeAttr("checked");
        $("#item-"+id).attr("checked",'checked');
    
        $("#bulk-top").val('delete');
    
        before_submit('frm-messages');
    });
    
    if($("#frm-reply").length>0){
        $("#message").addClass("required");
        $("#frm-reply").validate();
    }
    
});

function before_submit(form){
    
    var eles = $("#"+form+" input[name='ids[]']");
    var go = false;

    for(i=0;i<eles.length;i++){
        if ($(eles[i]).is(":checked"))
            go = true;
    }
    
    if (!go){
        alert(cm_select_message);
        return false;
    }
    
    if ($("#bulk-top").val()=='delete'){
        if (confirm(cm_message))
            $("#"+form).submit();
    }
}