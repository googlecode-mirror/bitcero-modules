$(document).ready(function(){

    /**
     * This event show a list based on clicked element
     */
    $("#dt-home-tabs li").click(function(){

        if($(this).hasClass("selected")) return;

        var w = $(this).attr("class");                      // Which list will show
        var p = $("#dt-home-tabs li.selected");             // Current selected item
        var r = p.removeClass("selected").attr("class");    // Name of current visible list

        $(this).addClass("selected");                       // Show clicked item

        if($("#"+w+"-items").length>0){

            $("#"+r+"-items").fadeOut('fast');                  // Hide current visible list
            $("#"+w+"-items").fadeIn('fast');                   // Show selected list

        } else {

            $("#dt-item-"+r).fadeOut('fast');                  // Hide current visible list
            $("#dt-item-"+w).fadeIn('fast');

        }

    });
});