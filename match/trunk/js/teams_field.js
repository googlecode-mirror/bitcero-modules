// $Id$
// --------------------------------------------------------------
// Matches
// Module to publish and manage sports matches
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
    
    $(".mch_teams_field").click(function(){
        var id = $(this).attr("id").replace("teams-container-",'');
        $("#mch-teams-loader-"+id).slideDown('fast');
        
        $("#mch-teams-loader-"+id).html('<span class="loader"><img src="../images/loading.gif" alt="" /></span>');
        
        var params = {
            
        };
        
        $.post('../include/teams.php', params, function(data){
            
            if (data.indexOf('Error:')==0){
                data = data.split(":");
                alert(data[1]);
                $("#mch-teams-loader-"+id).slideUp('fast');
                return;
            }
            
            $("#mch-teams-loader-"+id).html(data);
            
            $(".mch_tf_item").click(function(){

                var item = $(this).attr("id").replace("item-tf-", '');
                $("#mch-tf-value-"+id).val(item);
                $("#teams-container-"+id).html(
                    '<img src="'+$("#item-tf-"+item+" img").attr('src')+'" class="logo" alt="'+$("#item-tf-"+item+" img").attr("alt")+'" />'+
                    '<strong>'+$("#item-tf-"+item+" strong").html()+'</strong>'+
                    '<span>'+$("#item-tf-"+item+" span.category").html()+'</span>'+
                    '<span class="date">'+$("#item-tf-"+item+" span.date").html()+'</span>'
                );
                
                $("#mch-teams-loader-"+id).slideUp('fast',function(){
                    $(this).html('');
                });
                
                $("#teams-container-"+id).effect('bounce',{},500);
                
            });
            
        }, 'html');
        
    });
    
});