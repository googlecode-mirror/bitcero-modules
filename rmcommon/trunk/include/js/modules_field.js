/**
* $Id$
*/
$(document).ready(function(){
    
    $(".subpages_container .sp_title span").click(function(){
        id=$(this).attr("id").replace("close-",'');
        $("#subpages-"+id).hide();
    });
    
    $(".field_module_names").click(function(){
        id = $(this).attr("id").replace("modlabel-","");
        if($("#subpages-"+id).is(":visible")) return;
        $(".subpages_container:visible").hide();
        $("#subpages-"+id).show();
    });
    
    $(".field_module_names input[type='checkbox']").change(function(){
        
        var id = $(this).val();
        if ($(this).is(":checked")){
            $("#subpages-"+id+" input").attr("checked",  'checked');
        } else {
            $("#subpages-"+id+" input").removeAttr("checked");
        }
        
    });
    
    var mods = $(".modules_field .field_module_names");
    var w = 0;

    for(i=0;i<mods.length;i++){
        
        if(w < $(mods[i]).width())
            w = $(mods[i]).width();
    }
    
    $(".modules_field .mod_item").css("width", w+"px");
    
    
});
