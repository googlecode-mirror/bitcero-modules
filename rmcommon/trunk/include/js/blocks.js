$(document).ready(function(){

    $("#exspos").click(function(){
        $("#existing-positions").slideToggle('slow', function(){
            if($("#existing-positions").is(":visible")){
                $("#exspos span").html("&#916;");
                $(this).effect('highlight',{}, 1000);
            } else {
                $("#exspos span").html("&#8711");
            }
        });        
    });
    
    $("#add-pos-menu").click(function(){
        $("#exspos").click();
    });

    jkmegamenu.definemenu("newban", "megamenu1", "click")
    
    $("#newpos").click(function(){
        $("#form-pos").toggle('slow', function(){
            if($(this).is(":visible")){
                $(this).effect('highlight', {}, 1000);
            }
        });
    });
    
    $("#megamenu1 li a").click(function(){
        var block = $(this).attr("id").replace("block-",'');
        
        var block = block.split("-");
        
        mod = block[0];
        id = block[1];
        
        var params = {
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            module: mod,
            block: id
        };
        
        $.post('ajax/blocks.php', params, function(data){
            if(data.error){
                alert('Error');
            }
        }, 'json');
        
    });

});
