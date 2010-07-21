// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

var docsAjax = jQuery.extend({
    
    getSectionsList: function(page){
        
        var params = {
            'page': page,
            'action': 'resources-list'
        };
        
        $.get('include/ajax-functions.php', params, function(data){
            $("#resources-list").html(data);
        });
        
        $("#resources-list").dialog({
            autoOpen: false,
            height: 300,
            width: 500,
            modal: true
        });
            
        $("#resources-list").dialog('open');
        
    },
    
    displayForm: function(){
        
        $("#resources-form").dialog("destroy");
        $("#resources-form").dialog({
            autoOpen: false,
            height: 280,
            width: 300,
            modal: true,
            buttons: {
                '<?php _e('Create Note','docs'); ?>': function(){
                    
                    if($("#note-title").val()==''){
                        alert('<?php _e('You must specify a title for this note','docs'); ?>');
                        return;
                    }
                    
                    if($("#note-content").val()==''){
                        alert('<?php _e('You must specify a content for this note','docs'); ?>');
                        return;
                    }
                    
                    $("#frm-refs").submit();
                },
                '<?php _e('Cancel','docs'); ?>': function(){
                    $(this).dialog('close');
                }
            }
        });
        $("#resources-form").dialog("open");
        
    }
    
});