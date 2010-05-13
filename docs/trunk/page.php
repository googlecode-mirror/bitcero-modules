<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// Autor: gina
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
// @author: gina
// @copyright: 2007 - 2008 Red México


define('AH_LOCATION','page');
include ('../../mainfile.php');
$xoopsOption['template_main']='ahelp_page.html';

include ('header.php');
include 'include/functions.php';

$myts=&MyTextSanitizer::getInstance();
$id=isset($_REQUEST['id']) ? $myts->addSlashes($_REQUEST['id']) : 0;
$id = explode('/', $id);
$id = $id[0];

//Ruta completa de sección
$content=$id;

//Verifica que contenido sea válido
if ($id<=0){
	redirect_header(XOOPS_URL."/modules/ahelp/",2,_MS_AH_NOTTEXT);
	die();
}

//Verifica que contenido exista
$text=new AHText($id);
if ($text->isNew()){
	redirect_header(XOOPS_URL."/modules/ahelp/",2,_MS_AH_NOTEXIST);
	die();
}	

$res=new AHResource($text->resource());

//Obtiene sección a visualizar
$sec=new AHSection($text->section());
	
//Obtiene Contenido
$text=new AHText($id);
$tpl->assign('id_sec',$sec->id());
$tpl->assign('nameid',$sec->nameId());
$tpl->assign('sec_title',$sec->title());

//Obtiene contenido siguiente
$id_max=contentNext($id,$sec->id(),$text->order());

//Obtiene contenido anterior
$id_min=contentBack($id,$sec->id(),$text->order());

$sec_max=0; 
$sec_min=0;

//Obtiene seccion siguiente
if (!$id_max) $sec_max=getSectionNext($sec);
if (!$id_min) $sec_min=getSectionBack($sec);

$tpl->append('items',array('id'=>$text->id(),'title'=>$text->title(),'text'=>$text->text(),'order'=>$text->order(),'nameid'=>$text->nameId(),
'id_max'=>$id_max,'id_min'=>$id_min,'sec_min'=>$sec_min,'sec_max'=>$sec_max));


//Obtiene url de contenido siguiente o anterior
$url=str_replace($text->nameId(),'',$content);

//Obtiene url de sección anterior y siguiente
$url=$ids[0]."/";


$tpl->assign('lang_home',_MS_AH_HOME);
$tpl->assign('lang_indexpublic',_MS_AH_INDEXPUBLIC);
$tpl->assign('resource',$res->title());
$tpl->assign('lang_back',_MS_AH_BACK);
$tpl->assign('lang_index',_MS_AH_INDEX);
$tpl->assign('lang_next',_MS_AH_NEXT);
$tpl->assign('access',$xoopsModuleConfig['access']);
$tpl->assign('url',$url);


if ($xoopsModuleConfig['access']==2) 
	$tpl->assign('id_res',$res->nameId());
else
	$tpl->assign('id_res',$res->id());


$url_page=XOOPS_URL.'/modules/ahelp/contents/';
$url_sec=XOOPS_URL.'/modules/ahelp/sections/';

if (!$xoopsModuleConfig['access']){//Si acceso es por PHP	
	$id_min ? $tpl->assign('url_idmin','./page.php?id=') :  $tpl->assign('url_idmin','./section.php?id=');
	$id_max ? $tpl->assign('url_idmax','./page.php?id=') :  $tpl->assign('url_idmax','./section.php?id=');
	$tpl->assign('url_doc','./doc.php?id=');
	
}else{
	$id_min ? $tpl->assign('url_idmin',$url_page) :  $tpl->assign('url_idmin',$url_sec);
	$id_max ? $tpl->assign('url_idmax',$url_page) :  $tpl->assign('url_idmax',$url_sec);
	$tpl->assign('url_doc',XOOPS_URL.'/modules/ahelp/resources/');
}
	

makeHeader();
makeFooter();



include ('footer.php');
?>
