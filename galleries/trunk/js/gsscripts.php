<?php
// $Id: gsscripts.php 651 2011-06-27 05:24:56Z i.bitcero $
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
        $params =  isset($_GET['p']) ? $_GET['p'] : '';
        $xoopsOption["nocommon"] = 1;
        include_once '../../../mainfile.php';
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
		}else if($("#select-op-top").val()=='redo'){
            params = '<?php echo $params; ?>';
            url = '<?php echo XOOPS_URL.'/modules/galleries/include/upload.php'; ?>';
            redo_thumbnails(params);
            return false;
        }
		
	});
    
    $("a.regenerate").click(function(){
        ids[0] = $(this).attr("id").replace("imgr-",'');
        url = '<?php echo XOOPS_URL.'/modules/galleries/include/upload.php'; ?>';
        resize_image_fromlist('<?php echo $params; ?>');
        return false;
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
    
    $(".gs_filter_options").click(function(){
        $("#gs-filters").slideToggle('fast');
    });
	
});

<?php
		break;
	
	case 'bulkimages':


?>

<?php
		break;

}
