$(document).ready(function(){
    
    var eles = $(".gs_block_photos .gs_photo_item");
    
    var h = 0;
    
    $(eles).each(function(){
        if($(this).height()>h)
            h = $(this).height();
    });
    
    $(eles).height(h);
    
    var eles = $(".gs_block_sets .gs_photo_item");
    
    h = 0;
    
    $(eles).each(function(){
        if($(this).height()>h)
            h = $(this).height();
    });
    
    $(eles).height(h);
    
});