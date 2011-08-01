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
    
    $("a.add_name").click(function(){
        var id= $(this).attr('id').replace("addname-",'');
        $(this).hide();
        $("#sname-"+id).slideUp('fast', function(){
            $("#name-"+id).slideDown('fast');
        });
    });
    
    $("span.img_descs").click(function(){
        
        var id = $(this).attr("id").replace("spans-", '');
        $(this).slideUp('fast', function(){
            $("#descs-"+id).slideDown('fast');
        });       
        
    });
    
});
