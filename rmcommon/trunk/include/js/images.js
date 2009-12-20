var total = 0;
var ids = new Array();
var url = '';
var current = 0;

function send_resize(id,params){
    $.get(url, {data: params, img: id, action: 'resize'}, function(data){
        
        if (data['error']){
            $("#resizer-bar span.message").html('<span>'+data['message']+'</span>');
            resize_image(params);
            return;
        }
        
        var img = '<img src="'+data['file']+'" alt="" title="'+data['title']+'" />';
        $("#gen-thumbnails").append(img);
        $("#resizer-bar span.message").html(data['message']);
        resize_image(params);
        
    }, "json");
    
}

function resize_image(params){
    
    if (ids.length<=0) return;    
    
    if(ids[current]==undefined){
        $("#bar-indicator").html('100%');
        current = 0;
        total = 0;
        ids = new Array();
        $("div.donebutton").show('slow');
        return;
    }
    
    percent = 1/total*100;
    
    send_resize(ids[current], params);
    $("#bar-indicator").animate({
        width: percent*(current+1)+'%'
    }, 1000);
    //$("#bar-indicator").css('width', percent*(current+1)+'%');
    $("#bar-indicator").html(Math.round(percent*current)+'%');
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
