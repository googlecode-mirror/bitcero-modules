<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$file = isset($_GET['file']) ? $_GET['file'] : '';
$form = isset($_GET['form']) ? $_GET['form'] : 'frm-sets';

switch ($file){
	
	case 'sets':
?>

$(document).ready(function(){
	
	$("#select-op-top").change(function(){
		$("#select-op-bottom").val($("#select-op-top").val());
	});
	
	$("#select-op-bottom").change(function(){
		$("#select-op-top").val($("#select-op-bottom").val());
	});
	
	$("#op-top").click(function(){
		$("#<?php echo $form; ?>").submit();
	});
	
	$("#op-bottom").click(function(){
		$("#<?php echo $form; ?>").submit();
	});
	
	$("#<?php echo $form; ?>").submit(function(){
		
		if ($("#select-op-top").val()=='delete'){
			return confirm(delete_warning);
		}
		
	});
	
	$("a.gs_delete_option").click(function(){
		id = $(this).attr("id").replace("delete-",'');
		$("#<?php echo $form; ?> input[name='ids[]']").removeAttr("checked");
		$("#item-"+id).attr("checked",'checked');
		$("#select-op-top").val('delete');
		$("#<?php echo $form; ?>").submit();
		
	});
	
	$("a.gs_edit_option").click(function(){
		id = $(this).attr("id").replace("edit-",'');
		$("#<?php echo $form; ?> input[name='ids[]']").removeAttr("checked");
		$("#item-"+id).attr("checked",'checked');
		$("#select-op-top").val('edit');
		$("#<?php echo $form; ?>").submit();
		
	});
	
});

<?php
		break;

}
