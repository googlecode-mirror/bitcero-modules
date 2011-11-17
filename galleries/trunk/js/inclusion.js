var gsGallery = {
    
    load : function(page, set, limit,e){
        
        var loader = $(e).parent().parent().parent();
        $(loader).append('<div class="waiting"></div><img src="'+gsurl+'/images/loader.gif" class="loader_img" />');
        
        var params = {
            action: 'load_included',
            page: page,
            limit: limit,
            set: set            
        };
        
        $.post(gsurl+'/include/ajax-functions.php', params, function(data){
            if(data==undefined || data=='') return;
            
            $(loader).html(data);
            
        }, 'html');
        
    }
    
}