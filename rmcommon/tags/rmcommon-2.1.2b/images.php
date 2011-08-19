<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This is the images manager file for RMCommon. It is based on EXM system
* and as a substitute for xoops image manager
*/

include_once '../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';
define('RMCLOCATION','imgmanager');

/**
* Show all images existing in database
*/
function show_images(){
	global $xoopsModule, $xoopsModuleConfig;
	
	$db = Database::getInstance();
	
	// Check if some category exists
	$catnum = RMFunctions::get_num_records("rmc_img_cats");
	if ($catnum<=0){
		redirectMsg('images.php?action=newcat', __('There are not categories yet! Please create one in order to add images.','rmcommon'), 1);
		die();
	}
	
	$cat = rmc_server_var($_GET, 'category', 0);
    if ($cat<=0){
        header('location: images.php?action=showcats');
        die();
    }
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_images");
    if ($cat>0) $sql .= " WHERE cat='$cat'";
    /**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_GET, 'page', 1);
	$limit = $xoopsModuleConfig['imgsnumber'];
	list($num) = $db->fetchRow($db->query($sql));
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('images.php?'.($cat>0 ? 'category='.$cat : '').'&page={PAGE_NUM}');
    
    // Get categories
    $sql = "SELECT * FROM ".$db->prefix("rmc_images")." ".($cat>0 ? "WHERE cat='$cat'" : '')." ORDER BY id_img DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $images = array();
    $categories = array();
    $authors = array();
	
    $category = new RMImageCategory($cat);
    $sizes = $category->getVar('sizes');
    $current_size = array();
    
    foreach ($sizes as $size){
        if (empty($current_size)){
            $current_size = $size;
        } else {
            if ($current_size['width']>=$size['width'] && $size['width']>0){
                $current_size = $size;
            }
        }
    }
    
	while($row = $db->fetchArray($result)){
		$img = new RMImage();
		$img->assignVars($row);
		if (!isset($categories[$img->getVar('cat')])){
			$categories[$img->getVar('cat')] = new RMImageCategory($img->getVar('cat'));
		}
		
		if (!isset($authors[$img->getVar('uid')])){
			$authors[$img->getVar('uid')] = new XoopsUser($img->getVar('uid'));
		}
        
        $fd = pathinfo($img->getVar('file'));
		
		$images[] = array(
			'id'		=> $img->id(),
			'title'		=> $img->getVar('title'),
			'date'		=> $img->getVar('date'),
            'desc'      => $img->getVar('desc', 'n'),
			'cat'		=> $categories[$img->getVar('cat')]->getVar('name'),
			'author'	=> $authors[$img->getVar('uid')],
            'file'      => XOOPS_UPLOAD_URL.'/'.date('Y',$img->getVar('date')).'/'.date('m',$img->getVar('date')).'/sizes/'.$fd['filename'].'_'.$current_size['width'].'x'.$current_size['height'].'.'.$fd['extension'],
            'big'		=> XOOPS_UPLOAD_URL.'/'.date('Y',$img->getVar('date')).'/'.date('m',$img->getVar('date')).'/'.$fd['filename'].'.'.$fd['extension']
		);
	}
	
	$categories = RMFunctions::load_images_categories();
	
	if (RMFunctions::plugin_installed('lightbox')){
		RMLightbox::get()->add_element('#list-images a.bigimages');
		RMLightbox::get()->render();
	}
	
	xoops_cp_header();
	RMFunctions::create_toolbar();
    RMTemplate::get()->add_style('imgmgr.css','rmcommon');
    RMTemplate::get()->add_style('general.css','rmcommon');
    RMTemplate::get()->add_script('include/js/jquery.checkboxes.js');
	include RMTemplate::get()->get_template('images_images.php','module','rmcommon');
	xoops_cp_footer();
	
}

function images_form($edit = 0){
	global $xoopsModule, $xoopsModuleConfig, $xoopsSecurity, $xoopsUser, $rmc_config;
    
	$category = rmc_server_var($_GET, 'category', 0);
	$cat = new RMImageCategory($category);
	
	if (!$cat->isNew() && $cat->getVar('status')!='open'){
		showMessage(sprintf(__('Category %s is closed. Please, select another category.','rmcommon'), '"'.$cat->getVar('name').'"'), 1);
		$cat = new RMImageCategory();
	}
	
    /*$upload = new RMFlashUploader('images', 'images.php');*/
    if (!$cat->isNew()){
        $uploader = new RMFlashUploader('files-container', RMCURL.'/include/upload.php');
        $uploader->add_setting('scriptData', array(
        	'action'=>'upload',
        	'category'=>$cat->id(),
        	// Need better code
        	'rmsecurity'=>TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.RMCURL.'/images.php'.'|'.$xoopsSecurity->createToken(), true))
        );
        $uploader->add_setting('multi', true);
        $uploader->add_setting('fileExt', '*.jpg;*.png;*.gif');
        $uploader->add_setting('fileDesc', __('All Images (*.jpg, *.png, *.gif)','rmcommon'));
        $uploader->add_setting('sizeLimit', $cat->getVar('filesize') * $cat->getVar('sizeunit'));
        $uploader->add_setting('buttonText', __('Browse Images...','rmcommon'));
        $uploader->add_setting('queueSizeLimit', 100);
        $uploader->add_setting('onComplete',"function(event, id, file, resp, data){
            eval('ret = '+resp);
            if (ret.error){
                \$('#upload-errors').append('<span class=\"failed\"><strong>'+file.name+'</strong>: '+ret.message+'</span>');
            } else {
                total++;
                ids[total-1] = ret.id;
                \$('#upload-errors').append('<span class=\"done\"><strong>'+file.name+'</strong>: ".__('Uploaded successfully!','rmcommon')."</span>');
            }
            return true;
        }");
        $uploader->add_setting('onAllComplete', "function(event, data){
        	
        	if(total<=0) return;
        	
            \$('.select_image_cat').hide('slow');
            \$('#upload-errors').hide('slow');
            \$('#upload-errors').html('');
            \$('#upload-controls').hide('slow');
            \$('#resizer-bar').show('slow');
            \$('#resizer-bar').effect('highlight',{},1000);
            \$('#gen-thumbnails').show();
            
            var increments = 1/total*100;
            url = '".RMCURL."/images.php';
            
            params = '".TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.RMCURL.'/images.php'.'|'.$xoopsSecurity->createToken(), true)."';
            resize_image(params);
            
        }");
        RMTemplate::get()->add_head($uploader->render());
	}
	RMTemplate::get()->add_style('imgmgr.css', 'rmcommon');
    RMTemplate::get()->add_script('include/js/images.js');
	
	// Load Categories
	$categories = RMFunctions::load_images_categories("WHERE status='open' ORDER BY id_cat DESC");
	
	xoops_cp_header();
	RMFunctions::create_toolbar();
	
    include RMTemplate::get()->get_template('images_uploadimages.php','module','rmcommon');
    
	xoops_cp_footer();
	
}

function show_categories(){
    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $xoopsSecurity;
    
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rmc_img_cats");
    /**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_GET, 'page', 1);
	$limit = $xoopsModuleConfig['catsnumber'];
	list($num) = $db->fetchRow($db->query($sql));
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('images.php?action=showcats&page={PAGE_NUM}');
    
    // Get categories
    $sql = "SELECT * FROM ".$db->prefix("rmc_img_cats")." ORDER BY id_cat DESC LIMIT $start,$limit";
    
    $result = $db->query($sql);
    $categories = array();
    
    while ($row = $db->fetchArray($result)){
    	$cat = new RMImageCategory();
    	$cat->assignVars($row);
    	$groups = $cat->getVar('groups');
		$categories[] = array(
			'id'		=>	$cat->id(),
			'name'		=>	$cat->getVar('name'),
			'status'	=> 	$cat->getVar('status'),
			'gwrite'	=> 	RMFunctions::get_groups_names($groups['write']),
			'gread'		=> 	RMFunctions::get_groups_names($groups['read']),
			'sizes'		=>	$cat->getVar('sizes'),
			'images'	=>	RMFunctions::get_num_records('rmc_images', 'cat='.$cat->id())
		);
    }
    
    RMTemplate::get()->add_style('general.css','rmcommon');
    RMTemplate::get()->add_style('imgmgr.css','rmcommon');
    RMTemplate::get()->add_script('include/js/jquery.checkboxes.js');
    RMFunctions::create_toolbar();
    xoops_cp_header();
    
    
    include RMTemplate::get()->get_template('images_categories.php', 'module', 'rmcommon');
    
    xoops_cp_footer();
    
}

/**
* Show form to create categories
*/
function new_category($edit = 0){
	define('RMSUBLOCATION','rmc_imgnewcat');
	
	extract($_GET);
	
	// Check category to edit
	if ($edit){
		if ($id<=0){
			redirectMsg('images.php?action=showcats', __('You must specify a category id to edit!','rmcommon'), 1);
			die();
		}
		
		$cat = new RMImageCategory($id);
		
		if ($cat->isNew()){
			redirectMsg('images.php?action=showcats', __('The specified category does not exist!','rmcommon'), 1);
			die();
		}
		
		// Write and read permissions
		$perms = $cat->getVar('groups');
		$write = isset($write) ? $write : $perms['write'];
		$read = isset($read) ? $read : $perms['read'];
		// Active
		$active = $cat->getVar('status');
		
	}
	
	RMFunctions::create_toolbar();
	xoops_cp_header();
    
	$form = new RMForm('','','');
	$fwrite = new RMFormGroups('','write',true,1, 3, isset($write) ? $write : array(XOOPS_GROUP_ADMIN));
	$fread = new RMFormGroups('','read',true,1, 3, isset($read) ? $read : array(0));
	
    RMTemplate::get()->add_script('include/js/imgmanager.js');
	include RMTemplate::get()->get_template('categories_form.php', 'module', 'rmcommon');
	RMTemplate::get()->add_style('imgmgr.css','rmcommon');
	xoops_cp_footer();
	
}

/**
* Stores data for new categories
*/
function save_category($edit = 0){
    global $xoopsDB, $xoopsModuleConfig, $xoopsModule;
    
    $q = 'images.php?action='.($edit ? 'editcat' : 'newcat');
    foreach($_POST as $k => $v){
        if ($k=='action' || $k=='XOOPS_TOKEN_REQUEST') continue;
        if (is_array($v)){
            $q .= '&'.RMFunctions::urlencode_array($v, $k);
        } else {
            $q .= '&'.$k.'='.urlencode($v);
        }
        
    }
    extract($_POST);
    
    if ($edit){
        
        if ($id<=0){
            redirectMsg('images.php?action=showcats', __('Specify a valid category id','rmcommon'), 1);
            die();
        }
        
        $cat = new RMImageCategory($id);
        if ($cat->isNew()){
            redirectMsg('images.php?action=showcats', __('Specified category does not exists!','rmcommon'), 1);
            die();
        }
        
    } else {
        $cat = new RMImageCategory();
    }
    
    if ($name==''){
        redirectMsg($q, __('Please specify a category name','rmcommon'), 1);
        die();
    }
    
    if (empty($read)) $read = array(0);
    if (empty($write)) $write = array(0);
    
    // Check if resize data is correct
    $schecked = array();
    foreach ($sizes as $size){
        if (trim($size['name'])=='') continue;
        if ($size['type']!='none' && $size['width']<=0 && $size['height']<=0) continue;
        $schecked[] = $size;
    }
    
    if (empty($schecked)){
        redirectMsg($q, __('You must create one size for this category at least!','rmcommon'), 1);
        die();
    }
    
    // Check if there are a category with same name
    $num = RMFunctions::get_num_records('rmc_img_cats', "name='$name'".($edit ? " AND id_cat<>'".$cat->id()."'" : ''));
    if($num>0){
		redirectMsg($q, __('There is already a category with the same name!','rmcommon'), 1);
		die();
    }
    
    $cat->setVar('name', $name);
    $cat->setVar('status', $status);
    $cat->setVar('groups', array('write'=>$write,'read'=>$read));
    $cat->setVar('sizes',$schecked);
    $cat->setVar('filesize', $filesize<=0 ? '50' : $filesize);
    $cat->setVar('sizeunit', $sizeunit<=0 ? '1024' : $sizeunit);
    
    if ($cat->save()){
        redirectMsg('images.php?action=showcats', __($edit ? 'Category updated successfully!' : 'Category saved successfully!','rmcommon'), 0);
    } else {
        redirectMsg($q, __('There were some erros while trying to save this category.','rmcommon').'<br />'.$cat->errors(), 1);
    }
    
}

/**
* This functions allows to modify the status of categories
*/
function category_status($action='open'){
	
	$cats = rmc_server_var($_REQUEST, 'cats', array());
	
	if (empty($cats)){
		$id = rmc_server_var($_GET, 'id', 0);
		
		if ($id<=0){
			redirectMsg('images.php?action=showcats', __('Specify one category at least to change status!','rmcommon'), 1);
			die();
		}
		
		$cats[] = $id;
		
	}
	
	$db = Database::getInstance();
	$sql = "UPDATE ".$db->prefix("rmc_img_cats")." SET status='".$action."' WHERE id_cat IN(".implode(',',$cats).")";

	if ($db->queryF($sql)){
		redirectMsg('images.php?action=showcats', __('Database updated successfully!','rmcommon'), 0);
		die();
	} else {
		redirectMsg('images.php?action=showcats', __('There were some erros while updating database:','rmcommon').'<br />'.$db->error(), 1);
		die();
	}
	
}

function send_error($message){
    $data['error'] = 1;
    $data['message'] = $message;
    echo json_encode($data);
    die();
}

function resize_images(){
    global $xoopsUser, $xoopsLogger, $xoopsSecurity;
    
    set_time_limit(0);
    
    error_reporting(0);
    $xoopsLogger->activated = false;
    
    $params = rmc_server_var($_GET, 'data','');
    $id = rmc_server_var($_GET, 'img', 0);
    
    if ($params==''){
        send_error(__('Unauthorized!','rmcommon'));
    }
    
    if ($id<=0){
        send_error(__('Invalid image!','rmcommon'));
    }
    
    $params = TextCleaner::decrypt($params);
    $data = explode('|', $params);
    
    if ($data[0]!=$xoopsUser->uid()){
        send_error(__('Unauthorized!','rmcommon'));
    }
    
    if ($data[1]!=RMCURL.'/images.php'){
        send_error(__('Unauthorized!','rmcommon'));
    }
    
    if (!$xoopsSecurity->check(false, $data[2])){
        send_error(__('Unauthorized!','rmcommon'));
    }
    
    $image = new RMImage($id);
    if ($image->isNew()){
        send_error(__('Image not found!','rmcommon'));
    }
    
    // Resize image
    $cat = new RMImageCategory($image->getVar('cat'));
    if (!$cat->user_allowed_toupload($xoopsUser)){
        send_error(__('Unauthorized','rmcommon'));
    }
    
    $sizes = $cat->getVar('sizes');
    $updir = XOOPS_UPLOAD_PATH.'/'.date('Y', $image->getVar('date')).'/'.date('m',time());
    $upurl = XOOPS_UPLOAD_URL.'/'.date('Y', $image->getVar('date')).'/'.date('m',time());;
    $width = 0;
    $tfile = '';
    
    foreach ($sizes as $size){
        
        if ($size['width']<=0 && $size['height']<=0) continue;
        
        $fd = pathinfo($updir.'/'.$image->getVar('file'));
        
        $name = $updir.'/sizes/'.$fd['filename'].'_'.$size['width'].'x'.$size['height'].'.'.$fd['extension'];
        $sizer = new RMImageResizer($updir.'/'.$image->getVar('file'), $name);
        
        switch($size['type']){
            case 'crop':
                $sizer->resizeAndCrop($size['width'], $size['height']);
                break;
            default:
                if ($size['width']<=0 || $size['height']<=0){
                    $sizer->resizeWidth($size['width']);
                } else {
                    $sizer->resizeWidthOrHeight($size['width'], $size['height']);
                }                
                break;
        }
        
        if($size['width']<=$width || $width==0){
            $width = $size['width'];
            $tfile = str_replace(XOOPS_UPLOAD_PATH, XOOPS_UPLOAD_URL, $name);
        }
        
    }
    
    $ret['message'] = sprintf(__('%s done!', 'rmcommon'), $image->getVar('file'));
    $ret['done'] = 1;
    $ret['file'] = $tfile;
    $ret['title'] = $image->getVar('title');
    echo json_encode($ret);
        
    die();
}

/**
* Function to edit images
*/
function edit_image(){
    global $xoopsUser, $xoopsSecurity;
    
    $id = rmc_server_var($_GET, 'id', 0);
    $page = rmc_server_var($_GET, 'page', '');
    if ($id<=0){
        redirectMsg('images.php', __('Invalid image ID', 'rmcommon'), 1);
        die();
    }
    
    $image = new RMImage($id);
    if ($image->isNew()){
        redirectMsg('images.php', __('Image not found!', 'rmcommon'), 1);
        die();
    }
    
    $cat = new RMImageCategory($image->getVar('cat'));
    $sizes = $cat->getVar('sizes');
    $current_size = array();
    
    $fd = pathinfo($image->getVar('file'));
    $updir = '/'.date('Y', $image->getVar('date')).'/'.date('m',time());
    
    foreach ($sizes as $size){
    	if ($size['width']<=0) continue;
        if (empty($current_size)){
            $current_size = $size;
        } else {
            if ($current_size['width']>=$size['width'] && $size['width']>0){
                $current_size = $size;
            }
        }
        
        $image_data['sizes'][] = array(
            'file' => XOOPS_UPLOAD_URL.$updir.'/sizes/'.$fd['filename'].'_'.$size['width'].'x'.$size['height'].'.'.$fd['extension'],
            'name' => $size['name']
        );
    }
    
    
    $image_data['thumbnail'] = XOOPS_UPLOAD_URL.$updir.'/sizes/'.$fd['filename'].'_'.$current_size['width'].'x'.$current_size['height'].'.'.$fd['extension'];
    $mimes = include(XOOPS_ROOT_PATH.'/include/mimetypes.inc.php');
    $image_data['mime'] = isset($mimes[$fd['extension']]) ? $mimes[$fd['extension']] : 'application/octet-stream';
    $image_data['file'] = $image->getVar('file');
    $image_data['date'] = $image->getVar('date');
    $image_data['title'] = $image->getVar('title');
    $image_data['desc'] = $image->getVar('desc', 'e');
    $image_data['url'] = XOOPS_UPLOAD_URL.$updir.'/'.$image->getVar('file');
    
    $categories = RMFunctions::load_images_categories("WHERE status='open' ORDER BY id_cat DESC");
    
    xoops_cp_header();
    RMFunctions::create_toolbar();
    
    RMTemplate::get()->add_script('include/js/images.js');
    RMTemplate::get()->add_script('include/js/jquery.validate.min.js');
    RMTemplate::get()->add_style('imgmgr.css', 'rmcommon');
    include RMTemplate::get()->get_template('images_edit.php','module','rmcommon');
    
    xoops_cp_footer();
}

/**
* Update image data
*/
function update_image(){
	global $xoopsUser, $xoopsSecurity;
	
	set_time_limit(0);
	
	$title = rmc_server_var($_POST, 'title', '');
	$category = rmc_server_var($_POST, 'cat', '');
	$desc = rmc_server_var($_POST, 'desc', '');
	$page = rmc_server_var($_POST, 'page', 1);
	$id = rmc_server_var($_POST, 'id', 0);
	
	if (!$xoopsSecurity->check()){
		redirectMsg('images.php', __('Operation not allowed!','rmcommon'), 1);
		die();
	}
	
	if ($id<=0){
		redirectMsg("images.php?category=$cat&page=$page", __('Image ID not provided!','rmcommon'), 1);
		die();
	}
	
	if (trim($title)==''){
		redirectMsg("images.php?action=edit&id=$id&page=$page", __('You must  provide a title for this image','rmcommon'), 1);
		die();
	}
	
	$image = new RMImage($id);
	if ($image->isNew()){
		redirectMsg("images.php?category=$cat&page=$page", __('Image not exists!','rmcommon'), 1);
		die();
	}
	
	$cat = new RMImageCategory($category);
	if ($cat->isNew()){
		redirectMsg("images.php", __('Category not exist!','rmcommon'), 1);
		die();
	}
	
	if ($cat->id()!=$image->getVar('cat')){
		$pcat = new RMImageCategory($image->getVar('cat'));
	}
	
	$image->setVar('title', $title);
	$image->setVar('desc', $desc);
	if (isset($pcat)) $image->setVar('cat', $cat->id());
	
	if (!$image->save()){
		redirectMsg("images.php?action=edit&id=$id&page=$page", __('the image could not be updated!','rmcommon').'<br />'.$image->errors(), 1);
		die();
	}
	
	// Modify image dimensions if category has changed
	if (!isset($pcat)){
		redirectMsg("images.php?category=".$cat->id()."&page=$page", __('Image updated succesfully!','rmcommon'), 0);
		die();
	}
	
	$fd = pathinfo($image->getVar('file'));
    $updir = XOOPS_UPLOAD_PATH.'/'.date('Y', $image->getVar('date')).'/'.date('m',time());
    
    // Delete current image files
    foreach ($pcat->getVar('sizes') as $size){
		if ($size['width']<=0) continue;
		$file = $updir.'/sizes/'.$fd['filename'].'_'.$size['width'].'x'.$size['height'].'.'.$fd['extension'];
		@unlink($file);
		
    }
    
    // Create new image files
    foreach ($cat->getVar('sizes') as $size){
		if ($size['width']<=0 && $size['height']<=0) continue;
        
        $name = $updir.'/sizes/'.$fd['filename'].'_'.$size['width'].'x'.$size['height'].'.'.$fd['extension'];
        $sizer = new RMImageResizer($updir.'/'.$image->getVar('file'), $name);
        
        switch($size['type']){
            case 'crop':
                $sizer->resizeAndCrop($size['width'], $size['height']);
                break;
            default:
                if ($size['width']<=0 || $size['height']<=0){
                    $sizer->resizeWidth($size['width']);
                } else {
                    $sizer->resizeWidthOrHeight($size['width'], $size['height']);
                }                
                break;
        }
        
        $width = $width==0 ? $size['width'] : $width;
        if($width<$size['width']){
            $with = $size['width'];
            $tfile = str_replace(XOOPS_UPLOAD_PATH, XOOPS_UPLOAD_URL, $name);
        }
    }
    
    redirectMsg('images.php?category='.$cat->id(), __('Image updated successfully!', 'rmcommon'), 0);
	
}

/**
* Delete an image
*/
function delete_image(){
	
	$ids = rmc_server_var($_REQUEST, 'imgs', array());
	$page = rmc_server_var($_REQUEST, 'page', 0);
	$category = rmc_server_var($_REQUEST, 'category', 0);
	
	if (count($ids)<=0){
		redirectMsg('images.php?category='.$category.'&page='.$page, __('Please, speciy an image at least!','rmcommon'), 1);
		die();
	}
	
	$errors = '';
	
	foreach ($ids as $id){
	
		$image = new RMImage($id);
		if ($image->isNew()){
			redirectMsg('images.php', __('Image not exists!','rmcommon'), 1);
			die();
		}
		
		$cat = new RMImageCategory($image->getVar('cat'));
		
		$fd = pathinfo($image->getVar('file'));
	    $updir = XOOPS_UPLOAD_PATH.'/'.date('Y', $image->getVar('date')).'/'.date('m',time());
	    
	    // Delete current image files
	    foreach ($cat->getVar('sizes') as $size){
			if ($size['width']<=0) continue;
			$file = $updir.'/sizes/'.$fd['filename'].'_'.$size['width'].'x'.$size['height'].'.'.$fd['extension'];
			@unlink($file);
	    }
	    
	    $file = $updir.'/'.$image->getVar('file');
		@unlink($file);
	    
	    if (!$image->delete()){
	    	$errors .= $image->errors();
	    }
	
	}
    
    if ($errors!=''){
		redirectMsg('images.php?category='.$cat->id().'&page='.$page, __('Errors ocurred during images deletion!', 'rmcommon').'<br />'.$errors, 0);
	} else {
		redirectMsg('images.php?category='.$cat->id().'&page='.$page, __('Images deleted successfully!', 'rmcommon'), 0);
    }
	
}

/**
* This function deletes all images in a category and the category
*/
function delete_category(){
	global $xoopsSecurity;
	
	$id = rmc_server_var($_GET, 'id', 0);
	
	if (!$xoopsSecurity->check()){
		redirectMsg('images.php?action=showcats', __('Operation not allowed!', 'rmcommon'), 1);
		die();
	}
	
	if ($id<=0){
		redirectMsg('images.php?action=showcats', __('Category ID not provided', 'rmcommon'), 1);
		die();
	}
	
	$cat = new RMImageCategory($id);
	if ($cat->isNew()){
		redirectMsg('images.php?action=showcats', __('Category not found', 'rmcommon'), 1);
		die();
	}
	
	$sizes = array();
	foreach ($cat->getVar('sizes') as $size){
		if ($size['width']<=0) continue;
		
		$sizes[] = '_'.$size['width'].'x'.$size['height'];
		
	}
	
	$db = Database::getInstance();
	$sql = "SELECT * FROM ".$db->prefix("rmc_images")." WHERE cat='".$cat->id()."'";
	$result = $db->query($sql);
	
	while($row = $db->fetchArray($result)){
		$image = new RMImage();
		$image->assignVars($row);
		
		$updir = XOOPS_UPLOAD_PATH.'/'.date('Y', $image->getVar('date')).'/'.date('m',time());
		$fd = pathinfo($image->getVar('file'));
		
		foreach ($sizes as $size){
			$file = $updir.'/sizes/'.$fd['filename'].$size.'.'.$fd['extension'];
			@unlink($file);
	    }
	    $file = $updir.'/'.$image->getVar('file');
	    @unlink($file);
	    $image->delete();
	}
	
	if ($cat->delete()){
		redirectMsg('images.php?action=showcats', __('Category deleted successfully!', 'rmcommon'), 0);
	} else {
		redirectMsg('images.php?action=showcats', __('Errors ocurred while deleting the category', 'rmcommon').'<br />'.$cat->errors(), 0);
	}
	
}

/**
* Update image thumbnails
*/
function update_thumbnails(){
    global $xoopsUser, $xoopsSecurity;
    $cat = new RMImageCategory(rmc_server_var($_POST, 'category', 0));
    $imgs = rmc_server_var($_POST, 'imgs', array());
    
    $ids = implode(',', $imgs);
    
    RMTemplate::get()->add_style('imgmgr.css', 'rmcommon');
    RMTemplate::get()->add_script('include/js/images.js');
    
    xoops_cp_header();
    RMFunctions::create_toolbar();
    
    $isupdate = true;
    RMTemplate::get()->add_head("<script type='text/javascript'>
    \$(document).ready(function(){
        ids = [$ids];
        total = ".count($imgs).";
        \$('#resizer-bar').show('slow');
        \$('#resizer-bar').effect('highlight',{},1000);
        \$('#gen-thumbnails').show();
        
        var increments = 1/total*100;
        url = '".RMCURL."/images.php';
            
        params = '".TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.RMCURL.'/images.php'.'|'.$xoopsSecurity->createToken(), true)."';
        resize_image(params);
    });</script>");
    
    include RMTemplate::get()->get_template('images_uploadimages.php','module','rmcommon');
    
    xoops_cp_footer();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
    case 'showcats':
        show_categories();
        break;
	case 'newcat':
		new_category();
		break;
	case 'editcat':
		new_category(1);
		break;
	case 'delcat':
		delete_category();
		break;
    case 'save':
        save_category();
        break;
    case 'saveedit':
    	save_category(1);
    	break;
    case 'opencat':
    	category_status('open');
    	break;
    case 'closecat':
    	category_status('close');
    	break;
    case 'new':
    	images_form(0);
    	break;
    case 'resize':
        resize_images();
        break;
    case 'edit':
        edit_image();
        break;
    case 'update':
    	update_image();
    	break;
    case 'delete':
    	delete_image();
    	break;
    case 'thumbs':
        update_thumbnails();
        break;
	default:
		show_images();
		break;
}
