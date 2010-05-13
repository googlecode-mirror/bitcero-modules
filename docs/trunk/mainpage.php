<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION','index');
include ('../../mainfile.php');
$xoopsOption['template_main']='ahelp_index.html';
$xoopsOption['module_subpage'] = 'index';
include ('header.php');

//Determinamos el tipo de orden que tendrá
switch ($xoopsModuleConfig['text_type']){
	case 0:
		//Recientes
		$order = "created";
		$title = _MS_AH_RECENT;
		$mode = 3;
	break;
	case 1:
		//Populares
		$order = "`reads`";
		$title = _MS_AH_POPULAR;
		$mode = 2;
	break;
	case 2:
		//Mejor Votadas
		$order = 'rating/votes';
		$title = _MS_AH_VOTES;
		$mode = 1;
	break;
	


}

//Lista de publicaciones
$sql="SELECT * FROM ".$db->prefix('pa_resources')." WHERE public=1 AND approved=1 ORDER BY $order DESC LIMIT 0,".$xoopsModuleConfig['public_limit'];
$result=$db->queryF($sql);
$i=0;
$cache = array();
while ($rows=$db->fetchArray($result)){

	$res=new AHResource();
	$res->assignVars($rows);
	if ($xoopsModuleConfig['access']==2) $id=$res->nameId(); else $id=$res->id();
	
	$reads=sprintf(_MS_AH_READS,$res->reads());
	$rating = @intval($res->rating()/$res->votes());
	$votes=sprintf(_MS_AH_VOTE,$res->votes());	
	$by=sprintf(_MS_AH_BY,$res->owname());
	$url_link=XOOPS_URL."/userinfo.php?uid=".$res->owner();

	$tpl->append('resources',array('id'=>$id,'title'=>$res->title(),'desc'=>$util->filterTags($res->desc()),'num'=>$i,'image'=>$res->image(),'created'=>date($xoopsConfig['datestring'],$res->created()),
	'reads'=>$reads,'modified'=>date($xoopsConfig['datestring'],$res->modified()),'votes'=>$votes,'rating'=>$rating,'by'=>$by,'url_link'=>$url_link,
	'link'=>ah_make_link($res->nameId())));
	$i++;
	$cache[$res->id()] = $res;

}

// Featured resources
$sql="SELECT * FROM ".$db->prefix('pa_resources')." WHERE public=1 AND approved=1 AND featured=1 ORDER BY RAND() LIMIT 0,".$xoopsModuleConfig['recommend_limit'];
$result = $db->query($sql);
$featured = array();
while($row = $db->fetchArray($result)){
	$featured[] = $row;
	$featured[count($featured)-1]['type'] = 'res';
}

// Featured pages
$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE featured=1 ORDER BY RAND() LIMIT 0,".$xoopsModuleConfig['recommend_limit'];
$result = $db->query($sql);
while($row = $db->fetchArray($result)){
	$featured[] = $row;
	$featured[count($featured)-1]['type'] = 'page';
}

$i=0;
$mc['recommend_limit'] = count($featured)>$mc['recommend_limit'] ? $mc['recommend_limit'] : count($featured);
for ($i=0;$i<$mc['recommend_limit'];$i++){
	$count = count($featured);
	$in = rand(0,$count>1 ? $count-1 : $count);
	$v = $featured[$in];
	array_splice($featured, $in, 1);
	switch ($v['type']){
		case 'res':
			$item=new AHResource($v['id_res']);
			$type=1; $desc=$item->desc();
			$image=$item->image();
			$id_res=$item->owner();
			$reads=sprintf(_MS_AH_READS,$item->reads());
			$rating = @intval($item->rating()/$item->votes());
			$votes=sprintf(_MS_AH_VOTE,$item->votes());
			$url_link=XOOPS_URL."/userinfo.php?uid=".$item->owner();
			$by=sprintf(_MS_AH_BY,$item->owname());
			//Generamos el link
            $url = ah_make_link($item->nameId());
			
			break;
		case 'page':
        default:
			$item=new AHSection($v['id_sec']);
			$type=2;
			
			$text = substr($item->content(), 0, 300).' [...]';
			$pattern = "/\[ref:(.*)]/esU";
			$replacement = "";
			$text = preg_replace($pattern, $replacement, $text);
		    $pattern = "/\[fig:(.*)]/esU";
		    $replacement = "";
		    $text = preg_replace($pattern, $replacement, $text);
			$desc= $text;
			
			$image=0;
			$res=new AHResource($item->resource());
			$id_res=$res->id();
			$reads=sprintf(_MS_AH_READS,$res->reads());
			$by = sprintf(_MS_AH_IN,$res->title());
			$rating=@intval($res->rating()/$res->votes());
			$votes=sprintf(_MS_AH_VOTE,$res->votes());
			//Generamos el link
            $url_link=ah_make_link($res->nameId());
            $url = ah_make_link($res->nameId().'/'.$item->nameId());
			
			break;
		
	}
	
	$tpl->append('recommends',array('id'=>$id,'title'=>$item->title(),'image'=>$image,'num'=>$i,'url'=>$url,'reads'=>$reads,
	'votes'=>$votes,'desc'=>$util->filterTags($desc),'type'=>$type,'rating'=>$rating,
	'url_link'=>$url_link,'id_res'=>$id_res,'by'=>$by));

}

//Modificaciones Recientes
$sql="SELECT * FROM ".$db->prefix('pa_sections')." ORDER BY modified DESC LIMIT 0,".$xoopsModuleConfig['modified_limit'];
$result=$db->queryF($sql);
$i=0;
$sec=new AHSection();
while ($row=$db->fetchArray($result)){
	$sec->assignVars($row);
	if (isset($cache[$sec->resource()])){
		$res = $cache[$sec->resource()];
	} else {
		$res = new AHResource($sec->resource());
		$cache[$res->id()] = $res;
	}
	if ($xoopsModuleConfig['access']==2) $id=$sec->nameId(); else $id=$sec->id();
	
	$by=sprintf(_MS_AH_BY,$sec->uname());
	$url_link=XOOPS_URL."/userinfo.php?uid=".$sec->uid();
	$text = substr($sec->content(), 0, 300).' [...]';
	$pattern = "/\[ref:(.*)]/esU";
	$replacement = "";
	$text = preg_replace($pattern, $replacement, $text);
	$pattern = "/\[fig:(.*)]/esU";
	$replacement = "";
	$text = preg_replace($pattern, $replacement, $text);
	$tpl->append('modifieds',array('id'=>$id,'title'=>$sec->title(),'desc'=>$util->filterTags($text),'num'=>$i,
	'created'=>date($xoopsConfig['datestring'],$sec->created()),
	'modified'=>date($xoopsConfig['datestring'],$sec->modified()),'by'=>$by,'url_link'=>$url_link,
	'url'=>ah_make_link($res->nameId().'/'.$sec->nameId())));
	$i++;
	

}

$tpl->assign('lang_all',_MS_AH_ALL);
$tpl->assign('lang_title',$title);
$tpl->assign('lang_indexpub',_MS_AH_INDEXPUB);
$tpl->assign('lang_readings',_MS_AH_READINGS);
$tpl->assign('lang_modifieds',_MS_AH_MODIFIEDS);
$tpl->assign('access',$xoopsModuleConfig['access']);
$tpl->assign('url',XOOPS_URL.'/modules/ahelp');
$tpl->assign('mode',$mode);
$tpl->assign('lang_rating',_MS_AH_RATING);
$tpl->assign('home_text', $xoopsModuleConfig['homepage']);

include_once 'include/functions.php';
makeHeader();
makeFooter();

include ('footer.php');
