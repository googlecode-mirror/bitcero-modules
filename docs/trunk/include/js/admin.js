/**
* $Id$
*/

$(document).ready(function(){
    
    $("#bulk-top").change(function(){
        
        $("#bulk-bottom").val($(this).val());
        
    });
    
    $("#bulk-bottom").change(function(){
        
        $("#bulk-top").val($(this).val());
        
    });
    
    $("#the-op-top").click(function(){
        $("#frm-resources").submit();
    });
    
});