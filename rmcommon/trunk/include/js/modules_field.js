/**
* $Id$
*/
$(document).ready(function(){
    $("label.field_module_names input[type='checkbox']").change(function(){
        id = $(this).attr("id").split("-");
        
        id = id[1];
        if(id<=0) return;
        
        $("#subpages-"+id).slideDown();
    });
});
