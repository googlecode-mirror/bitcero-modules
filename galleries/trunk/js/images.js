/*
$Id$
--------------------------------------------------------------
Matches
Module to publish and manage sports matches
Author: Eduardo Cortés <i.bitcero@gmail.com>
Email: i.bitcero@gmail.com
License: GPL 2.0
--------------------------------------------------------------
*/

var total = 0;
var ids = new Array();
var url = '';
var current = 0;

function send_resize(id,params){

    $.get(url, {token: params, img: id, action: 'resize'}, function(data){
        if (data['error']){
            $("#resizer-bar span.message").html('<span>'+data['message']+'</span>');
            resize_image(params);
            return;
        }
        
        var img = '<img src="'+data['file']+'" alt="" title="'+data['title']+'" />';
        $("#gen-thumbnails").append(img);
        $("#resizer-bar span.message").html(data['message']+' - '+(current)+' of '+total);
        resize_image(params);
        
    }, "json");
    
}

function resize_image(params){
    
    if (ids.length<=0) return;    
    
    if(ids[current]==undefined){
        $("#bar-indicator").html('100%');
        $("#bar-indicator").animate({
            width: '100%'
        }, 1000);
        current = 0;
        total = 0;
        ids = new Array();
        $("div.donebutton").show('slow');
        return;
    }
    
    percent = 1/total*100;
    
    send_resize(ids[current], params);
    $("#bar-indicator").animate({
        width: percent*(current)+'%'
    }, 1000);
    //$("#bar-indicator").css('width', percent*(current+1)+'%');
    $("#bar-indicator").html(Math.round(percent*current+1)+'%');
    current++;
    
}

function imgcontinue(){
    $("#resizer-bar").hide('slow');
    $('.select_image_cat').show('slow');
    $('#upload-errors').show('slow');
    $('#upload-controls').show('slow');
    $("#bar-indicator").html(0);
    $("#bar-indicator").css('width','0px');
    $("#gen-thumbnails").hide('slow', function(){
        $("#gen-thumbnails").html('');
    });
    
}

function show_image_pop(url){
    
  var img = new Image();
  
  // wrap our new image in jQuery, then:
  $(img)
    // once the image has loaded, execute this code
    .load(function () {
      // set the image hidden by default    
      $(this).hide('slow', function(){
          $(this).fadeIn('slow', function(){
              $('#image-loader')
                // then insert our image
                .html(this)
                
                .animate({
                    width: $(this).width()+'px',
                    height: $(img).height()+'px',
                    marginLeft: '-'+($(img).width()/2)+'px',
                    marginTop: '-'+($(img).height()/2)+'px'
                });
          });
      });        
    
    })
    
    // if there was an error loading the image, react accordingly
    .error(function () {
      // notify the user that the image could not be loaded
    })
    
    // *finally*, set the src attribute of the new image to our image
    .attr('src', url)
    .attr('onclick','$("#image-loader").hide("slow");');
    
    //alert($('#image-loader img').attr('src'));

}