<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','items');
include ('header.php');

/**
* @desc Muestra todos lo elementos registrados
**/
function showItems(){
    define('RMCSUBLOCATION','downitems');
	global $xoopsModule, $xoopsSecurity;
	
	$search= rmc_server_var($_REQUEST,'search','');
	$sort = rmc_server_var($_REQUEST,'sort','id_soft');
	$mode = rmc_server_var($_REQUEST,'mode',1);
	$sort = $sort=='' ? 'id_soft' : $sort;
	$catid = rmc_server_var($_REQUEST,'cat',0);
	$type = rmc_server_var($_REQUEST,'type','');
	
	//Barra de Navegación
    $db = XoopsDatabaseFactory::getDatabaseConnection();
	$sql = "SELECT COUNT(*) FROM ".($type=='edit' ? $db->prefix('dtrans_software_edited') : $db->prefix('dtrans_software'));
	$sql.=$catid ? " WHERE id_cat='$catid'" : '';
	$sql.=$type=='wait' ? ($catid ? " AND approved=0" : " WHERE approved=0") : "";
	$sql1='';
	if ($search){
		$words=explode(" ",$search);
	
		foreach ($words as $k){
			
			//Verificamos si la palabra proporcionada es mayor a 2 caracteres
			if (strlen($k)<=2) continue;

			$sql1.=($sql1=='' ? ($catid || $type=='wait' ? " AND " :  " WHERE ") : " OR "). " (name LIKE '%$k%' OR uname LIKE '%$k%') ";	

		}

	}
	$sql2=" ORDER BY $sort ".($mode ? "DESC" : "ASC");
			
	list($num)=$db->fetchRow($db->queryF($sql.$sql1.$sql2));
	
	$page = rmc_server_var($_REQUEST,'page',1);
    $limit = 15;
    
    $nav = new RMPageNav($num, $limit, $page);
    $nav->target_url("items.php?search=$search&amp;sort=$sort&amp;mode=$mode&amp;cat=$catid&amp;type=$type&page={PAGE_NUM}");
    $navpage = $nav->render(false, true);
    $start = $nav->start();
	//Fin de barra de navegación
	
	$catego=new DTCategory($catid);
	$sql="SELECT * FROM ".($type=='edit' ? $db->prefix('dtrans_software_edited') : $db->prefix('dtrans_software'));
	$sql.=$catid ? " WHERE id_cat=$catid" : '';
	$sql.=$type=='wait' ? ($catid ? " AND approved=0" : " WHERE approved=0") : "";
	$sql2.=" LIMIT $start,$limit";
	$result=$db->queryF($sql.$sql1.$sql2);
    $items = array();

    $timeFormat = new RMTimeFormatter(0, '%m%-%d%-%Y%');

	while ($rows=$db->fetchArray($result)){
		if ($type=='edit'){
			$sw = new DTSoftwareEdited();
		}else{
			$sw = new DTSoftware();
		}
		$sw->assignVars($rows);
        $img = new RMImage($sw->getVar('image'));
        $user = new XoopsUser($sw->getVar('uid'));

		$items[] = array(
            'id'=>($type=='edit' ? $sw->software() : $sw->id()),
            'name'=>$sw->getVar('name'),
            'screens'=>$sw->getVar('screens'),
		    'image'=>$img->get_smallest(),
            'secure'=>$sw->getVar('secure'),
            'approved'=>$sw->getVar('approved'),
            'uname'=>$user->getVar('uname'),
            'created' => $timeFormat->format($sw->getVar('created')),
		    'modified'=>$timeFormat->format($sw->getVar('modified')),
		    'link'=> $sw->permalink(),
            'featured'=>$sw->getVar('featured'),
            'daily'=>$sw->getVar('daily'),
            'password'=>$sw->getVar('password')!=''
        );
	}


	//Lista de categorías
	$categories = array();
	DTFunctions::getCategos($categos, 0, 0, array(), true);
	foreach ($categos as $k){
		$cat = $k['object'];
		$categories[] = array('id'=>$cat->id(),'name'=>str_repeat('--', $k['jumps']).' '.$cat->name());	
	}
	
	switch ($type){
		case 'wait':
			$loc = __('Pending Downloads','dtransport');
		    break;
		case 'edit':
			$loc = __('Edited Downloads','dtransport');
		    break;
		default:
			$loc = __('Downloads Management','dtransport');
            break;
	}

	DTFunctions::toolbar();

    $tpl = RMTemplate::get();
    $tpl->add_style('admin.css','dtransport');
    $tpl->add_local_script('admin.js','dtransport');
    $tpl->add_local_script('items.js','dtransport');
    $tpl->add_local_script('jquery.checkboxes.js','rmcommon','include');

    include DT_PATH.'/include/js_strings.php';

	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".$loc);
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/dtrans_items.php','module','dtransport');
    
	xoops_cp_footer();	
	
}


/**
* @desc Formulario de Elementos
**/
function formItems($edit=0){
	global $xoopsModule,$xoopsConfig,$xoopsModuleConfig, $rmc_config, $xoopsSecurity, $functions;
	
    define('RMCSUBLOCATION','newitem');
    
    // Get layout data
	$id     = intval(rmc_server_var($_REQUEST, 'id', 0));
	$page   = intval(rmc_server_var($_REQUEST, 'page', 0));
	$search = rmc_server_var($_REQUEST, 'search', '');
	$sort   = rmc_server_var($_REQUEST, 'sort', 'id_soft');
	$mode   = intval(rmc_server_var($_REQUEST, 'mode', 0));
	$catid  = intval(rmc_server_var($_REQUEST, 'car', 0));
	$type   = rmc_server_var($_REQUEST, 'type', '');

    $ev = RMEvents::get();

	$params='?page='.$page.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$catid.'&type='.$type;
    
	if ($edit){
		//Verificamos que el software sea válido
		if ($id<=0){
			redirectMsg('items.php'.$params, __('Download item has not been specified!','dtransport'),1);
			die();
		}

		//Verificamos que el software exista
		if ($type=='edit'){
			$sw = new DTSoftwareEdited($id);
            $location = __('Verifying edited item!','dtransport');
		}else{
			$sw=new DTSoftware($id);
            $location = __('Editing download item','dtransport');
		}
		
		if ($sw->isNew()){
			redirectMsg('./items.php'.$params, __('Specified download item does not exists!','dtransport'),1);
			die();
		}

	}else{
        $sw = new DTSoftware();
        $location = __('New Download Item','dtransport');
    }
	  
    $form = new RMForm('','','');
	$ed = new RMFormEditor('','desc','99%','300px',$edit ? $sw->getVar('desc', 'e') : '',$rmc_config['editor_type']);
	$ed->addClass('required');
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    //Lista de categorías
    $categos = array();
    $swcats = $sw->categories();
    DTFunctions::getCategos($categos, 0, 0, array(), false);
    foreach ($categos as $row){
        $cat = new DTCategory();
        $cat->assignVars($row);
        $categories[] = array(
                    'id'=>$cat->id(),
                    'name'=>$cat->name(),
                    'parent'=>$cat->parent(),
                    'active'=>$cat->active(),
                    'description' => $cat->desc(),
                    'indent'=>$row['jumps'],
                    'selected' => $edit?in_array($cat->id(), $swcats):''
                );    
    }
    unset($categos);
    
    // Licencias
    $sql="SELECT * FROM ".$db->prefix('dtrans_licences');
    $result=$db->queryF($sql);
    $lics = array();
    $lics[] = array(
        'id' => 0,
        'name' => __('Other license','dtransport'),
        'selected' => !$edit||in_array(0, $sw->licences())?1:0
    );
    while($row = $db->fetchArray($result)){
        $lic = new DTLicense();
        $lic->assignVars($row);
        $lics[] = array(
            'id' => $lic->id(),
            'name' => $lic->name(),
            'selected' => $edit?in_array($lic->id(), $sw->licences()):''
        );
    }
    unset($lic);
    
    // Plataformas
    $sql="SELECT * FROM ".$db->prefix('dtrans_platforms');
    $result=$db->queryF($sql);
    $oss = array();
    $oss[] = array(
        'id' => 0,
        'name' => __('Other platform','dtransport'),
        'selected' => !$edit||in_array(0, $sw->platforms())?1:0
    );
    while($row = $db->fetchArray($result)){
        $os = new DTPlatform();
        $os->assignVars($row);
        $oss[] = array(
            'id' => $os->id(),
            'name' => $os->name(),
            'selected' => $edit?in_array($os->id(), $sw->platforms()):''
        );
    }
    unset($os);
    
    // Allowed groups
    $field = new RMFormGroups('','groups',1,1,1,$edit ? $sw->getVar('groups') : array(1,2));
    $groups = $field->render();

    // Tags
    $ftags = $sw->tags(true);
    $tags = array();
    foreach($ftags as $tag){
        $tags[] = $tag->getVar('tag');
    }
    unset($ftags);
    
    
    RMTemplate::get()->add_style('admin.css','dtransport');
    RMTemplate::get()->add_style('items.css','dtransport');
	
	RMTemplate::get()->add_local_script('itemsform.js', 'dtransport');
	RMTemplate::get()->add_local_script('jquery.validate.min.js', 'rmcommon', 'include');
    include DT_PATH.'/include/js_strings.php';
	
    DTFunctions::toolbar();
    xoops_cp_location($location);
    xoops_cp_header();
    include RMTemplate::get()->get_template('admin/dtrans_formitems.php','module','dtransport');
    
	xoops_cp_footer();

}

/**
* desc Elimina de la base de datos los elementos
**/
function deleteItems(){
	global $xoopsModule,$util,$xoopsModuleConfig,$xoopsConfig;

	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$sort=isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_soft';
	$mode=isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$cat=isset($_REQUEST['cat']) ? intval($_REQUEST['cat']) : 0;
	$type=isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	
	$params='?pag='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat.'&type='.$type;

	//Verificamos que el software sea válido
	if ($id<=0){
		redirectMsg('./items.php'.$params,_AS_DT_ERR_ITEMVALID,1);
		die();
	}

	//Verificamos que el software exista
	if ($type=='edit'){
		$sw = new DTSoftwareEdited($id);
	}else{
		$sw=new DTSoftware($id);
	}
	if ($sw->isNew()){
		redirectMsg('./items.php'.$params,_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	if ($ok){

		if (!$util->validateToken()){
		
			redirectMsg('./items.php'.$params,_AS_DT_SESSINVALID, 1);
			die();
		
		}
		

		$secure=$sw->secure();
	
		if ($type=='edit'){
			$item = new DTSoftware($id);
			if ($item->image()!=$sw->image()){			
		
				@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$sw->image());
				@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$sw->image());
			}
		}
		if (!$sw->delete()){
			redirectMsg('./items.php'.$params,_AS_DT_DBERROR,1);
			die();
		}else{
			
			
			//Notificamos al usuario que su edición a sido rechazada
			if ($type=='edit'){

				$sw = new DTSoftware($id);

				$xu = new XoopsUser($sw->uid());
				$xoopsMailer =& getMailer();
				$xoopsMailer->usePM();
				$xoopsMailer->setTemplate('edit_downloaddelete.tpl');
				$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
				$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
				$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
				$xoopsMailer->assign('DOWNLOAD', $sw->name());
				$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
				$xoopsMailer->setToUsers($xu);
				$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
				$xoopsMailer->setFromName($xoopsConfig['sitename']);
				$xoopsMailer->setSubject(sprintf(_AS_DT_SUBJECTDEL,$sw->name()));
				if (!$xoopsMailer->send(true)){
					redirectMsg(XOOPS_URL.'/modules/dtransport/admin/items.php?type=edit',$xoopsMailer->getErrors(),1);
				}

				
			}

			if ($secure){
				redirectMsg('./items.php'.$params,_AS_DT_DBOK.sprintf(_AS_DT_DELSECURE,$xoopsModuleConfig['directory_secure']),0);
				die();
			}



			redirectMsg('./items.php'.$params,_AS_DT_DBOK,0);
			die();
			
		}


	}else{
		
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_DT_ITEMS);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$hiddens['op'] = 'delete';
		$hiddens['pag'] = $page;
		$hiddens['limit'] = $limit;
		$hiddens['cat'] = $cat;
		$hiddens['type'] = $type;

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'items.php'.$params.'\';"';
		
		$util->msgBox($hiddens, 'items.php', sprintf(_AS_DT_DELETECONF, $sw->name()). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	
	}


}

/**
* @desc Permite aprobar o no un elemento
**/
function dt_change_status($data, $value=0){
    global $xoopsSecurity;

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	$ids = rmc_server_var($_POST, 'ids', array());
	$page = rmc_server_var($_POST, 'page', 1);
    $limit = rmc_server_var($_POST, 'limit', 15);
	$search = rmc_server_var($_POST, 'search', '');
	$sort = rmc_server_var($_POST, 'sort', 'id_soft');
	$mode = rmc_server_var($_POST, 'mode', 0);
	$cat = rmc_server_var($_POST, 'cat', 0);

	$params='page='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat;

	if (!$xoopsSecurity->check()){
		redirectMsg('./items.php?'.$params, __('Session token expired!','dtransport'), RMMSG_ERROR);
		die();
	}

	//Verificamos si se proporciono algún elemento
	if (!is_array($ids) || empty($ids)){
		redirectMsg('./items.php?'.$params, __('You must select at least one item to modify!','dtransport'), RMMSG_WARN);
		die();
	}

	$sql = "UPDATE ".$db->prefix("dtrans_software")." SET $data=$value WHERE id_soft IN (".implode(",",$ids).")";

	if (!$db->queryF($sql)){
		redirectMsg('./items.php?'.$params,__('Errors ocurred while trying to update database!','dtransport').'<br />'.$errors, RMMSG_ERROR);
		die();
	}else{
		redirectMsg('./items.php?'.$params, __('Database updated successfully!','dtransport'), RMMSG_SUCCESS);
		die();
	}
	
}

$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		formItems();
	    break;
	case 'edit':
		formItems(1);
	    break;
	case 'delete':
		deleteItems();
	    break;
	case 'bulk_approve':
		dt_change_status('approve',1);
	    break;
	case 'bulk_unapproved':
		dt_change_status('approve',0);
	    break;
	case 'bulk_featured':
		dt_change_status('featured',1);
	    break;
    case 'bulk_unfeatured':
        dt_change_status('featured', 0);
        break;
	case 'bulk_daily':
		dt_change_status('daily', 1);
	    break;
    case 'bulk_undaily':
        dt_change_status('daily', 0);
        break;
    case 'bulk_secure':
        dt_change_status('secure',1);
        break;
    case 'bulk_nosecure':
        dt_change_status('secure', 0);
        break;
	default:
		showItems();
        break;
}
