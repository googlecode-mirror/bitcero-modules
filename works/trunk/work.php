<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'pw_work.html';
$xoopsOption['module_subpage'] = 'work';
include 'header.php';

$mc =& $xoopsModuleConfig;

if ($id==''){
	redirect_header(PW_URL.'/', 2, __('Work id not provided!','works'));
	die();
}

//Verificamos si el trabajo existe
$work = new PWWork($id);
if($work->isNew()){
	redirect_header(PW_URL.'/', 2, __('Specified id does not exists!','works'));
	die();
}

$cat = new PWCategory($work->category());
$client = new PWClient($work->client());

$link_cat = PW_URL.($mc['urlmode'] ? '/category/'.$cat->nameId().'/' : '/catego.php?id='.$cat->nameId());

$tpl->assign('work',array('id'=>$work->id(),'title'=>$work->title(),'desc'=>$work->desc(),
	     'catego'=>$cat->name(),'client'=>$client->businessName(),'site'=>$work->nameSite(),'url'=>$work->url(),
	     'created'=>formatTimeStamp($work->created(),'s'),'start'=>formatTimeStamp($work->start(),'s'),
	     'period'=>$work->period(),'cost'=>$mc['cost'] ? sprintf($mc['format_currency'],number_format($work->cost(),2)) : '',
	     'mark'=>$work->mark(),'image'=>XOOPS_UPLOAD_URL.'/works/'.$work->image(),'linkcat'=>$link_cat,
	     'comment'=>$work->comment(),'rating'=>PWFunctions::rating($work->rating()),'views'=>$work->views()));

$work->addView();

//Obtenemos todas las imágenes del trabajo
$sql = "SELECT * FROM ".$db->prefix('pw_images')." WHERE work=".$work->id();
$result = $db->query($sql);
while($row = $db->fetchArray($result)){
	$img = new PWImage();
	$img->assignVars($row);

	$tpl->append('images',array('id'=>$img->id(),'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$img->image(),
	'title'=>$img->title(),'desc'=>$img->desc(),'link_image'=>XOOPS_UPLOAD_URL.'/works/'.$img->image()));
}

$tpl->assign('xoops_pagetitle', $work->title().' &raquo; '.$mc['title']);

/**
* Otros trabajos
**/
if ($mc['other_works']){ //Trabajos destacados
	$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=1 AND mark=1 AND id_work<>'".$work->id()."' ORDER BY RAND() LIMIT 0,".$mc['num_otherworks'];
}else{ //Misma categoría
	$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=1 AND catego=".$work->category()." AND id_work<>'".$work->id()."' ORDER BY RAND() LIMIT 0,".$mc['num_otherworks'];
}
$result = $db->query($sql);
$categos = array();
$clients = array();
while ($row = $db->fetchArray($result)){
	$wk = new PWWork();
	$wk->assignVars($row);

	if (!isset($categos[$wk->category()])) $categos[$wk->category()] = new PWCategory($wk->category());

	if (!isset($clients[$wk->client()])) $clients[$wk->client()] = new PWClient($wk->client());

	$link = PW_URL.($mc['urlmode'] ? '/work/'.$wk->id().'/' : '/work.php?id='.$wk->id());
	$link_cat = PW_URL.($mc['urlmode'] ? '/cat/'.$categos[$wk->category()]->nameId().'/' : '/catego.php?id='.$categos[$wk->category()]->nameId());

	$tpl->append('works',array('id'=>$wk->id(),'title'=>$wk->title(),'desc'=>$wk->descShort(),'linkcat'=>$link_cat,
	'catego'=>$categos[$wk->category()]->name(),'client'=>$clients[$wk->client()]->name(),'link'=>$link,
	'created'=>formatTimeStamp($wk->created(),'s'),'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$wk->image(),'views'=>$wk->views()));
}



$tpl->assign('lang_desc',_MS_PW_DESC);
$tpl->assign('lang_catego',_MS_PW_CATEGO);
$tpl->assign('lang_client',_MS_PW_CLIENT);
$tpl->assign('lang_start',_MS_PW_START);
$tpl->assign('lang_period',_MS_PW_PERIOD);
$tpl->assign('lang_comment',_MS_PW_COMMENT);
$tpl->assign('lang_cost',_MS_PW_COST);
$tpl->assign('lang_others',_MS_PW_OTHERS);
$tpl->assign('lang_date',_MS_PW_DATE);
$tpl->assign('lang_images',_MS_PW_IMAGES);
$tpl->assign('lang_site',_MS_PW_SITE); 
$tpl->assign('lang_mark',_MS_PW_MARK);
$tpl->assign('lang_rating',_MS_PW_RATING);
$tpl->assign('works_type', $mc['other_works']);
$tpl->assign('lang_views', _MS_PW_VIEWS);

$imgSize = $mc['image_main'];
$tpl->assign('width',$imgSize[0]);
$thsSize = $mc['image_ths'];
$tpl->assign('widthimg',$thsSize[0]+10);
$tpl->assign('widthOther',$thsSize[0]+20);
	
//$xmh .= "\n<link href='".PW_URL."/include/css/lightbox.css' type='text/css' media='screen' rel='stylesheet' />\n
//<script type='text/javascript'>\nvar pw_url='".PW_URL."';\n</script>";
//$util->addScript('prototype');
//$util->addScript('scriptaeffects');
global $xoTheme;
//$xoTheme->addScript(PW_URL."/include/js/lightbox.js");	
PWFunctions::makeHeader();
include 'footer.php';
