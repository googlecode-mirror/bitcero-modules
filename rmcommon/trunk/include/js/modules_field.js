/**
* $Id$
*/
$(document).ready(function(){
    
    $(".subpages_container .sp_title span").click(function(){
        id=$(this).attr("id").replace("close-",'');
        $("#subpages-"+id).hide();
    });
    
    $(".field_module_names").hover(function(){
        id = $(this).attr("id").replace("modlabel-","");
        if($("#subpages-"+id).is(":visible")) return;
        $(".subpages_container:visible").hide();
        $("#subpages-"+id).show();
    });
    
    $(".field_module_names input[type='checkbox']").change(function(){
        
        id = $(this).val();
        
        $("#subpages-"+id+" input").attr("checked", $(this).attr("checked"));
        
    });
    
    var mods = $(".modules_field .field_module_names");
    var w = 0;

    for(i=0;i<mods.length;i++){
        
        if(w < $(mods[i]).width())
            w = $(mods[i]).width();
    }
    
    $(".modules_field .mod_item").css("width", w+"px");
    
    
});