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
		$("#frm-sets").submit();
	});
	
	$("#op-bottom").click(function(){
		$("#frm-sets").submit();
	});
	
	$("#frm-sets").submit(function(){
		
		if ($("#select-op-top").val()=='delete'){
			return confirm(delete_warning);
		}
		
	});
	
	$("a.gs_delete_option").click(function(){
		id = $(this).attr("id").replace("delete-",'');
		
		$("#frm-sets input[name='ids[]']").removeAttr("checked");
		$("#set-"+id).attr("checked",'checked');
		$("#select-op-top").val('delete');
		$("#frm-sets").submit();
		
	});
	
});

<?php
		break;
}
