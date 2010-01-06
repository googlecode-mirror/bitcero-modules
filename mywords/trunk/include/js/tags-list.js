// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
	$("#action-list").change(function(){
		$("#action-list-b").val($("#action-list").val());
	});
    
    $("#new-name").focus();
	
	$("#submit-newtag").click(function(){
		// Check values
		if ($("#new-name").val()==''){
			
			$("#mw-dialog").html('<?php _e('You must provide a name for this tag!','admin_mywords'); ?>');
			$("#mw-dialog").attr('title','<?php _e('Missing name','admin_mywords'); ?>');
			
			$("#mw-dialog").dialog({
				bgiframe: true,
				modal: true,
				autoOpen: false,
				buttons: {
					<?php _e('Ok','admin_mywords'); ?>: function(){
						$(this).dialog('close');
					}
				}
			});
			
			$("#mw-dialog").dialog('open');
			return;
		}
		
		$("#form-tags").submit();
		
	});
    
    $()
    
});

