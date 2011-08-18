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
    
    var preloader = new Image();
    preloader.onload = function() {
        $('body').append(preloader);
        $('#gs-the-image')
        .css('background-image','')
        .css('background-image', 'url('+details.img+')')
        .css('height', $(this).height()+'px')
        .css('max-width', $(this).width())
        .attr("title", details.title);
        $('#gs-the-image div').css('height',$(this).height()-2+'px');
        $('#gs-the-image div a').css('height',$(this).height()-2+'px');
        $(preloader).remove();
    }
    preloader.src = details.img;
    
});
