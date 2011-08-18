/*
$Id$
--------------------------------------------------------------
MyGalleries
Module for advanced image galleries management
Author: Eduardo Cortés
Email: i.bitcero@gmail.com
License: GPL 2.0
--------------------------------------------------------------
*/

$(document).ready(function(){
    var items = $(".gs_pic_item");
    
    if(items.length>0){
        
        h = 0;
        for(i=0;i<items.length;i++){
            
            if ($(items[i]).height()>h)
                h = $(items[i]).height();
            
        }
        
        $(".gs_pic_item").height(h);
    }
});
