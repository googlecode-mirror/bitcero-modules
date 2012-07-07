$(document).ready(function(){
    
    $("#bulk-top").change(function(){
        
        $("#bulk-bottom").val($(this).val());
        
    });
    
    $("#bulk-bottom").change(function(){
        
        $("#bulk-top").val($(this).val());
        
    });

    if($("#frm-add").length>0)
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

    if($("#bulk-top").val()=='') return false;

    var eles = $("#"+form+" input[name='ids[]']");
    var go = false;

    for(i=0;i<eles.length;i++){
        if ($(eles[i]).is(":checked"))
            go = true;
    }
    
    if (!go){
        alert(jsLang.noSelectMsg);
        return false;
    }
    
    if ($("#bulk-top").val()=='delete'){
        if (confirm(jsLang.confirmDeletion))
            $("#"+form).submit();
    } else {
        $("#"+form).submit();
    }
}

function block_screen(block,bg){

    if(block==1){
        $('body').append("<div id='items-blocker'></div>");
        $("#items-blocker").fadeIn('fast');
    } else {
        $("#items-blocker").slideUp('fast', function(){
            $("#status-bar").slideUp('fast', function(){
                $("#status-bar").css('background', bg);
                $("#items-blocker").remove();
            });
        });
    }

}


function dt_show_error(data){
    $("#status-bar").html(data.message+' &nbsp; <input type="button" id="cancel-changes" onclick="block_screen(0); $(this).parent().slideUp();" value="'+jsLang.cancel+'" />');
    $("#status-bar").css('background','#991006');

    if(data.token=='')
        window.location.reload();

    $("#XOOPS_TOKEN_REQUEST").val(data.token);
}
