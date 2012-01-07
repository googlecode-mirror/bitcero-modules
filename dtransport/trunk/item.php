<?php
// $Id: item.php 45 2008-06-03 02:16:18Z BitC3R0 $
// --------------------------------------------------------------
// D-Transport
// Módulo para la administración de descargas
// http://www.redmexico.com.mx
// http://www.exmsystem.com
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @copyright: 2007 - 2008 Red México

define('DT_LOCATION','item');
include '../../mainfile.php';

$myts =& MyTextSanitizer::getInstance();
$id = isset($_GET['id']) ? $myts->addSlashes($_GET['id']) : '';
$mc =& $xoopsModuleConfig;

if ($id==''){
	redirect_header(XOOPS_URL.'/modules/dtransport', 2, _MS_DT_ERRID);
	die();
}

// Parámetros
$params = explode('/', $id);
$id = $params[0];


$item = new DTSoftware($id);
if ($item->isNew() || !$item->approved()){
	redirect_header(XOOPS_URL.'/modules/dtransport', 2, _MS_DT_ERRID);
	die();
}

/**
* @desc Muestra todos los detalles de la descarga
*/
function showDetails(){
	global $id, $item, $tpl, $xoopsUser, $xoopsOption, $mc, $xoopsModuleConfig, $db;
	$xoopsOption['template_main'] = 'dtrans_item.html';
	$xoopsOption['module_subpage'] = 'item';

	include 'header.php';
	$tpl->assign('dtrans_option','details');
	
	DTFunctionsHandler::makeHeader();

	// Categoría del Elemento
	$category = new DTCategory($item->category());

	// Vinculos
	$link = DT_URL . ($mc['urlmode'] ? '/item/'.$item->nameId().'/' : '/item.php?id='.$item->id());

	// Datos del Elemento
	$data = array();
	$data['link'] = $link;
	$data['slink'] = $link.($mc['urlmode'] ? 'screens/' : '/screens/');
	$data['clink'] = $link.($mc['urlmode'] ? 'comments/' : '/comments/');
	$data['dlink'] = $link.($mc['urlmode'] ? 'download/' : '/download/');
	$data['flink'] = $link.($mc['urlmode'] ? 'features/' : '/features/');
	$data['llink'] = $link.($mc['urlmode'] ? 'logs/' : '/logs/');
	$data['name'] = $item->name();
	$data['version'] = $item->version();
	$data['image'] = XOOPS_UPLOAD_URL.'/dtransport/'.$item->image();
	$data['rating'] = @number_format($item->rating()/$item->votes(), 1);

	// Licencias
	$data['lic'] = '';
	foreach ($item->licences(true) as $lic){
		$data['lic'] .= $data['lic']=='' ? '<a href="'.$lic->link().'" target="_blank">'.$lic->name().'</a>' : ', <a href="'.$lic->link().'" target="_blank">'.$lic->name().'</a>';
	}
	//  Plataformas
	$data['os'] = '';
	foreach ($item->platforms(true) as $os){
		$data['os'] .= $data['os']=='' ? $os->name() : ', '.$os->name();
	}

	$data['register'] = formatTimestamp($item->created(), 's');
	$data['update'] = $item->modified()>0 ? formatTimestamp($item->modified(), 's') : '';
	$data['author'] = $item->author();
	$data['url'] = $item->url();
	$data['langs'] = $item->langs();
	$file =& $item->file();
	$data['size'] = formatBytesSize($file ? $file->size():0);
	$data['downs'] = $item->hits();
	$data['version'] = $item->version();
	$data['updated'] = $item->modified()>$item->created() && $item->modified()>(time()-($mc['update']*86400));
	$data['new'] = !$data['updated'] && $item->created()>(time()-($mc['new']*86400));
	$data['desc'] = $item->desc();
	$data['shortdesc'] = $item->shortdesc();

	$tpl->assign('item', $data);

	// Usuario
	$dtUser = new XoopsUser($item->uid());
	$tpl->assign('dtUser', array('id'=>$item->uid(),'uname'=>$item->uname(),'avatar'=>$dtUser->getVar('user_avatar')));
	$candownload = $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS);

	// Características
	$chars = $item->features(true);
	$tpl->assign('features_count', count($chars));
	$tpl->assign('lang_featuresnum', sprintf(count($chars)==1 ? _MS_DT_FEATURESOPS : _MS_DT_FEATURESOP, count($chars)));
	foreach ($chars as $feature){
		$updated = $feature->modified()>$feature->created() && $feature->modified()>(time()-($mc['update']*86400));
		$new = !$updated && $feature->created()>(time() - ($mc['new']*86400));
		$tpl->append('features', array('id'=>$feature->id(),'title'=>$feature->title(),
			'link'=>$link.($mc['urlmode'] ? '' : '/').'features/'.$feature->id(),'new'=>$new,'updated'=>$updated,
			'nameid'=>$feature->nameid()));
	}
	unset($chars);

	// Logs
	$logs = $item->logs(true);
	$tpl->assign('logs_count', count($logs));
	$tpl->assign('lang_logsnum', sprintf(_MS_DT_LOGSOP, count($logs)));
	foreach ($logs as $log){
		$tpl->append('logs', array('id'=>$log->id(),'title'=>$log->title(),
			'link'=>$link.($mc['urlmode'] ? '' : '/').'logs/'.$log->id(),'date'=>formatTimestamp($log->date(),'s')));
	}
	unset($logs);

	// Imágenes de la Descarga
	$imgs = $item->screens(true);
	$tpl->assign('screens_count', $item->screensCount());
	$tpl->assign('lang_screensnum', sprintf($item->screensCount()!=1 ? _MS_DT_SCREENSNUM : _MS_DT_SCREENSS, $item->screensCount()));
	foreach ($imgs as $img){
		$tpl->append('screens', array('id'=>$img->id(),'title'=>$img->title(),'image'=>$img->image()));
	}
	
	// Archivos Adicionales
	$groups = $item->fileGroups(true);
	$tpl->assign('groups_count', count($groups));
	foreach ($groups as $group){
		$files = $group->files(true);
		$rtnFiles = array();
		foreach ($files as $file){
			$rtn = array();
			$rtn['id'] = $file->id();
			$rtn['file'] = $file->title();
			$rtn['size'] = formatBytesSize($file->size());
			$rtn['downloads'] = $file->hits();
			$rtn['link'] = $candownload ? $data['dlink'].$file->id().'/'  : urlencode(str_replace(XOOPS_URL, '', $data['dlink'].$file->id().'/'));
			$rtn['date'] = formatTimestamp($file->date(),'string');
			$rtnFiles[] = $rtn;
		}
		$tpl->append('groups', array('id'=>$group->id(),'title'=>$group->name(), 'files'=>$rtnFiles, 
				'filescount'=>count($rtnFiles)));
	}
	
	// Archivos adicionales sin grupos
	$sql = "SELECT * FROM ".$db->prefix("dtrans_files")." WHERE id_soft='".$item->id()."' AND `group`='0'";
	$result = $db->query($sql);
	$tpl->assign('files_count', $db->getRowsNum($result));
	while ($row = $db->fetchArray($result)){
		$file = new DTFile();
		$file->assignVars($row);
		$rtn = array();
		$rtn['id'] = $file->id();
		$rtn['file'] = $file->title();
		$rtn['size'] = formatBytesSize($file->size());
		$rtn['downloads'] = $file->hits();
		$rtn['link'] = $data['dlink'].$file->id().'/';
		$rtn['date'] = formatTimestamp($file->date(),'string');
		$tpl->append('files', $rtn);
	}

	
	//Etiquetas
	$sql="SELECT b.* FROM ".$db->prefix('dtrans_softtag')." a INNER JOIN ".$db->prefix('dtrans_tags')." b ON (a.id_tag=b.id_tag) WHERE id_soft=".$item->id()." GROUP BY a.id_tag";
	$result = $db->query($sql);
	while ($row = $db->fetchArray($result)){
		$tag=new DTTag();
		$tag->assignVars($row);
		$rtn = array();
		$rtn['id']=$tag->id();
		$rtn['name']=$tag->tag();	
		$rtn['link']=XOOPS_URL.'/modules/dtransport/'.($mc['urlmode'] ? 'tag/'.$tag->tag() : 'tags.php?id='.$tag->tag());		
		$tpl->append('tags',$rtn);
	
	}
		
	//Programas relacionados
	$tpl->assign('related_active', $mc['active_relatsoft']);
	if ($mc['active_relatsoft']){
		$items_relation=array();
		//Obtenemos las etiquetas del elemento
		$tags=$item->tags();	
			
		$sql = "SELECT b.* FROM ".$db->prefix('dtrans_softtag')." a INNER JOIN ".$db->prefix('dtrans_software')." b ON (a.id_soft<>".$item->id()." AND a.id_soft=b.id_soft AND (";
		$sql1="";
		foreach ($tags as $k){
			$sql1.= ($sql1=="" ? " " : " OR ")."id_tag=$k";
		}
		$sql1.=")) GROUP BY b.id_soft ORDER BY RAND() LIMIT 0,".$mc['limit_relatsoft'];
		$result=$db->query($sql.$sql1);
		$tpl->assign('related_num', $db->getRowsNum($result));
		$i=0;
		while ($row = $db->fetchArray($result)){
			$items_rel=new DTSoftware();
			$items_rel->assignVars($row);
	
			$rtn = DTFunctionsHandler::createItemData($items_rel);	
			$tpl->append('relation_items', $rtn);	
			$items_relation[$i]=$items_rel->id();
			++$i;		
		}	
	
	}

	//Otros programas de la misma categoría
	$tpl->assign('incatego_active', $mc['active_othersw']);
	if ($mc['active_othersw']){
		
		$sql="SELECT * FROM ".$db->prefix('dtrans_software')." WHERE approved='1' AND id_cat=".$item->category()." AND id_soft<>";
		if ($mc['active_relatsoft']){
			foreach ($items_relation as $k){
				$sql.= " AND id_soft<>$k";
			}

		}
		$sql.=" LIMIT 0,".$mc['limit_othersw'];
		$result=$db->query($sql);
		$tpl->assign('incatego_num', $db->getRowsNum($result));
		while ($row = $db->fetchArray($result)){
			$items_other=new DTSoftware();
			$items_other->assignVars($row);

			$rtn = DTFunctionsHandler::createItemData($items_other);
			$tpl->append('others_items', $rtn);	

		}


	}

	//Calificar Descarga
	for ($i=1; $i<=10; ++$i){
		$tpl->append('votes',array('val'=>$i));
	}

	// Lenguaje
	$tpl->assign('lang_generald', _MS_DT_GENERALD);
	$tpl->assign('lang_comsnum', sprintf($item->comments()!=1 ? _MS_DT_COMSNUM : _MS_DT_COMSS, $item->comments()));
	$tpl->assign('lang_lic', _MS_DT_LIC);
	$tpl->assign('lang_register', _MS_DT_REGISTER);
	$tpl->assign('lang_updated', _MS_DT_UPDATED);
	$tpl->assign('lang_author', _MS_DT_AUTHOR);
	$tpl->assign('lang_lang', _MS_DT_LANG);
	$tpl->assign('lang_size', _MS_DT_SIZE);
	$tpl->assign('lang_downs', _MS_DT_DOWNSNUM);
	$tpl->assign('lang_version', _MS_DT_VERSION);
	$tpl->assign('can_download', $candownload);
	$tpl->assign('lang_download', _MS_DT_DWONNOW);
	$tpl->assign('lang_by',sprintf(_MS_DT_BY, $item->uname()));
	$tpl->assign('lang_os', _MS_DT_OS);
	$tpl->assign('lang_features', _MS_DT_FEATURES);
	$tpl->assign('lang_new', _MS_DT_NEW);
	$tpl->assign('lang_updateda', _MS_DT_UPDATEDA);
	$tpl->assign('lang_logs', _MS_DT_LOGS);
	$tpl->assign('lang_images', sprintf(_MS_DT_IMAGES, $item->name()));
	$tpl->assign('lang_comment', _MS_DT_OPINE);
	$tpl->assign('lang_files', _MS_DT_FILES);
	$tpl->assign('lang_file', _MS_DT_FILE);
	$tpl->assign('lang_fsize', _MS_DT_FSIZE);
	$tpl->assign('lang_fdowns', _MS_DT_FDOWNS);
	$tpl->assign('lang_date', _MS_DT_FDATE);
	$tpl->assign('lang_itemsrel',_MS_DT_ITEMSREL);
	$tpl->assign('lang_itemsother',sprintf(_MS_DT_ITEMSOTHER,$category->name()));
	$tpl->assign('lang_user',_MS_DT_USERS);
	$tpl->assign('lang_tags',_MS_DT_TAGS);
	$tpl->assign('lang_ratesite', $xoopsConfig['sitename']);
	$tpl->assign('width',($mc['size_ths']+20));
	$tpl->assign('lang_rateuser',_MS_DT_RATEUSER);
	$tpl->assign('lang_vote',_MS_DT_VOTE);
	$tpl->assign('link',$link);
	$tpl->assign('lang_votebutton',_MS_DT_VOTEBUTTON);
	$tpl->assign('id',$id);

	$tpl->assign('xoops_pagetitle', $item->name().($item->version()!='' ? " ".$item->version() : '')." &raquo; ".$xoopsModule->name());		

	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= DTFunctionsHandler::getCatLocation($category);
	$location .= " &raquo; <strong>".$item->name()."</strong>";
	$tpl->assign('dt_location', $location);

	// INclusión de Scripts JS
	$xmh .= '<script type="text/javascript" src="'.XOOPS_URL.'/include/prototype.js"></script>
	<script type="text/javascript" src="'.XOOPS_URL.'/include/scriptaculous.js?load=effects"></script>
	<script type="text/javascript">
		var lboxUrl = "'.DT_URL.'/include/";
	</script>
	<script type="text/javascript" src="'.DT_URL.'/include/js/lightbox.js"></script>
	<link rel="stylesheet" href="'.DT_URL.'/include/css/lightbox.css" type="text/css" media="screen" />';

	include 'footer.php';
	
}

/**
* @desc Mostramos las pantallas del elemento
*/
function showScreens(){
	global $tpl, $xoopsOption, $xoospModuleConfig, $mc, $xoopsUser, $item, $xoopsModule;

	$xoopsOption['template_main'] = "dtrans_item.html";
	$xoopsOption['module_subpage'] = 'screens';

	include 'header.php';
	$tpl->assign('dtrans_option','images');
	
	DTFunctionsHandler::makeHeader();
	
	$link = DT_URL.($mc['urlmode'] ? "/item/".$item->nameId().'/' : '/item.php?id='.$item->id());
	if ($item->screensCount()<=0){
		redirect_header($link, 2,  _MS_DT_NOIMGS);
		die();
	}
	
	// Categoría del Elemento
	$category = new DTCategory($item->category());
	
	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= DTFunctionsHandler::getCatLocation($category);
	$location .= " &raquo; <a href='$link'>".$item->name()."</a>";
	$location .= " &raquo; <strong>"._MS_DT_IMAGESLOC."</strong>";
	$tpl->assign('dt_location', $location);
	
	// Datos del Elemento
	$data = array();
	$data['link'] = $link;
	$data['slink'] = $link.($mc['urlmode'] ? 'screens/' : '/screens/');
	$data['clink'] = $link.($mc['urlmode'] ? 'comments/' : '/comments/');
	$data['dlink'] = $link.($mc['urlmode'] ? 'download/' : '/download/');
	$data['flink'] = $link.($mc['urlmode'] ? 'features/' : '/features/');
	$data['llink'] = $link.($mc['urlmode'] ? 'logs/' : '/logs/');
	$data['name'] = $item->name();
	$data['version'] = $item->version();
	$data['updated'] = $item->modified()>$item->created() && $item->modified()>(time()-($mc['update']*86400));
	$data['new'] = !$data['updated'] && $item->created()>(time()-($mc['new']*86400));
	
	$tpl->assign('item', $data);
	
	$tpl->assign('lang_generald', _MS_DT_GENERALD);
	// Imágenes de la Descarga
	$imgs = $item->screens(true);
	$tpl->assign('screens_count', $item->screensCount());
	$tpl->assign('lang_screensnum', sprintf($item->screensCount()!=1 ? _MS_DT_SCREENSNUM : _MS_DT_SCREENSS, $item->screensCount()));
	$tpl->assign('lang_comsnum', sprintf($item->comments()!=1 ? _MS_DT_COMSNUM : _MS_DT_COMSS, $item->comments()));
	$chars = $item->features(true);
	$tpl->assign('features_count', count($chars));
	$tpl->assign('lang_featuresnum', sprintf(count($chars)==1 ? _MS_DT_FEATURESOPS : _MS_DT_FEATURESOP, count($chars)));
	// Logs
	$logs = $item->logs(true);
	$tpl->assign('logs_count', count($logs));
	$tpl->assign('lang_logsnum', sprintf(_MS_DT_LOGSOP, count($logs)));
	$tpl->assign('lang_comment', _MS_DT_OPINE);
	$tpl->assign('xoops_pagetitle',$xoopsModule->name()." &raquo; "._MS_DT_IMAGESLOC);
	
	foreach ($imgs as $img){
		$tpl->append('screens', array('id'=>$img->id(),'title'=>$img->title(),'image'=>$img->image()));
	}
	
	// INclusión de Scripts JS
	$xmh .= '<script type="text/javascript" src="'.XOOPS_URL.'/include/prototype.js"></script>
	<script type="text/javascript" src="'.XOOPS_URL.'/include/scriptaculous.js?load=effects"></script>
	<script type="text/javascript">
		var lboxUrl = "'.DT_URL.'/include/";
	</script>
	<script type="text/javascript" src="'.DT_URL.'/include/js/lightbox.js"></script>
	<link rel="stylesheet" href="'.DT_URL.'/include/css/lightbox.css" type="text/css" media="screen" />';
	
	$tpl->assign('can_download', $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS));
	$tpl->assign('lang_download', _MS_DT_DWONNOW);
	
	include 'footer.php';
	
}

/**
* @desc Muestra las características del la descarga
* @param array Parámetros pasados a la página
*/
function showFeatures($params){
	global $tpl, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsOption, $item, $mc;
	
	$xoopsOption['template_main'] = "dtrans_item.html";
	$xoopsOption['module_subpage'] = 'features';

	include 'header.php';
	$tpl->assign('dtrans_option','features');
	
	DTFunctionsHandler::makeHeader();
	$link = DT_URL.($mc['urlmode'] ? "/item/".$item->nameId().'/' : '/item.php?id='.$item->id());
	if (count($item->features())<=0){
		redirect_header($link, 2, _MS_DT_NOFEATS);
		die();
	}
	
	// Categoría del Elemento
	$category = new DTCategory($item->category());
	
	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= DTFunctionsHandler::getCatLocation($category);
	$location .= " &raquo; <a href='$link'>".$item->name()."</a>";
	$location .= " &raquo; <strong>"._MS_DT_FEATSLOC."</strong>";
	$tpl->assign('dt_location', $location);
	
	// Datos del Elemento
	$data = array();
	$data['link'] = $link;
	$data['slink'] = $link.($mc['urlmode'] ? 'screens/' : '/screens/');
	$data['clink'] = $link.($mc['urlmode'] ? 'comments/' : '/comments/');
	$data['dlink'] = $link.($mc['urlmode'] ? 'download/' : '/download/');
	$data['flink'] = $link.($mc['urlmode'] ? 'features/' : '/features/');
	$data['llink'] = $link.($mc['urlmode'] ? 'logs/' : '/logs/');
	$data['name'] = $item->name();
	$data['version'] = $item->version();
	$data['updated'] = $item->modified()>$item->created() && $item->modified()>(time()-($mc['update']*86400));
	$data['new'] = !$data['updated'] && $item->created()>(time()-($mc['new']*86400));
	
	$tpl->assign('item', $data);
	
	$tpl->assign('lang_generald', _MS_DT_GENERALD);
	// Imágenes de la Descarga
	$imgs = $item->screens(true);
	$tpl->assign('screens_count', $item->screensCount());
	$tpl->assign('lang_screensnum', sprintf($item->screensCount()!=1 ? _MS_DT_SCREENSNUM : _MS_DT_SCREENSS, $item->screensCount()));
	$tpl->assign('lang_comsnum', sprintf($item->comments()!=1 ? _MS_DT_COMSNUM : _MS_DT_COMSS, $item->comments()));
	$chars = $item->features(true);
	$tpl->assign('features_count', count($chars));
	$tpl->assign('lang_featuresnum', sprintf(count($chars)==1 ? _MS_DT_FEATURESOPS : _MS_DT_FEATURESOP, count($chars)));
	// Logs
	$logs = $item->logs(true);
	$tpl->assign('logs_count', count($logs));
	$tpl->assign('lang_logsnum', sprintf(_MS_DT_LOGSOP, count($logs)));
	$tpl->assign('lang_comment', _MS_DT_OPINE);
	$tpl->assign('can_download', $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS));
	$tpl->assign('lang_download', _MS_DT_DWONNOW);
	$tpl->assign('lang_new', _MS_DT_NEW);
	$tpl->assign('lang_updateda', _MS_DT_UPDATEDA);
	$tpl->assign('lang_features', _MS_DT_FEATURES);
	
	// Característica Específica
	$idfeat = 0;
	if (isset($params[2]) && intval($params[2])>0){
		$idfeat = intval($params[2]);
		$tpl->assign('ok_feature', 1);
		$feature = new DTFeature(intval($params[2]));
		$tpl->assign('feature', array('id'=>$feature->id(),'title'=>$feature->title(),
			'content'=>$feature->content(),'nameid'=>$feature->nameid()));
	}
	
	// Características
	$chars = $item->features(true);
	foreach ($chars as $feature){
		$updated = $feature->modified()>$feature->created() && $feature->modified()>(time()-($mc['update']*86400));
		$new = !$updated && $feature->created()>(time() - ($mc['new']*86400));
		$tpl->append('features', array('id'=>$feature->id(),'title'=>$feature->title(),
			'link'=>$link.($mc['urlmode'] ? '' : '/').'features/'.$feature->id(),'new'=>$new,'updated'=>$updated,
			'nameid'=>$feature->nameid(),'current'=>$feature->id()==$idfeat));
	}
	unset($chars);	

	
	$tpl->assign('xoops_pagetitle', $xoopsModule->name()." &raquo; "._MS_DT_FEATSLOC);
	
	include 'footer.php';
	
}

/**
* @desc Muestra los registros de cambios
* @param array $params
*/
function showLogs($params){
	global $tpl, $xoopsUser, $xoopsModule, $mc, $xoopsOption, $item;
	
	$xoopsOption['template_main'] = "dtrans_item.html";
	$xoopsOption['module_subpage'] = 'logs';


	include 'header.php';
	$tpl->assign('dtrans_option','logs');
	
	DTFunctionsHandler::makeHeader();
	$link = DT_URL.($mc['urlmode'] ? "/item/".$item->nameId().'/' : '/item.php?id='.$item->id());
	if (count($item->logs())<=0){
		redirect_header($link, 2, _MS_DT_NOLOGS);
		die();
	}
	
	// Categoría del Elemento
	$category = new DTCategory($item->category());
	
	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= DTFunctionsHandler::getCatLocation($category);
	$location .= " &raquo; <a href='$link'>".$item->name()."</a>";
	$location .= " &raquo; <strong>"._MS_DT_LOGSLOC."</strong>";
	$tpl->assign('dt_location', $location);
	
	// Datos del Elemento
	$data = array();
	$data['link'] = $link;
	$data['slink'] = $link.($mc['urlmode'] ? 'screens/' : '/screens/');
	$data['clink'] = $link.($mc['urlmode'] ? 'comments/' : '/comments/');
	$data['dlink'] = $link.($mc['urlmode'] ? 'download/' : '/download/');
	$data['flink'] = $link.($mc['urlmode'] ? 'features/' : '/features/');
	$data['llink'] = $link.($mc['urlmode'] ? 'logs/' : '/logs/');
	$data['name'] = $item->name();
	$data['version'] = $item->version();
	$data['updated'] = $item->modified()>$item->created() && $item->modified()>(time()-($mc['update']*86400));
	$data['new'] = !$data['updated'] && $item->created()>(time()-($mc['new']*86400));
	
	$tpl->assign('item', $data);
	
	$tpl->assign('lang_generald', _MS_DT_GENERALD);
	// Imágenes de la Descarga
	$imgs = $item->screens(true);
	$tpl->assign('screens_count', $item->screensCount());
	$tpl->assign('lang_screensnum', sprintf($item->screensCount()!=1 ? _MS_DT_SCREENSNUM : _MS_DT_SCREENSS, $item->screensCount()));
	$tpl->assign('lang_comsnum', sprintf($item->comments()!=1 ? _MS_DT_COMSNUM : _MS_DT_COMSS, $item->comments()));
	$chars = $item->features(true);
	$tpl->assign('features_count', count($chars));
	$tpl->assign('lang_featuresnum', sprintf(count($chars)==1 ? _MS_DT_FEATURESOPS : _MS_DT_FEATURESOP, count($chars)));
	// Logs
	$logs = $item->logs(true);
	$tpl->assign('logs_count', count($logs));
	$tpl->assign('lang_logsnum', sprintf(_MS_DT_LOGSOP, count($logs)));
	$tpl->assign('lang_comment', _MS_DT_OPINE);
	$tpl->assign('can_download', $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS));
	$tpl->assign('lang_download', _MS_DT_DWONNOW);
	$tpl->assign('lang_new', _MS_DT_NEW);
	$tpl->assign('lang_updateda', _MS_DT_UPDATEDA);
	$tpl->assign('lang_logs', sprintf(_MS_DT_LOGSTITLE, $item->name()));
	$tpl->assign('lang_logsloc', _MS_DT_LOGSLOC);
	
	// Log Específico
	$idlog = 0;
	if (isset($params[2]) && intval($params[2])>0){
		$idlog = intval($params[2]);
		$tpl->assign('ok_log', 1);
		$log = new DTLog(intval($idlog));
		$tpl->assign('log', array('id'=>$log->id(),'title'=>$log->title(),'content'=>$log->log()));
	}
	
	// Logs
	foreach ($logs as $log){
		$tpl->append('logs', array('id'=>$log->id(),'title'=>$log->title(),
			'link'=>$link.($mc['urlmode'] ? '' : '/').'logs/'.$log->id(),'date'=>formatTimestamp($log->date(),'string'),'current'=>$log->id()==$idlog));
	}
	unset($logs);
	
	$tpl->assign('xoops_pagetitle', $xoopsModule->name()." &raquo; ".$item->name()." &raquo; "._MS_DT_LOGSLOC);
	
	include 'footer.php';
}

/**
* @desc Función para mostrar los comentarios
*/
function showComments(){
	global $item, $xoopsModule, $xoopsModuleConfig, $xoopsConfig, $tpl, $xoopsUser, $xoopsOption, $mc;
	
	$myts =& MyTextSanitizer::getInstance();
	$xoopsOption['template_main'] = "dtrans_item.html";
	$xoopsOption['module_subpage'] = 'comments';

	include 'header.php';
	$tpl->assign('dtrans_option','comments');
	
	DTFunctionsHandler::makeHeader();
	$link = DT_URL.($mc['urlmode'] ? "/item/".$item->nameId().'/' : '/item.php?id='.$item->id());
	
	// Categoría del Elemento
	$category = new DTCategory($item->category());
	
	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= DTFunctionsHandler::getCatLocation($category);
	$location .= " &raquo; <a href='$link'>".$item->name()."</a>";
	$location .= " &raquo; <strong>"._MS_DT_LOGSLOC."</strong>";
	$tpl->assign('dt_location', $location);
	
	// Datos del Elemento
	$data = array();
	$data['link'] = $link;
	$data['slink'] = $link.($mc['urlmode'] ? 'screens/' : '/screens/');
	$data['clink'] = $link.($mc['urlmode'] ? 'comments/' : '/comments/');
	$data['dlink'] = $link.($mc['urlmode'] ? 'download/' : '/download/');
	$data['flink'] = $link.($mc['urlmode'] ? 'features/' : '/features/');
	$data['llink'] = $link.($mc['urlmode'] ? 'logs/' : '/logs/');
	$data['name'] = $item->name();
	$data['version'] = $item->version();
	$data['updated'] = $item->modified()>$item->created() && $item->modified()>(time()-($mc['update']*86400));
	$data['new'] = !$data['updated'] && $item->created()>(time()-($mc['new']*86400));
	
	$tpl->assign('item', $data);
	
	$tpl->assign('lang_generald', _MS_DT_GENERALD);
	// Imágenes de la Descarga
	$imgs = $item->screens(true);
	$tpl->assign('screens_count', $item->screensCount());
	$tpl->assign('lang_screensnum', sprintf($item->screensCount()!=1 ? _MS_DT_SCREENSNUM : _MS_DT_SCREENSS, $item->screensCount()));
	$tpl->assign('lang_comsnum', sprintf($item->comments()!=1 ? _MS_DT_COMSNUM : _MS_DT_COMSS, $item->comments()));
	$chars = $item->features(true);
	$tpl->assign('features_count', count($chars));
	$tpl->assign('lang_featuresnum', sprintf(count($chars)==1 ? _MS_DT_FEATURESOPS : _MS_DT_FEATURESOP, count($chars)));
	// Logs
	$logs = $item->logs(true);
	$tpl->assign('logs_count', count($logs));
	$tpl->assign('lang_logsnum', sprintf(_MS_DT_LOGSOP, count($logs)));
	$tpl->assign('lang_comment', _MS_DT_OPINE);
	$tpl->assign('can_download', $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS));
	$tpl->assign('lang_download', _MS_DT_DWONNOW);
	$tpl->assign('lang_new', _MS_DT_NEW);
	$tpl->assign('lang_updateda', _MS_DT_UPDATEDA);
	$tpl->assign('lang_logs', sprintf(_MS_DT_LOGSTITLE, $item->name()));
	$tpl->assign('lang_logsloc', _MS_DT_LOGSLOC);
	
	$id = $item->id();
	
	include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';
	
	XoopsCommentHandler::renderNavbar($id, $item->name(), $link.($mc['urlmode'] ? 'comments/' : '/comments'), $link.($mc['urlmode'] ? 'comments/' : '/comments'), 'post');
	XoopsCommentHandler::getComments($id, $xoopsModule->mid(), true, $link.($mc['urlmode'] ? 'comments/' : '/comments'));
	
	include 'footer.php';
	
}

/**
* @desc Descargar archivos
*/
function downloadFiles($params){
	global $tpl, $xoopsModule, $mc, $xoopsUser, $xoopsOption, $item;
	
	$xoopsOption['template_main'] = "dtrans_download.html";
	$xoopsOption['module_subpage'] = 'download';

	include 'header.php';
	
	DTFunctionsHandler::makeHeader();
	$link = DT_URL.($mc['urlmode'] ? "/item/".$item->nameId().'/' : '/item.php?id='.$item->id());
	
	if (!$item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS)){
		redirect_header($link, 2, _MS_DT_NODOWN);
		die();
	}
	
	// Categoría del Elemento
	$category = new DTCategory($item->category());
	
	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
	$location .= DTFunctionsHandler::getCatLocation($category);
	$location .= " &raquo; <a href='$link'>".$item->name()."</a>";
	$location .= " &raquo; <strong>"._MS_DT_DWONNOW."</strong>";
	$tpl->assign('dt_location', $location);
	
	// Datos del Elemento
	$data = array();
	$data['link'] = $link;
	$data['slink'] = $link.($mc['urlmode'] ? 'screens/' : '/screens/');
	$data['clink'] = $link.($mc['urlmode'] ? 'comments/' : '/comments/');
	$data['dlink'] = $link.($mc['urlmode'] ? 'download/' : '/download/');
	$data['flink'] = $link.($mc['urlmode'] ? 'features/' : '/features/');
	$data['llink'] = $link.($mc['urlmode'] ? 'logs/' : '/logs/');
	$data['name'] = $item->name();
	$data['version'] = $item->version();
	$data['updated'] = $item->modified()>$item->created() && $item->modified()>(time()-($mc['update']*86400));
	$data['new'] = !$data['updated'] && $item->created()>(time()-($mc['new']*86400));
	
	$tpl->assign('item', $data);
	
	if (isset($params[2]) && $params[2]!=''){
		$idfile = $params[2];
		$file = new DTFile($idfile);
	} else {
		$file = $item->file();
		$idfile = $file->id();
	}
	
	if ($file->isNew() || $file->software()!=$item->id()){
		redirect_header($link, 2, _MS_DT_NOFILE);
		die();
	}
	
	$dlink = $mc['urlmode'] ? DT_URL.'/getfile/'.$idfile.'/' : DT_URL.'/getfile.php?id='.$idfile;
	$text = file_get_contents(DT_PATH.'/language/'.$xoopsConfig['language'].'/download.tpl');
	$text = str_replace('{DOWNLOAD_NAME}',$item->name(), $text);
	$text = str_replace('{DOWNLOAD_URL}',$dlink, $text);
	$tpl->assign('lang_thanks', $text);
	$tpl->assign('link', DT_URL.'/getfile/'.$idfile);

	$xmh .= '<meta http-equiv="refresh" content="5;URL='.$dlink.'" />';
	
	include 'footer.php';
	
}



if (isset($params[1]) && $params[1]!=''){
	switch($params[1]){
		case 'screens':
			showScreens();
			die();
		case 'features':
			showFeatures($params);
			die();
		case 'logs':
			showLogs($params);
			die();
		case 'comments':
			showComments();
			break;
		case 'download':
			downloadFiles($params);
			break;
		default:
			header('location: '.XOOPS_URL.'/modules/dtransport/item/'.$item->nameId().'/');
			die();
		
	}
} else {
	showDetails();
}

?>
