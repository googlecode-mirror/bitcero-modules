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
        
        $("#resources-list").html('<img src="images/wait.gif" class="image_waiting" alt="<?php _e('Wait a second...','docs'); ?>" />');
        
        $("#resources-list").dialog({
            autoOpen: false,
            height: 300,
            width: 500,
            modal: true
        });
        
        $("#resources-list").dialog('open');
        
        var params = {
            'page': page,
            'action': 'resources-list'
        };
        
        $.get('include/ajax-functions.php', params, function(data){
            $("#resources-list").html(data);
        });
        
    },
    
    displayForm: function(){
        
        $("#resources-form").dialog("destroy");
        $("#resources-form input[name=action]").val('save');
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
                    
                    $("#resources-form label").hide('fast');
                    $("#resources-form input").hide('fast');
                    $("#resources-form textarea").hide('fast');
                    $("#resources-form .image_waiting").show('fast');
                    $("#frm-refs").submit();
                },
                '<?php _e('Cancel','docs'); ?>': function(){
                    $("#resources-form input[type=text]").val('');
                    $("#resources-form textarea").html('');
                    $(this).dialog('close');
                }
            }
        });
        $("#resources-form").dialog("open");
        
    },
    
    editNote: function(id){
        
        params = {
            id: id,
            'action': 'note-edit'
        }
        
        $("#resources-form").attr('title', '<?php _e('Edit Note','docs'); ?>');
        $("#resources-form form").hide();
        $("#resources-form .image_waiting").show();
        $("#resources-form").dialog("destroy");
        $("#resources-form").dialog({
            autoOpen: false,
            height: 280,
            width: 300,
            modal: true,
            buttons: {
                '<?php _e('Save Changes','docs'); ?>': function(){
                    
                    if($("#note-title").val()==''){
                        alert('<?php _e('You must specify a title for this note','docs'); ?>');
                        return;
                    }
                    
                    if($("#note-content").val()==''){
                        alert('<?php _e('You must specify a content for this note','docs'); ?>');
                        return;
                    }
                    
                    $("#resources-form label").hide('fast');
                    $("#resources-form input").hide('fast');
                    $("#resources-form textarea").hide('fast');
                    $("#resources-form .image_waiting").show('fast');
                    $("#frm-refs").submit();
                },
                '<?php _e('Cancel','docs'); ?>': function(){
                    $("#resources-form input[type=text]").val('');
                    $("#resources-form textarea").val('');
                    $(this).dialog('close');
                }
            }
        });
        $("#resources-form").dialog("open");
        
        
        $.get("include/ajax-functions.php", params, function(data){
            
            if (data.error!=''){
                $("#resources-form").html(data.message);
                return;
            }
            
            $("#resources-form #note-title").val(data.title);
            $("#resources-form #note-content").val(data.text);
            $("#resources-form input[name=action]").val('saveedit');
            $("#resources-form input[name=id]").val(data.res);
            $("#resources-form input[name=page]").val(get('page'));
            $("#resources-form input[name=search]").val(get('search'));
            $("#resources-form input[name=name]").val(get('name'));
            $("#resources-form form").append('<input type="hidden" name="id_ref" value="'+data.id+'"');
            
            $("#resources-form .image_waiting").hide('fast');
            $("#resources-form form").fadeIn('fast');
            
        }, 'json');
        
    }
    
});

function get( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}