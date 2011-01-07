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

});
