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
        
        show_library();
        
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

function show_upload(){
    
    $("#library-container").hide('slow', function(){
        $("#library-content").html('');
    });
    $("#upload-container").slideDown('slow');
    $("#resizer-bar").hide('slow');
    $('.categories_selector').show('slow');
    $('#upload-errors').show('slow');
    $('#upload-controls').slideDown('slow');
    $("#bar-indicator").html('');
    $("#bar-indicator").css('width','0px');
    $("#gen-thumbnails").hide('slow', function(){
        $("#gen-thumbnails").html('');
    });
    $("#img-toolbar a").removeClass('select');
    $("#a-upload").addClass('select');
    
}

function show_library(pag){
    
    if ($("#ret-token").length>0) 
        $("#xoops-token").val($("#ret-token").val());
    
    if($("#category-field option").length==2){
		$("#category-field option").removeAttr("selected");
		var opt = $("#category-field option");
		$(opt[1]).attr("selected", 'selected');
    }

    var params = {
        category: $("#category-field").val(),
        action: 'load-images',
        XOOPS_TOKEN_REQUEST: $("#xoops-token").val(),
        url: window.parent.location.href,
        page: pag
    }
    
    $("#upload-container").slideUp('slow');
    $("#library-content").html('');
    $("#library-content").addClass('loading');
    $("#library-container").show('slow');
    $("#img-toolbar a").removeClass('select');
    $("#a-library").addClass('select');
    
    $.post('tiny-images.php', params, function(data, status){
        $("#library-content").html(data);
        $("#library-content").removeClass('loading');
    }, 'html');
    
}

function show_image_data(id){
    
    $(".image_list").show();
    $(".image_data").hide();
    $("#list-"+id).hide();
    $("#data-"+id).show();
    
}

function hide_image_data(id){
    
    $(".image_list").show();
    $(".image_data").hide();
    
}

function insert_image(id){
	ed = tinyMCEPopup.editor;
	
	var html = '';
	var ext = $("#extension_"+id).val();
	
	// Link
	if ($("#image-link-"+id).val()!=''){
		html = '<a href="'+$("#image-link-"+id).val()+'" title="'+$("#image-name-"+id).val()+'">';
	}
	
	// Image
	html += '<img src="';
	var sizes = $("input[name='size_"+id+"']");
    for(i=0;i<sizes.length;i++){
        if(!$(sizes[i]).attr('checked')) continue;
        
        // File URL
        html += $("#filesurl").val() + '/' + $(sizes[i]).val() + '"';
    }
    
    // Alignment
    var align = $("input[name='align_"+id+"']");
    for(i=0;i<align.length;i++){
        if(!$(align[i]).attr('checked')) continue;
        
        // File URL
        html += ' class="'+$(align[i]).val()+'"';
    }
    
    html += ' alt="'+$("#image-alt-"+id).val()+'" />';
    
    if ($("#image-link-"+id).val()!=''){
		html += '</a>';
	}
    
	ed.execCommand("mceInsertContent", true, html);
	tinyMCEPopup.close();
}