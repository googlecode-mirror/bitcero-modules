var total = 0;
var ids = new Array();
var url = '';
var current = 0;

function send_resize(id,params){
    $.get(url, {data: params, img: id, action: 'resize'}, function(data){
        alert(data);
        resize_image(params);
        
    });
    
}

function resize_image(params){
    
    if (ids.length<=0) return;    
    
    if(ids[current]==undefined){
        $("#bar-indicator").html('100%');
        current = 0;
        total = 0;
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
}
