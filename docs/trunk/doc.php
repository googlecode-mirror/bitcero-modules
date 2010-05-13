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



define('AH_LOCATION','docs');
include ('../../mainfile.php');
$xoopsOption['template_main']='ahelp_docs.html';

include ('header.php');

$myts=&MyTextSanitizer::getInstance();
$id =isset($_REQUEST['id']) ? $myts->addSlashes($_REQUEST['id']) : 0;

//Verifica que se haya proporcionado una publicación
if (is_numeric($id)){
	if ($id<=0){
		redirect_header(XOOPS_URL."/modules/ahelp/",2,_MS_AH_NOTRESOURCE);
		die();
	}
}else{
	if ($id==''){
		redirect_header(XOOPS_URL."/modules/ahelp/",2,_MS_AH_NOTRESOURCE);
		die();

	}
}

//Verifica que la publicación exista
$res= new AHResource($id);
if ($res->isNew()){
	redirect_header(XOOPS_URL."/modules/ahelp/",2,_MS_AH_NOTEXIST);
	die();
}


//Verificamos si es una publicación aprobada
if (!$res->approved()){
	redirect_header(XOOPS_URL.'/modules/ahelp/',2,_MS_AH_NOTAPPROVED);
	die();
}

//Verifica si usuario permisos para la publicación
if (!$res->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS) &&  !$res->showIndex()){
	redirect_header(XOOPS_URL."/modules/ahelp/",2,_MS_AH_NOTPERM);
	die();
}


$index=$res->quick();
$content=false;
//Obtiene índice
$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='".$res->id()."' AND parent=0 ORDER BY `order`";
$result=$db->queryF($sql);
while ($rows=$db->fetchArray($result)){
	$sec=new AHSection();
	$sec->assignVars($rows);	
	if ($xoopsModuleConfig['access']==2) $id_sec=$sec->nameId(); else $id_sec=$sec->id();

	$tpl->append('items',array('id'=>$id_sec,'title'=>$sec->title(),'order'=>$sec->order(),'parent'=>$sec->parent(),
	'indent'=>0,'type'=>'section','desc'=>substr($util->filterTags($sec->desc()),0,50)."...",'route'=>$id_sec));
		
	//Obtiene contenido
	if (!$index){
		$content=true;		
		contents($sec,1,$id_sec);
		
	}
		

	//Obtiene secciones hijas
	child($res->id(),$sec,$id_sec,1,$content);

}


$tpl->assign('resource',$res->title());
$tpl->assign('lang_index',_MS_AH_INDEX);
$tpl->assign('lang_home',_MS_AH_HOME);
$tpl->assign('lang_indexpublic',_MS_AH_INDEXPUBLIC);
$tpl->assign('id',$id);
$tpl->assign('index',$index);
$tpl->assign('access',$xoopsModuleConfig['access']);
$tpl->assign('url',XOOPS_URL.'/modules/ahelp');
$tpl->assign('show',$res->show());
$tpl->assign('id_public',$res->nameId());


/**
* @desc Obtiene los contenidos pertenecientes a una sección
* @param sec Objeto de sección
* @param $route Ruta de la sección a la que pertenece el contenido
**/
function contents($sec,$indent,$route){
	global $tpl,$db,$xoopsModuleConfig;
		
	$sql="SELECT * FROM ".$db->prefix('pa_texts')." WHERE id_section='".$sec->id()."' ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$text=new AHText();
		$text->assignVars($rows);
		if ($xoopsModuleConfig['access']==2){
			 $id_text=$text->nameId(); 
			 $id=$sec->nameId();			
		}else{
			 $id_text=$text->id();
			 $id=$sec->id();
		}

		$tpl->append('items',array('id'=>$id_text,'title'=>$text->title(),'order'=>$text->order(),
		'sec'=>$id,'type'=>'content','indent'=>$indent,'route'=>$route));
		
	
	}


}



/**
* @desc Obtiene las secciones hijas de una sección
* @param int $id Publicación a que pertenece
* @param int $sec Objeto Sección
* @param $route Ruta de la Sección a la que pertenece
**/
function child($id,$sec,$route,$indent,$content){
	global $tpl,$db,$util,$xoopsModuleConfig;
	
	$child= array();
	$sql="SELECT * FROM ".$db->prefix('pa_sections')." WHERE id_res='$id' AND parent='".$sec->id()."' ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new AHSection();
		$sec->assignVars($rows);
		
		
		if ($xoopsModuleConfig['access']==2) $id_sec=$sec->nameId(); else $id_sec=$sec->id();
		
		$root=$route."/".$id_sec;
		$tpl->append('items',array('id'=>$id_sec,'title'=>$sec->title(),'order'=>$sec->order(),'parent'=>$sec->parent(),
		'indent'=>$indent,'type'=>'section','desc'=>substr($util->filterTags($sec->desc()),0,50)."...",'route'=>$root));
		
		if ($content){
			contents($sec,$indent+1,$root);
			
		}
		
		child($id,$sec,$root,$indent+1,$content);	
	}
}





include_once 'include/functions.php';
makeHeader();
makeFooter();

include ('footer.php');
?>
