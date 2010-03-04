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