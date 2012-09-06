<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','files');
include ('header.php');

/**
* @desc Visualiza todos los archivos de un software
**/
function showFiles(){
    global $xoopsModule, $xoopsSecurity, $tpl;

    define("RMCSUBLOCATION",'fileslist');

	$item = rmc_server_var($_REQUEST, 'item', 0);
	$edit = rmc_server_var($_REQUEST, 'edit', 0);
	$id = rmc_server_var($_REQUEST, 'id', 0);

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    if($item<=0)
        redirectMsg('items.php', __('Before to view files, you must specify a download item!','dtransport'), RMMSG_INFO);

		$sw=new DTSoftware($item);

        if($sw->isNew())
            redirectMsg('items.php', __('Specified item does not exists!','dtransport'), RMMSG_WARN);

        $files = array(); // Files data container

		//Archivos sin grupo
		$sql="SELECT * FROM ".$db->prefix('dtrans_files')." WHERE id_soft=$item AND `group`=0";
		$result=$db->queryF($sql);
		while($rows=$db->fetchArray($result)){
			$fl=new DTFile();
			$fl->assignVars($rows);

			$files[] = array(
                'id'=>$fl->id(),
                'file'=>$fl->file(),
                'downs'=>$fl->hits(),
                'group'=>$fl->group(),
			    'default'=>$fl->isDefault(),
                'remote'=>$fl->remote(),
                'type'=>'files',
                'title'=>$fl->title()
            );
			
		}
		

		//Grupos pertenecientes al software
		$groups=$sw->filegroups();

		foreach ($groups as $k){
			$gr = new DTFileGroup($k);		

			$files[] = array(
                'id'=>$gr->id(),
                'file'=>$gr->name(),
                'type'=>'group'
            );

			$sql="SELECT * FROM ".$db->prefix('dtrans_files')." WHERE id_soft=$item AND `group`=$k";
			$result=$db->queryF($sql);
			while($rows=$db->fetchArray($result)){
				$fl=new DTFile();
				$fl->assignVars($rows);

				$files[] = array(
                    'id'=>$fl->id(),
                    'file'=>$fl->file(),
                    'downs'=>$fl->hits(),
                    'group'=>$fl->group(),
				    'default'=>$fl->isDefault(),
                    'remote'=>$fl->remote(),
                    'type'=>'files',
                    'title'=>$fl->title()
                );
			
			}
		
		}
		
        $groups = array();

		//Lista de grupos
		$sql ="SELECT * FROM ".$db->prefix('dtrans_groups')." WHERE id_soft=$item";
		$result=$db->queryF($sql);
		while($rows=$db->fetchArray($result)){
			$group=new DTFileGroup();
			$group->assignVars($rows);

			$groups[] = array(
                'id'=>$group->id(),
                'name'=>$group->name()
            );

		}

		
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>".__('Download Items','dtransport')."</a> &raquo; ".__('Files Management','dtransport'));

	// Title
    $title = sprintf(__('Files for "%s"','dtransport'), $sw->getVar('name'));
    $tpl->assign('xoops_pagetitle', $title);

    DTFunctions::toolbar();
    $tpl->add_style('admin.css','dtransport');

    include DT_PATH.'/include/js_strings.php';

    $tpl->add_local_script('admin.js', 'dtransport');
    $tpl->add_local_script('files.js', 'dtransport');
    $tpl->add_local_script('jquery.checkboxes.js','rmcommon','include');

    xoops_cp_header();
	
    include $tpl->get_template('admin/dtrans_files.php', 'module', 'dtransport');

	xoops_cp_footer();
}



/**
* @desc Formulario de archivos
**/
function formFiles($edit=0){
	global $tpl, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $xoopsSecurity;

    define("RMCSUBLOCATION",'newfile');

	$id = rmc_server_var($_GET, 'id', 0);
	$item = rmc_server_var($_GET, 'item', 0);


	//Verificamos si el software es válido
	if ($item<=0)
		redirectMsg('files.php', __('No download item ID has been provided!','dtransport'), RMMSG_WARN);

	//Verificamos si existe el software
	$sw = new DTSoftware($item);
	if ($sw->isNew())
		redirectMsg('files.php', __('Specified download item does not exists!','dtransport'), RMMSG_ERROR);

    $file_exists = true;
	
	if ($edit){
		//Verificamos si archivo es válido
		if ($id<=0)
			redirectMsg('./files.php?item='.$item, __('No file ID has been specified!','dtransport'), RMMSG_WARN);

		//Verificamos si existe archivo
		$fl = new DTFile($id);
		if ($fl->isNew())
			redirectMsg('files.php?item='.$item, __('Specified file does not exists!','dtransport'), RMMSG_ERROR);

        if($sw->getVar('secure'))
            $dir = $xoopsModuleConfig['directory_secure'];
        else
            $dir = $xoopsModuleConfig['directory_insecure'];

        if(!$fl->remote()){

            if(!is_file($dir.'/'.$fl->file())){
                $file_exists = false;
                showMessage(sprintf(__('File %s does not exists! You need to upload this file again.','dtransport'), $dir .'/'.$fl->file()), RMMSG_WARN);
            }


        }

	}	

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='items.php'>".$sw->getVar('name')."</a> &raquo; <a href='files.php?item=".$sw->id()."'>".__('Files','dtransport')."</a> &raquo; ".($edit ? __('Edit file','dtransport') : __('New file','dtransport')));
    $tpl->assign('xoops_pagetitle', $xoopsModule->name()." &raquo; ".$sw->getVar('name')." &raquo; ".__('Files','dtransport')." &raquo; ".($edit ? __('Edit file','dtransport') : __('New file','dtransport')));

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $func = new DTFunctions();
    $func->toolbar();
    $rmf = RMFunctions::get();
    $rmu = RMUtilities::get();

    // Uploader
    $tc = TextCleaner::getInstance();
    $uploader = new RMFlashUploader('dtfiles-uploader', DT_URL.'/ajax/upload.php');
    $token = $xoopsSecurity->createToken();
    $uploader->add_setting('onUploadStart', 'function(file){
        $("#dtfiles-uploader").uploadify("settings", "formData", {
            action: "upload",
            item: $("#item").val(),
            XOOPS_TOKEN_REQUEST: $("#XOOPS_TOKEN_REQUEST").val(),
            data: "'.$tc->encrypt($_SERVER['HTTP_USER_AGENT'].'|'.session_id().'|'.$xoopsUser->uid().'|'.$rmf->current_url()).'"
        });
    }');
    $uploader->add_setting('multi', false);
    $uploader->add_setting('fileExt', '*.'.implode(";*.",$xoopsModuleConfig['type_file']));
    $uploader->add_setting('fileDesc', sprintf(__('Allowed files (%s)', 'dtransport'), implode(";*.",$xoopsModuleConfig['type_file']).')'));
    $uploader->add_setting('sizeLimit', $xoopsModuleConfig['size_file']*1024*1024);
    $uploader->add_setting('buttonText', __('Select File...','rmcommon'));
    $uploader->add_setting('queueSizeLimit', 1);
    $uploader->add_setting('auto', true);
    $uploader->add_setting('onUploadSuccess',"function(file, resp, data){
            eval('ret = '+resp);
            if (ret.error==1){
                \$('.dt-errors').html(ret.message).slideDown('fast');
                upload_error = 1;
            } else {
                upload_error = 0;
                getFilesToken();
                \$('#dtfiles-preview .name').html(ret.file);
                \$('#dtfiles-preview .size').html(ret.size);
                \$('#size').val(ret.fullsize);
                \$('#dtfiles-preview .type').html(ret.type);
                \$('#dtfiles-preview .secure').html(ret.secure);
            }
            return true;
        }");
    $uploader->add_setting('onQueueComplete', "function(event, data){
            if(upload_error==1) return;
            \$('.dt-errors').slideUp('fast');
            \$('#dtfiles-uploader').fadeOut('fast');
            \$('#dtfiles-preview').fadeIn('fast');
        }");
    $uploader->add_setting('onSelectOnce', "function(event, data){
            \$('#upload-errors').html('');
        }");
    if($edit && $file_exists){
        $uploader->add_setting('onInit', 'function(instance){
            $("#dtfiles-uploader").hide();
        }');
    }

    $groups = array();

    //Lista de grupos
    $sql ="SELECT * FROM ".$db->prefix('dtrans_groups')." WHERE id_soft=$item";
    $result=$db->queryF($sql);
    while($rows=$db->fetchArray($result)){
        $group=new DTFileGroup();
        $group->assignVars($rows);

        $groups[] = array(
            'id'=>$group->id(),
            'name'=>$group->name()
        );

    }

    $tpl->add_head($uploader->render());
    $tpl->add_style('admin.css','dtransport');
    $tpl->add_style('files.css','dtransport');

    $tpl->add_local_script('admin.js','dtransport');
    $tpl->add_local_script('files.js','dtransport');
    $tpl->add_head_script('var upload_error = 0;');

    include DT_PATH.'/include/js_strings.php';

	xoops_cp_header();

    include $tpl->get_template("admin/dtrans_filesform.php", 'module','dtransport');

	xoops_cp_footer();

}

/**
* @desc Elimina el grupo especificado
**/
function deleteGroups(){

	global $xoopsModule,$util, $xoopsSecurity;

	$id = rmc_server_var($_REQUEST, 'id', array());
	$item = rmc_server_var($_REQUEST, 'item', 0);

    if (!$xoopsSecurity->check()){
        redirectMsg('files.php?item='.$item, __('Session token not valid!','dtransport'), RMMSG_WARN);
        die();
    }

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('files.php', __('Download item ID not provided!','dtransport'), RMMSG_WARN);
		die();
	}
	
	//Verificamos si existe el software
	$sw = new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('files.php', __('Specified download item does not exists!','dtransport'), RMMSG_WARN);
		die();
	}

	//Verificamos si grupo es válido
	if ($id<=0){
		redirectMsg('files.php?item='.$item, __('Group id not provided!','dtransport'), RMMSG_ERROR);
		die();
	}

	//Verificamos si el grupo existe
	$group = new DTFileGroup($id);
	if ($group->isNew()){
		redirectMsg('files.php?item='.$item, __('Specified group does not exists!','dtransport'), RMMSG_ERROR);
		die();
	}

	if (!$group->delete()){
		redirectMsg('files.php?item='.$item, sprintf(__('Group %s could not be deleted!','dtransport'), '<strong>'.$group->name().'</strong>').'<br />'.$group->errors(),1);
		die();
	}else{
		redirectMsg('files.php?item='.$item, sprintf(__('Group %s deleted successfully!','dtransport'), '<strong>'.$group->name().'</strong>'),0);
		die();
	}

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		formFiles();
	    break;
	case 'edit':
		formFiles(1);
	    break;
	case 'save':
		saveFiles();
	    break;
	case 'saveedit':
		saveFiles(1);
	    break;
	case 'savegroup':
		saveGroups();
	    break;
	case 'saveeditgr':
		saveGroups(1);
	    break;
	case 'updategroup':
		updateGroups(1);
	    break;
	case 'delete':
		deleteFiles();
	break;
	case 'deletegroup':
		deleteGroups();
	break;
	case 'default':
		defaultFile();
	    break;
	default:
		showFiles();

}

