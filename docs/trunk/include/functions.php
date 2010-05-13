<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

function makeHeader(){
	global $tpl,$xoopsModuleConfig,$xoopsUser;	
	
	$tpl->assign('lang_titleheader',$xoopsModuleConfig['title']);
	$tpl->assign('lang_resource',_MS_AH_RESOURCE);
	$tpl->assign('lang_find',_MS_AH_FIND);
	$tpl->assign('lang_findlabel',_MS_AH_FINDLABEL);
	//Crear publicación	
	if ($xoopsModuleConfig['createres']){
		$res=new AHResource();
		if ($res->isAllowedNew(($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS),$xoopsModuleConfig['create_groups'])){
			$tpl->assign('lang_new',_MS_AH_NEWRES);
		}
	}
	$tpl->assign('lang_voted',_MS_AH_VOTED);
	$tpl->assign('lang_popular',_MS_AH_POPULAR);
	$tpl->assign('lang_recent',_MS_AH_RECENT);
	
}

function makeFooter(){
	global $xoopsModule, $tpl;
	$util =& RMUtils::getInstance();
	$module = $util->getVersion(true, 'ahelp');
	$tpl->assign('ahelp_footer','Powered by <a href="'.formatURL($xoopsModule->getInfo('url')).'">'.$module.'</a>' .
			' | Development by <strong><a href="http://redmexico.com.mx">Red México</a></strong>.<br />
			Copyright &copy; 2007 - 2008 <strong><a href="http://www.redmexico.com.mx">Red México</a></strong>.');
}

/**
* @desc Genera el arbol de categorías en un array
* @param array Referencia del Array que se rellenará
* @param int Id de la Sección padre
* @param int Contador de sangría
*/
function getSectionTree(&$array, $parent = 0, $saltos = 0, $resource = 0, $fields='*', $exclude=0){
	global $db;
	$sql = "SELECT $fields FROM ".$db->prefix("pa_sections")." WHERE ".($resource>0 ? "id_res='$resource' AND" : '')."
			parent='$parent' ".($exclude>0 ? "AND id_sec<>'$exclude'" : '')." ORDER BY `order`";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$ret = array();
		$ret = $row;
		$ret['saltos'] = $saltos;
		$array[] = $ret;
		getSectionTree($array, $row['id_sec'], $saltos + 1, $resource, $fields, $exclude);
	}
	
	return true;
	
}

/**
* Assign vars to Smarty var, then this var can be used as index of the resource
* @param int Id of the section parent
* @param int Jumps (level)
* @param object Resource (owner)
* @param string Smarty var to append
* @param string Index number to add (eg. 1.1)
* @param bool Indicates if the array will be assigned to Smarty var or not
* @param array Reference to an array for fill.
* @return empty
*/
function assignSectionTree($parent = 0, $jumps = 0, AHResource $res, $var = 'index', $number='', $assign = true, &$array = null){
	global $tpl;
	$db = Database::getInstance();
	
	if (get_class($res)!='AHResource') return;
	
	$sql = "SELECT * FROM ".$db->prefix("pa_sections")." WHERE ".($res->id()>0 ? "id_res='".$res->id()."' AND" : '')."
			parent='$parent' ORDER BY `order`";
	$result = $db->query($sql);
	$sec = new AHSection();
	$i = 1; // Counter
	$num = 1;
	while ($row = $db->fetchArray($result)){
		$sec->assignVars($row);
		$link = ah_make_link($res->nameId().'/'.$sec->nameId());
		if ($assign){
			$tpl->append($var, array('title'=>$sec->title(),'nameid'=>$sec->nameId(), 'jump'=>$jumps,'link'=>$link, 'number'=>$jumps==0 ? $num : ($number !='' ? $number.'.' : '').$i));
		} else {
			$array[] = array('title'=>$sec->title(), 'nameid'=>$sec->nameId(), 'jump'=>$jumps,'link'=>$link, 'number'=>$jumps==0 ? $num : ($number !='' ? $number.'.' : '').$i);
		}
		assignSectionTree($sec->id(), $jumps+1, $res, $var, ($number !='' ? $number.'.' : '').$i, $assign, $array);
		$i++;
		if ($jumps==0) $num++;
	}
	
	return true;
}

/**
* @desc Obtiene el primer parent de la sección especificada
* @param int Id de la sección
*/
function getSuperParent($id){
	global $db;
	
	if ($id<=0) return;
	
	$sql = "SELECT id_sec, parent, nameid FROM ".$db->prefix("pa_sections")." WHERE id_sec='$id'";
	$result = $db->query($sql);
	if ($db->getRowsNum($result)<=0) return;
	list($id_sec, $parent, $nameid) = $db->fetchRow($result);
	if ($parent>0){
		$section = getSuperParent($parent);
	} else {
		$section['id'] = $id_sec;
		$section['parent'] = $parent;
		$section['nameid'] = $nameid;
	}
	
	return $section;
	
}

function ahBuildReference($id){
	global $xoopsModuleConfig, $tpl;
	
	$ref = new AHReference($id);
	if ($ref->isNew()) return;

	$ret = "<a name='top$id'></a><a href='javascript:;' ".(!$xoopsModuleConfig['refs_method'] ? "title='".$ref->title()."' " : " ");
	if ($xoopsModuleConfig['refs_method']){
		$ret .= "onclick=\"doReference(event,'$id');\"";
	} else {
		$ret .= "onclick=\"showReference($id,'$xoopsModuleConfig[refs_color]');\"";
		$tpl->append('references', array('id'=>$ref->id(),'text'=>$ref->reference()));
		$tpl->assign('have_refs', 1);
	}
	$ret .= "><img src='".XOOPS_URL."/modules/ahelp/images/reflink.png' align='textop' ".(!$xoopsModuleConfig['refs_method'] ? "alt='".$ref->title()."'" : "")." /></a>";
	
	return $ret;
}

function ahBuildFigure($id){
    
    $fig = new AHFigure($id);
    if ($fig->isNew()) return;
    
    $ret = "<div ";
    if ($fig->_class()!='') $ret .= "class='".$fig->_class()."' ";
    if ($fig->style()!='') $ret .= "style='".$fig->style()."' ";
    
    $ret .= $fig->figure();
    
    $ret .= "<div class='ahFigureFoot'>".$fig->desc()."</div></div>";
    
    return $ret;
    
}

/**
* @desc Función para crear las referencias del documento
*/
function ahParseReferences($text){
	
    // Parseamos las referencias
	$pattern = "/\[ref:(.*)]/esU";
	$replacement = "ahBuildReference(\\1)";
	$text = preg_replace($pattern, $replacement, $text);
    
    // Parseamos las figuras
    $pattern = "/\[fig:(.*)]/esU";
    $replacement = "ahBuildFigure(\\1)";
    $text = preg_replace($pattern, $replacement, $text);
    
	return $text;
	
}

/**
* @desc Incrementa las lecturas en un recurso si es posible
* @param object Objeto AHResource
* @return bool
*/
function addRead(AHResource &$res){
	
	if (!isset($_SESSION['ahResources'])){
		$_SESSION['ahResources'] = array($res->id());
		$res->addRead();
		return true;
	} else {
		
		if (in_array($res->id(), $_SESSION['ahResources'])) return false;
		
		$_SESSION['ahResources'][] = $res->id();
		$res->addRead();
		return true;
	}
		
}


/**
* @desc Envía correo de aprobación de publicación
* @param Object $res Publicación
**/
function mailApproved(&$res){
	global $xoopsModuleConfig,$xoopsConfig;

	$errors='';
	$user=new XoopsUser($res->owner());
	$member_handler =& xoops_gethandler('member');
	$xoopsMailer =& getMailer();
	$method=$user->getVar('notify_method');
	switch ($method){
		case '1':
			$xoopsMailer->usePM();
			$config_handler =& xoops_gethandler('config');
			$xoopsMailerConfig =& $config_handler->getConfigsByCat(XOOPS_CONF_MAILER);
			$xoopsMailer->setFromUser($member_handler->getUser($xoopsMailerConfig['fromuid']));
		
		break;
		case '2':
			$xoopsMailer->useMail();
		break;
	}
	$xoopsMailer->setTemplate('user_approv_resource.tpl');
	if ($xoopsModuleConfig['access']){
		$xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/ahelp/resource/".$res->id()."/".$res->nameId());
	}else{
		$xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/ahelp/resources.php?id=".$res->id());
	}
				
	$xoopsMailer->assign('NAME_RESOURCE',$res->title());
	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/ahelp/language/".$xoopsConfig['language']."/mail_template/");
	$xoopsMailer->setToUsers($user);
	$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
	$xoopsMailer->setFromName($xoopsConfig['sitename']);
	$xoopsMailer->setSubject(sprintf(_AS_AH_SUBJECT,$res->title()));
	if (!$xoopsMailer->send(true)){
		$errors.=$xoopsMailer->getErrors();
	}
		
	return $errors;


}

function ah_make_link($link=''){
    global $xoopsModuleConfig;
    
    $mc =& $xoopsModuleConfig;
    $url = $mc['access'] ? XOOPS_URL.$mc['htpath'].'/' : XOOPS_URL.'/modules/ahelp/index.php?page=';
    
    return $url.$link;
    
}
