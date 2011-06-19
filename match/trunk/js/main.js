// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
    
    // Role play
    $("#champ").change(function(){
        
        if($(this).val()<=0){
            return;    
        }
        
        if($("#category").val()<=0){
            return;
        }
        
        window.location.href = '?cat='+$("#category").val()+"&ch="+$("#champ").val();
        
    });
    
    $("#category").change(function(){
        
        if($(this).val()<=0){
            return;
        }
        
        if($("#champ").val()<=0) return;
        
        window.location.href = '?cat='+$("#category").val()+"&ch="+$("#champ").val();
        
    });
    
});