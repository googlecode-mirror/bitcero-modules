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
    define('RMCSUBLOCATION','items');
	global $xoopsModule, $xoopsSecurity;
	
	$search= rmc_server_var($_REQUEST,'search','');
	$sort = rmc_server_var($_REQUEST,'sort','id_soft');
	$mode = rmc_server_var($_REQUEST,'mode',0);
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
	$link = XOOPS_URL.'/modules/dtransport/';
    $items = array();
	while ($rows=$db->fetchArray($result)){
		if ($type=='edit'){
			$sw = new DTSoftwareEdited();
		}else{
			$sw = new DTSoftware();
		}
		$sw->assignVars($rows);		

		$slink = $mc['urlmode'] ? $link.'item/'.$sw->nameId().'/' : $link.'item.php?id='.$sw->id();
		$cat = new DTCategory($sw->category());

		$items = array(
            'id'=>($type=='edit' ? $sw->software() : $sw->id()),
            'name'=>$sw->name(),
            'screens'=>$sw->screensCount(),
		    'image'=>$sw->image(),
            'secure'=>$sw->secure(),
            'approved'=>$sw->approved(),
            'uname'=>$sw->uname(),
		    'date'=>($sw->created()<=$sw->modified() ? formatTimestamp($sw->modified(), 's') : formatTimestamp($sw->created(), 's')),
		    'link'=>$slink,
            'mark'=>$sw->mark(),
            'daily'=>$sw->daily(),
            'category'=>$cat->name());
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
    RMTemplate::get()->add_style('admin.css','dtransport');
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".$loc);
	xoops_cp_header();
    
    include RMTemplate::get()->get_template('admin/dtrans_items.php','module','dtransport');
    
	xoops_cp_footer();	
	
}


/**
* @desc Formulario de Elementos
**/
function formItems($edit=0){
	global $xoopsModule,$xoopsConfig,$xoopsModuleConfig, $rmc_config;
	
    define('RMCSUBLOCATION','newitem');
    
    // Get layout data
	$id     = intval(rmc_server_var($_REQUEST, 'id', 0));
	$page   = intval(rmc_server_var($_REQUEST, 'page', 0));
	$search = rmc_server_var($_REQUEST, 'search', '');
	$sort   = rmc_server_var($_REQUEST, 'sort', 'id_soft');
	$mode   = intval(rmc_server_var($_REQUEST, 'mode', 0));
	$catid  = intval(rmc_server_var($_REQUEST, 'car', 0));
	$type   = rmc_server_var($_REQUEST, 'type', '');

	$params='?page='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$catid.'&type='.$type;
    
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
    
    // CREACIÓN DEL FORMULARIO
    // -----------------------
	$form = new RMForm($location,'frmitem','items.php');
	$form->setExtra("enctype='multipart/form-data'");	

	if ($edit){
		$form->addElement(new RMFormText(_AS_DT_NAMEID,'nameid',50,150,$edit ? $sw->nameId() : ''));
	}
	  

	$ed = new RMFormEditor('','desc','99%','300px',$edit ? $sw->desc('e') : '',$xoopsConfig['editor_type']);

	$form->addElement(new RMFormFile(_AS_DT_IMAGE,'image', 45, $xoopsModuleConfig['image']*1024));

	if ($edit){
		$img = "<img src='".XOOPS_URL."/uploads/dtransport/ths/".$sw->image()."' border='0' />";
		$form->addElement(new RMLabel(_AS_DT_IMAGEACT,$img));	
	}
	
	$limits=new RMFormText(_AS_DT_LIMITS,'limits',5,10,$edit ? $sw->limits() : 0);	
	$form->addElement($limits,true);
	
	if ($edit){
		$form->addElement(new RMFormUserEXM(_AS_DT_USER, 'user', 0,array($sw->uid()), 50));
	}

	$form->addElement(new RMFormYesno(_AS_DT_SECURE,'secure',$edit ? $sw->secure() : 0));
	$form->addElement(new RMFormGroups(_AS_DT_GROUPS,'groups',1,1,5,$edit ? $sw->groups() : array(1,2)),true);
	$form->addElement(new RMFormYesNo(_AS_DT_APPROVED,'approved',$edit ? $sw->approved() : 1));
	$form->addElement(new RMFormYesNo(_AS_DT_MARK,'mark',$edit ? $sw->mark() : 1));	

	$ele=new RMFormSelect(_AS_DT_RATING,'siterate');
	$ele->addOption('','');
	for ($i = 1; $i <= 10; ++$i)
	{
		$ele->addOption($i,$i,$edit ? ($i==$sw->rate() ? 1 : 0) : 0);
	}
	$form->addElement($ele,true,'noselect:null');
	
	//Etiquetas
	if ($type=='edit'){
		$fields = $sw->fields();
		$tags = $fields['tags'];
	}else{
		if ($edit){
			$tags='';
			foreach ($sw->tags(true) as $tag){			
				$tags .= $tags=='' ? $tag->tag() : " ".$tag->tag();
			}
		}
	}

	$text = new RMFormText(_AS_DT_TAGS,'tags',50,255,$edit ? $tags : '');
	$text->setDescription(_AS_DT_DESCTAGS);
	$form->addElement($text,true);
	
	//Licencias
	$ele=new RMFormSelect(_AS_DT_LICENCES,'licences[]',1,$edit ? ($type=='edit' ? $fields['licences'] : ($sw->licences() ? $sw->licences() : array(0))) : array(0));	
	$ele->addOption('0', _AS_DT_LICOTHER);
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
	$sql="SELECT * FROM ".$db->prefix('dtrans_licences');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ele->addOption($rows['id_lic'],$rows['name']);
	}

	$form->addElement($ele,true);

	//Plataformas

	$ele=new RMFormSelect(_AS_DT_PLATFORMS,'platforms[]',1,$edit ? ($type=='edit' ? $fields['platforms'] : ($sw->platforms() ? $sw->platforms() : array(0))) : array(0));	
	$ele->addOption('0', _AS_DT_LICOTHER);
	$sql="SELECT * FROM ".$db->prefix('dtrans_platforms');
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ele->addOption($rows['id_platform'],$rows['name']);
	}

	$form->addElement($ele,true);
	
	// Autor e idioma
	$form->addElement(new RMFormSubTitle(_AS_DT_OTHER, 1, 'head'));
	$form->addElement(new RMFormText(_AS_DT_AUTHOR, 'author', 50, 150, $edit ? $sw->author() : ''));
	$form->addElement(new RMFormText(_AS_DT_AUTHORURL, 'url', 50, 255, $edit ? $sw->url() : ''));
	$form->addElement(new RMFormText(_AS_DT_LANGS, 'langs', 50, 255, $edit ? $sw->langs() : ''));

	//Alerta de software
	$form->addElement(new RMFormSubTitle(_AS_DT_ALERT, 1, 'head'));

	$edit ? $alert=$sw->alert() : '';
	$form->addElement(new RMFormYesNo(_AS_DT_ACTALERT,'alert',$edit ? ($type=='edit' ? ($fields['alert']['limit'] ? 1 : 0) : ($sw->alert() ? 1 : 0)) : 0));
	$ele2=new RMFormText(_AS_DT_LIMIT,'limitalert',5,10,$edit ? ($type=='edit' ? $fields['alert']['limit'] : $alert ? $alert->limit() : '') : 0);
	$ele2->setDescription(_AS_DT_DESCLIMIT);
	$form->addElement($ele2);

	$sel=new RMFormSelect(_AS_DT_MODE,'mode');
	$sel->addOption(0,_AS_DT_MP,$edit ? ($type=='edit' ? ($fields['alert']['mode']==0 ? 1 : 0) :  ($edit && $alert ? (!$alert->mode()==0 ? 1 : 0) : 0)) : 0);
	$sel->addOption(1,_AS_DT_EMAIL,$edit ? ($type=='edit' ? ($fields['alert']['mode']==1 ? 1 : 0) :  ($edit && $alert ? (!$alert->mode()==1 ? 1 : 0) : 1)) : 0);
	
	$form->addElement($sel);

	if ($type!='edit'){
		$ele=new RMFormCheck(_OPTIONS);
		if (!$edit){
			$ele->addOption(_AS_DT_NEWFILES,'options',1,0);
		}else{
			$ele->addOption(_AS_DT_NEWLOG,'options',2,0);
		}
	
		$form->addElement($ele);
	}


	$form->addElement(new RMFormHidden('op',$edit ? ($type=='edit' ? 'savewait' : 'saveedit') : 'save'));
	$form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('page',$page));
	$form->addElement(new RMFormHidden('limit',$limit));
	$form->addElement(new RMFormHidden('search',$search));
	$form->addElement(new RMFormHidden('sort',$sort));
	$form->addElement(new RMFormHidden('mode',$mode));
	$form->addElement(new RMFormHidden('cat',$catid));
	$form->addElement(new RMFormHidden('type',$type));

	$buttons =new RMFormButtonGroup();
	
	if ($type=='edit'){
		$buttons->addButton('sbt',_AS_DT_ACCEPT,'submit');
		$buttons->addButton('delete',_AS_DT_DELETE,'button','onclick="document.forms[\'frmitem\'].elements[\'op\'].value=\'delete\'; submit();"');
	}else{
		$buttons->addButton('sbt',_SUBMIT,'submit');
	}
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'items.php'.$params.'\';"');

	$form->addElement($buttons);
	// Obtenemos todos los campos en una cariable
    //$the_form = $form->renderForTemplate();

    //-------------------
    // FIN DEL FORMULARIO
    
    RMTemplate::get()->add_style('admin.css','dtransport');
    RMTemplate::get()->add_style('items.css','dtransport');
    DTFunctions::toolbar();
    xoops_cp_location($location);
    xoops_cp_header();
    $form->display();
    include RMTemplate::get()->get_template('admin/dtrans_formitems.php','module','dtransport');
    
	xoops_cp_footer();

}

/**
* @desc Almacena el elemento en la base de datos
**/
function saveItems($edit=0){
	global $xoopsUser,$xoopsModuleConfig,$util,$db,$xoopsConfig, $myts;
	$ids=array();
	$platforms = array();
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$params='pag='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat.'&type='.$type;

	if (!$util->validateToken()){
		if (!$edit){
			redirectMsg('./items.php?op=new&'.$params,_AS_DT_SESSINVALID, 1);
			die();
		}else{
			redirectMsg('./items.php?op=edit&'.$params,_AS_DT_SESSINVALID, 1);
			die();
		}
	}

	if ($edit){
		//Verificamos que el software sea válido
		if ($id<=0){
			redirectMsg('./items.php?'.$params,_AS_DT_ERR_ITEMVALID,1);
			die();
		}

		//Verificamos que el software exista
		$sw=new DTSoftware($id);
		if ($sw->isNew()){
			redirectMsg('./items.php?'.$params,_AS_DT_ERR_ITEMEXIST,1);
			die();
		}

		//Comprueba que el título del elemento no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_software')." WHERE name='$name' AND id_soft<>'".$id."'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./items.php?op=edit&id='.$id.'&.'.$params,_AS_DT_ERRNAME,1);	
			die();
		}
		
		if ($nameid){

			$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_software')." WHERE nameid='$nameid' AND id_soft<>'".$id."'";
			list($num)=$db->fetchRow($db->queryF($sql));
			if ($num>0){
				redirectMsg('./items.php?op=edit&id='.$id.'&.'.$params,_AS_DT_ERRNAMEID,1);	
				die();
			}

		}

	}else{
		//Comprueba que el título del elemento no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_software')." WHERE name='$name'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./items.php?op=new&'.$params,_AS_DT_ERRNAME,1);	
			die();
		}
		$sw=new DTSoftware();
	}

		

	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	if ($name!=$sw->name() || empty($nameid)){
		do{
			$nameid = $util->sweetstring($name).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_software'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
		}while ($found==true);
	}
	
	$sw->setName($name);
	$sw->setShortDesc($myts->displayTarea($shortdesc, 0, 0, 0, 0, 1));
	$sw->setDesc($desc);
	$sw->setLimits($limits);
	if ($sw->isNew()){
		$sw->setUid($xoopsUser->uid());
		$sw->setUname($xoopsUser->uname());
		$sw->setModified(time());
		$sw->setCreated(time());
	}else{
		$user=new XoopsUser($user);
		$sw->setUid($user->uid());
		$sw->setUname($user->uname());
		$sw->setModified(time());
	}
	$sw->setSecure($secure);
	$sw->setGroups($groups);
	$type=='edit' ? $sw->setApproved(1) : $sw->setApproved($approved);
	$sw->setNameId($nameid);
	$sw->setMark($mark);
	$sw->setCategory($category);
	$sw->setRate($siterate);
	$sw->setVersion($version);
	$sw->setAuthor($author);
	$sw->setUrl(formatUrl($url));
	$sw->setLangs($langs);
	$sw->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$sw->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$sw->setVar('dobr', isset($dobr) ? 1 : 0);
	$sw->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$sw->setVar('doimage', isset($doimage) ? 1 : 0);

	$tgs=explode(" ",$tags);
	if (count($tgs)>$xoopsModuleConfig['limit_tags']){
		$tgs=array_slice($tgs,0,$xoopsModuleConfig['limit_tags']);
	}	

	foreach ($tgs as $k){
		$v=trim($k);
		if ($v=="" || (strlen($v)<$xoopsModuleConfig['caracter_tags'])){
			continue;
		}
		$tag = new DTTag($v);
		if (!$tag->isNew()){
			$ids[]=$tag->id();
			continue;
		}		
		
		$tag->setTag($v);
		$tag->save();
		$ids[]=$tag->id();
	}	
	$sw->setTags($ids);
	
	//Alerta
	$sw->createAlert($alert);
	$sw->setLimit($limitalert);
	$sw->setMode($mode);

	//Licencias
	$sw->setLicences($licences);
	
	//Plataformas
	$sw->setPlatforms($platforms);

	//Imagen
	include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
	$up = new RMUploader(true);
	$folder = XOOPS_UPLOAD_PATH.'/dtransport/';
	$folderths = XOOPS_UPLOAD_PATH.'/dtransport/ths';
	if ($edit){
		if ($type=='edit'){
			$swedit = new DTSoftwareEdited($id);
			if ($swedit->image()==$sw->image()){
				$filename=$sw->image();	
			}else{
				$filename=$swedit->image();
				@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$sw->image());
				@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$sw->image());	
				
			}
		}else{
			$filename=$sw->image();
		}
	}
	else{
		$filename = '';
	}
	
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $xoopsModuleConfig['image']*1024);//tamaño
	
	if ($up->fetchMedia('image')){

	
		if (!$up->upload()){
			if ($sw->isNew()){
				redirectMsg('./items.php?op=new&'.$params,$up->getErrors(), 1);
				die();
			}else{
				redirectMsg('./items.php?op=edit&'.$params,$up->getErrors(), 1);
				die();
			}
		}
					
		if ($edit && $sw->image()!=''){
			@unlink(XOOPS_UPLOAD_PATH.'/dtransport/'.$sw->image());
			@unlink(XOOPS_UPLOAD_PATH.'/dtransport/ths/'.$sw->image());
		}

		$filename = $up->getSavedFileName();
		$fullpath = $up->getSavedDestination();
		// Redimensionamos la imagen
		$redim = new RMImageControl($fullpath, $fullpath);
		switch ($xoopsModuleConfig['redim_image']){
			case 0:
				//Recortar miniatura
				$redim->resizeWidth($xoopsModuleConfig['size_image']);
				$redim->setTargetFile($folderths."/$filename");				
				$redim->resizeAndCrop($xoopsModuleConfig['size_ths'],$xoopsModuleConfig['size_ths']);
				
			break;	
			case 1: 
				//Recortar imagen grande
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($xoopsModuleConfig['size_ths']);
				$redim->setTargetFile($fullpath);
				$redim->resizeAndCrop($xoopsModuleConfig['size_image'],$xoopsModuleConfig['size_image']);				
			break;
			case 2:
				//Recortar ambas
				$redim->resizeAndCrop($xoopsModuleConfig['size_image'],$xoopsModuleConfig['size_image']);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeAndCrop($xoopsModuleConfig['size_ths'],$xoopsModuleConfig['size_ths']);
			break;
			case 3:
				//Redimensionar
				$redim->resizeWidth($xoopsModuleConfig['size_image']);
				$redim->setTargetFile($folderths."/$filename");
				$redim->resizeWidth($xoopsModuleConfig['size_ths']);
			break;			
		}

	}

	$sw->setImage($filename);

	if (!$sw->save(true, $alert, true, true)){
		redirectMsg('./items.php',_AS_DT_DBERROR."<br />".$sw->errors(),1);		
		die();
	}else{
		
		//Notificamos al usuario que su edición a sido aceptada
		if ($type=='edit'){

			$xu = new XoopsUser($sw->uid());
			$xoopsMailer =& getMailer();
			$xoopsMailer->usePM();
			$xoopsMailer->setTemplate('edit_downloadaccept.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
			$xoopsMailer->assign('DOWNLOAD', $sw->name());
			$xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/dtransport/submit.php?op=edit&id=".$sw->id());
			$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
			$xoopsMailer->setToUsers($xu);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_AS_DT_SUBJECT,$sw->name()));
			if (!$xoopsMailer->send(true)){
				redirectMsg(XOOPS_URL.'/modules/dtransport/admin/items.php?type=edit',$xoopsMailer->getErrors(),1);
			}


		}
	
		$sw = new DTSoftwareEdited($id);
		$sw->delete();

		

		if ($options==1){
			redirectMsg('./files.php?item='.$sw->id(),_AS_DT_DBOK,0);
			die();
		}
		if ($options==2){
			redirectMsg('./logs.php?item='.$sw->id(),_AS_DT_DBOK,0);
			die();

		}
		
		//Buscamos la página donde se encuentra el software que almacenamos
		$sql="SELECT id_soft FROM ".$db->prefix('dtrans_software')." ORDER BY $sort".($mode ? " DESC " : " ASC ");
		$result=$db->query($sql);
		$num = $db->getRowsNum($result);
		$i=1;
		while ($rows=$db->fetchArray($result)){
			if ($rows['id_soft']==$sw->id()){
				$page=ceil($i / $limit);
				break;
			}
			$i++;	
		}

		
		redirectMsg('./items.php?pag='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat,_AS_DT_DBOK,0);
		die();
	}


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
function approvedItems($app=0){
	global $util,$db;

	$items = isset($_REQUEST['items']) ? $_REQUEST['items'] : array();
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$sort=isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_soft';
	$mode=isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$cat=isset($_REQUEST['cat']) ? intval($_REQUEST['cat']) : 0;

	$params='pag='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat;

	if (!$util->validateToken()){
		redirectMsg('./items.php?'.$params,_AS_DT_SESSINVALID, 1);
		die();
	}

	//Verificamos si se proporciono algún elemento
	if (!is_array($items) || empty($items)){
		redirectMsg('./items.php?'.$params,_AS_DT_NOTID,1);
		die();
	}

	$errors='';
	foreach ($items as $k){
		
		//Verificamos si software es válido
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRNOTVALID,$k);
			continue;
		}		

		//Verificamos si software existe
		$sw=new DTSoftware($k);
		if ($sw->isNew()){
			$errors.=sprintf(_AS_DT_ERRNOTEXIST,$k);
			continue;
		}
		
		$sql="UPDATE ".$db->prefix('dtrans_software')." SET approved=$app WHERE id_soft=$k";
		$result=$db->queryF($sql);		
		if (!$result){
			$errors.=sprintf(_AS_DT_ERRNOTSAVE,$k);
		}

	}

	if ($errors!=''){
		redirectMsg('./items.php?'.$params,_AS_DT_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./items.php?'.$params,_AS_DT_DBOK,0);
		die();
	}
	
}

/**
* @desc Marca una descarga como destacada
**/
function markItems(){

	$items=isset($_REQUEST['items']) ? $_REQUEST['items'] : null;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    	$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$sort=isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_soft';
	$mode=isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;	
	$cat=isset($_REQUEST['cat']) ? intval($_REQUEST['cat']) : 0;

	$params='pag='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat;


	//Verificamos si se proporcionó algun software
	if (!is_array($items) || empty($items)){
		redirectMsg('./items.php?'.$params,_AS_DT_NOTID,1);
		die();
	}

	$errors='';
	foreach ($items as $k){
		
		//Verificamos si software es válido
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRNOTVALID,$k);
			continue;
		}		

		//Verificamos si software existe
		$sw=new DTSoftware($k);
		if ($sw->isNew()){
			$errors.=sprintf(_AS_DT_ERRNOTEXIST,$k);
			continue;
		}
		
		$sw->setMark(!$sw->mark());
		if (!$sw->save()){
			$errors.=sprintf(_AS_DT_ERRNOTSAVE,$k);
		}

	}


	if ($errors!=''){
		redirectMsg('./items.php?'.$params,_AS_DT_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./items.php?'.$params,_AS_DT_DBOK,0);
		die();
	}
	


}

/**
* @desc Marca una descarga como diaria
**/
function dailyItems(){

	$items=isset($_REQUEST['items']) ? $_REQUEST['items'] : null;
	$page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	$sort=isset($_REQUEST['sort']) ? $_REQUEST['sort'] : 'id_soft';
	$mode=isset($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
	$cat=isset($_REQUEST['cat']) ? intval($_REQUEST['cat']) : 0;

	$params='pag='.$page.'&limit='.$limit.'&search='.$search.'&sort='.$sort.'&mode='.$mode.'&cat='.$cat;


	//Verificamos si se proporcionó algun software
	if (!is_array($items) || empty($items)){
		redirectMsg('./items.php?'.$params,_AS_DT_NOTID,1);
		die();
	}

	$errors='';
	foreach ($items as $k){
		
		//Verificamos si software es válido
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRNOTVALID,$k);
			continue;
		}		

		//Verificamos si software existe
		$sw=new DTSoftware($k);
		if ($sw->isNew()){
			$errors.=sprintf(_AS_DT_ERRNOTEXIST,$k);
			continue;
		}
		
		$sw->setDaily(!$sw->daily());
		if (!$sw->save()){
			$errors.= $sw->errors()."<br />";
		}

	}


	if ($errors!=''){
		redirectMsg('./items.php?'.$params,_AS_DT_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./items.php?'.$params,_AS_DT_DBOK,0);
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
	case 'save':
		saveItems();
	break;
	case 'saveedit':
		saveItems(1);
	break;
	case 'savewait':
		saveItems(1);
	break;
	case 'delete':
		deleteItems();
	break;
	case 'approve':
		approvedItems(1);
	break;
	case 'noapprove':
		approvedItems();
	break;
	case 'mark':
		markItems();
	break;
	case 'daily':
		dailyItems();
	break;
	default:
		showItems();

}
