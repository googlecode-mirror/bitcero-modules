<?php
// $Id: files.php 37 2008-03-03 18:46:45Z BitC3R0 $
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

define('DT_LOCATION','files');
include ('header.php');

/**
* @desc Muestra la barra de menus
*/
function optionsBar(DTSoftware $item = null){
    global $tpl;
	
	if ($item){
		$tpl->append('xoopsOptions', array('link' => './items.php', 'title' => _AS_DT_SOFTWARE, 'icon' => '../images/soft16.png'));
	}
    $tpl->append('xoopsOptions', array('link' => './files.php?item='.($item ? $item->id() : ''), 'title' => _AS_DT_FILES, 'icon' => '../images/down16.png'));
    if ($item){
	    $tpl->append('xoopsOptions', array('link' => './files.php?op=new&item='.$item->id(), 'title' => _AS_DT_NEWFILE, 'icon' => '../images/add.png'));
    }
}

/**
* @desc Visualiza todos los archivos de un software
**/
function showFiles(){
	global $db,$adminTemplate,$tpl,$util,$xoopsModule;

	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$edit=isset($_REQUEST['edit']) ? intval($_REQUEST['edit']) : 0;
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	
	if ($item>0){
		$sw=new DTSoftware($item);

		//Archivos sin grupo
		$sql="SELECT * FROM ".$db->prefix('dtrans_files')." WHERE id_soft=$item AND `group`=0";
		$result=$db->queryF($sql);
		while($rows=$db->fetchArray($result)){
			$fl=new DTFile();
			$fl->assignVars($rows);

			$tpl->append('files',array('id'=>$fl->id(),'file'=>$fl->file(),'downs'=>$fl->hits(),'group'=>$fl->group(),
			'default'=>$fl->isDefault(),'remote'=>$fl->remote(),'type'=>'files', 'title'=>$fl->title()));
			
		}
		

		//Grupos pertenecientes al software
		$groups=$sw->filegroups();
		foreach ($groups as $k){
			$gr = new DTFileGroup($k);		

			$tpl->append('files',array('id'=>$gr->id(),'file'=>$gr->name(),'type'=>'group'));

			$sql="SELECT * FROM ".$db->prefix('dtrans_files')." WHERE id_soft=$item AND `group`=$k";
			$result=$db->queryF($sql);
			while($rows=$db->fetchArray($result)){
				$fl=new DTFile();
				$fl->assignVars($rows);

				$tpl->append('files',array('id'=>$fl->id(),'file'=>$fl->file(),'downs'=>$fl->hits(),'group'=>$fl->group(),
				'default'=>$fl->isDefault(),'remote'=>$fl->remote(),'type'=>'files', 'title'=>$fl->title()));
			
			}
		
		}
		

		//Lista de grupos
		$sql ="SELECT * FROM ".$db->prefix('dtrans_groups')." WHERE id_soft=$item";
		$result=$db->queryF($sql);
		while($rows=$db->fetchArray($result)){
			$group=new DTFileGroup();
			$group->assignVars($rows);

			$tpl->append('groups',array('id'=>$group->id(),'name'=>$group->name()));

		}
		
		
		$tpl->assign('lang_exist',sprintf(_AS_DT_EXIST,$sw->name()));
		
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_FILES);
		
	} else {
		
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_DT_FILES);
		
	}
	
	$tpl->assign('lang_id',_AS_DT_ID);
	$tpl->assign('lang_remote',_AS_DT_REMOTE);
	$tpl->assign('lang_group',_AS_DT_GROUP);
	$tpl->assign('lang_file',_AS_DT_FILE);
	$tpl->assign('lang_downs',_AS_DT_DOWNS);
	$tpl->assign('lang_default',_AS_DT_DEFAULTFILE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('lang_selectitem',_AS_DT_SELECTITEM);
	$tpl->assign('lang_listsoft',_AS_DT_LSOFT);
	$tpl->assign('item',$item);
	$tpl->assign('parent','files');
	$tpl->assign('lang_select',_SELECT);
	$tpl->assign('lang_save',_AS_DT_SAVE);
	$tpl->assign('token',$util->getTokenHTML());
	
	optionsBar(isset($sw) ? $sw : null);
	$adminTemplate = 'admin/dtrans_files.html';
	xoops_cp_header();


	//Creamos formulario para crear grupos

	if ($edit){
		//Verificamos si grupo es válido
		if ($id<=0){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRGROUPVALID,1);
			die();
		}

		//Verificamos si el grupo existe
		$group=new DTFileGroup($id);
		if ($group->isNew()){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRGROUPEXIST,1);
			die();
		}
	}

	$form = new RMForm(_AS_DT_NEWGROUP,'frmgroup','files.php');
	$form->addElement(new RMText(_AS_DT_NAME,'name',50,100,$edit ? $group->name() : ''));

	$form->addElement(new RMHidden('op',$edit ? 'saveeditgr' : 'savegroup'));
	$form->addElement(new RMHidden('item',$item));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'files.php?item='.$item.'\';"');

	$form->addElement($buttons);
	
	$tpl->assign('form_group',$form->render());
	

	xoops_cp_footer();
}



/**
* @desc Formulario de archivos
**/
function formFiles($edit=0){
	global $xoopsConfig,$xoopsModule,$db,$xoopsModuleConfig;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;


	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}
	
	if ($edit){
		//Verificamos si archivo es válido
		if ($id<=0){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRFILEVALID,1);//erroe
			die();
		}
	
		//Verificamos si existe archivo
		$fl = new DTFile($id);
		if ($fl->isNew()){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRFILEEXIST,1);
			die();
		}
	}	

	optionsBar($sw);
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; <a href='?item=".$sw->id()."'>"._AS_DT_FILES."</a> &raquo; ".($edit ? _AS_DT_EDITFILE : _AS_DT_NEWFILE));
	xoops_cp_header();

	$form = new RMForm($edit ? sprintf(_AS_DT_EDITFILES,$sw->name()) : sprintf(_AS_DT_NEWFILES,$sw->name()),'frmfile','files.php');
	$form->setExtra("enctype='multipart/form-data'");	

	//Obtenemos las extensiones permitidas
	$ext='';
	foreach ($xoopsModuleConfig['type_file'] as $k){
		$ext.= $ext=='' ? '' : ',';
		$ext.=$k;
	}

	//Tamaño de archivo
	$size = formatBytesSize($xoopsModuleConfig['size_file']*1024);
	
	//Archivo
	if ($sw->secure()){
		$f = new RMText(_AS_DT_FILE,'file',50,255,$edit ? $fl->file() : '');
		$f->setDescription(sprintf(_AS_DT_DESCTYPEFILE,$ext,$size));
		$form->addElement($f,true);
		$ele = new RMText(_AS_DT_FILETITLE, 'title', 50, 100, $edit ? $fl->title() : '');
		$ele->setDescription(_AS_DT_FILETITLE_DESC);
		$form->addElement($ele);
	}else{
		$f = new RMFile(_AS_DT_FILE,'files',45,$xoopsModuleConfig['size_file']*1024);
		$f->setDescription(sprintf(_AS_DT_DESCTYPEFILE,$ext,$size));
		$form->addElement($f);
		if ($edit && file_exists($xoopsModuleConfig['directory_insecure']."/".$fl->file())){
			$form->addElement(new RMLabel(_AS_DT_NAMEFILE,$fl->file()));

		}
		$form->addElement(new RMText(_AS_DT_FILEURL,'url',50,255,$edit && !file_exists($xoopsModuleConfig['directory_insecure']."/".$fl->file()) ? $fl->file() : ''));
		$ele = new RMText(_AS_DT_FILETITLE, 'title', 50, 100, $edit ? $fl->title() : '');
		$ele->setDescription(_AS_DT_FILETITLE_DESC);
		$form->addElement($ele);
		$ele=new RMText(_AS_DT_SIZE,'size',11,11,$edit ? $fl->size() : '');
		$ele->setDescription(_AS_DT_DESCSIZE);
		$form->addElement($ele);
	}

	
	$form->addElement(new RMYesno(_AS_DT_DEFAULT,'default',$edit ? $fl->isDefault() : 0));


	$ele = new RMSelect(_AS_DT_GROUP,'group');
	$ele->addOption(0,_SELECT);
	
	//Lista de grupos
	$sql = "SELECT * FROM ".$db->prefix('dtrans_groups')." WHERE id_soft=".$item;
	$result = $db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ele->addOption($rows['id_group'],$rows['name'],$edit ? ($rows['id_group']==$fl->group() ? 1 : 0) : 0);		
	}

	$form->addElement($ele);
	$form->addElement(new RMHidden('op',$edit ? 'saveedit': 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('item',$item));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'files.php?item='.$item.'\';"');

	$form->addElement($buttons);
	

	$form->display();

	xoops_cp_footer();

}


/**
* @desc Almacena información del archivo en la base de datos
**/
function saveFiles($edit=0){
	global $db,$xoopsModuleConfig,$util;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		redirectMsg('./files.php?item='.$item,_AS_DT_SESSINVALID, 1);
		die();
	}

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	if ($edit){
		//Verificamos si archivo es válido
		if ($id<=0){
			redirectMsg('./files.php?id='.$id.'&item='.$item.'&op=edit',_AS_DT_ERRFILEVALID,1);
			die();
		}

		//Verificamos si existe archivo
		$fl = new DTFile($id);
		if ($fl->isNew()){
			redirectMsg('./files.php?id='.$id.'&item='.$item.'&op=edit',_AS_DT_ERRFILEEXIST,1);
			die();
		}

	}else{

		$fl=new DTFile();

	}

	if (!$sw->secure()){
		
		//Archivo
		include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
		$up = new RMUploader(false);
		$folder = $xoopsModuleConfig['directory_insecure'];//ruta
		$filename = '';
		$config=array();
		foreach ($xoopsModuleConfig['type_file'] as $k)
		{
			$config[]=$up->getMIME($k);	
		}

		$up->prepareUpload($folder, $config, $xoopsModuleConfig['size_file']*1024);
		
		if ($up->fetchMedia('files')){
		
			if (!$up->upload()){
				if ($fl->isNew()){
					redirectMsg('./files.php?op=new&id='.$id.'&item='.$item,$up->getErrors(),1);
					die();
				}else{
					redirectMsg('./files.php?op=edit&id='.$id.'&item='.$item,$up->getErrors(),1);
					die();				
				}
			}	

			if ($edit && $fl->file()!=''){
				@unlink($xoopsModuleConfig['directory_insecure']."/".$fl->file());
			}

			$filename = $up->getSavedFileName();
			$fullpath = $up->getSavedDestination();	
			$fl->setFile($filename);
			$fl->setSize($up->mediaSize);
			$fl->setMime($up->mediaType);
		}else{
			if ($url){

				//Verificamos el tipo de extension del archivo
				$type = pathinfo($url);
				/*if ($type && !in_array($type['extension'],$xoopsModuleConfig['type_file'])){
					if ($fl->isNew()){
						redirectMsg('./files.php?op=new&id='.$id.'&item='.$item,sprintf(_AS_DT_ERRNOTEXT,$type['extension']),1);	
						die();
					}else{
						redirectMsg('./files.php?id='.$id.'&item='.$item.'&op=edit',sprintf(_AS_DT_ERRNOTEXT,$type['extension']),1);
						die();	
					}
				}*/

				if ($edit && $fl->file()!=''){
					@unlink($xoopsModuleConfig['directory_insecure']."/".$fl->file());
				}
				$fl->setRemote(1);
				$fl->setFile($url);
				if (!$size){
					if ($fl->isNew()){
						redirectMsg('./files.php?op=new&item='.$item,_AS_DT_ERRSIZE,1);	
						die();
					}else{
						redirectMsg('./files.php?op=edit&id='.$id.'&item='.$item,_AS_DT_ERRSIZE,1);
						die();	
					}
				}
				$fl->setSize($size);

			}
		}
	}else{

		//Verificamos el tipo de extension del archivo
		$type=explode(".",$file); 
		if (!in_array(end($type),$xoopsModuleConfig['type_file'])){
			if ($fl->isNew()){
				redirectMsg('./files.php?op=new&item='.$item,sprintf(_AS_DT_ERRNOTEXT,end($type)),1);	
				die();
			}else{
				redirectMsg('./files.php?id='.$id.'&item='.$item.'&op=edit',sprintf(_AS_DT_ERRNOTEXT,end($type)),1);
				die();	
			}
		}
		
		//Verificamos si el archivo existe en el directorio de descargas seguras
		if (!file_exists($xoopsModuleConfig['directory_secure']."/".$file)){
			if ($fl->isNew()){
				redirectMsg('./files.php?op=new&id='.$id.'&item='.$item,sprintf(_AS_DT_ERRNOEXISTFILE,$xoopsModuleConfig['directory_secure']),1);	
				die();
			}else{
				redirectMsg('./files.php?id='.$id.'&item='.$item.'&op=edit',sprintf(_AS_DT_ERRNOEXISTFILE,$xoopsModuleConfig['directory_secure']),1);	
				die();	
			}

		}
		
		$up = new RMUploader(false);
		$fl->setSize(fileSize($xoopsModuleConfig['directory_secure']."/".$file));
		$fl->setMime($up->getMIME(end($type)));
		
	}
	
	
	//Modificamos todos los archivos a default=0
	if ($default){
		$sql="UPDATE ".$db->prefix('dtrans_files')." SET `default`=0 WHERE id_soft=$item";
		$result=$db->queryF($sql);
	}
	
	$fl->setSoftware($item);
	$sw->secure() ? $fl->setFile($file) : '';
	$fl->setdefault($default);
	$fl->setGroup($group);
	$fl->setDate(time());
	$fl->setTitle(trim($title));

	if (!$fl->save()){
		if ($fl->isNew()){
			redirectMsg('./files.php?op=new&item='.$item,_AS_DT_DBERROR,1);
			die();
		}else{
			redirectMsg('./files.php?op=edit&id='.$id.'&item='.$item,_AS_DT_DBERROR,1);
			die();
		}
	
	}else{
		redirectMsg('./files.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}

}


/**
* @desc Elimina de la base de datos el archivo especificado
**/
function deleteFiles(){
	global $xoopsModule,$util,$xoopsModuleConfig;

	$ids=isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos si nos proporcionaron algun archivo
	if (!is_array($ids) && $ids<=0){
		redirectMsg('./files.php?item='.$item,_AS_DT_NOTFILE,1);
		die();	
	}
	
	$num=0;
	if (!is_array($ids)){
		$file=new DTFile($ids);
		$ids=array($ids);
		$num=1;
	}


	if ($ok){

		if (!$util->validateToken()){
			redirectMsg('./files.php?item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}	
	
		
		$errors='';
		$files='';
		foreach ($ids as $k){
			

			//Verificamos si el archivo es válido
			if ($k<=0){
				$errors.=sprintf(_AS_DT_ERRFILEVAL,$k);
				continue;
			}

			//Verificamos si el archivo existe
			$fl=new DTFile($k);
			if ($fl->isNew()){
				$errors.=sprintf(_AS_DT_ERRFILEEX,$k);
				continue;
			}

			$secure=$sw->secure();
			$file=$fl->file();
			if (!$fl->delete()){
				$errors.=sprintf(_AS_DT_ERRFILEDEL,$k);
			}else{
				if ($secure){
					$files.=$file."<br />";
				}
			}
		
		}	

		
		if ($errors!=''){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRORS.$errors,1);
			die();
		}else{
			if ($files){
				redirectMsg('./files.php?item='.$item,_AS_DT_DBOK.sprintf(_AS_DT_DELSECURE,$files,$xoopsModuleConfig['directory_secure']),0);
			}
			
			redirectMsg('./files.php?item='.$item,_AS_DT_DBOK,0);
			die();
		}

	}else{
		
		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_DELETEFILE);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id[]'] = $ids;
		$hiddens['item'] = $item;
		$hiddens['op'] = 'delete';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'files.php?item='.$item.'\';"';
		
		$util->msgBox($hiddens, 'files.php', ($num ? sprintf(_AS_DT_DELETECONF,$file->file()) : _AS_DT_DELCONF). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}

}


/**
* @desc Almacena los datos del grupo en la base de datos
**/
function saveGroups($edit=0){
	global $db,$util;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$util->validateToken()){
		redirectMsg('./files.php?item='.$item,_AS_DT_SESSINVALID, 1);
		die();
	}

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	if ($edit){
		//Verificamos si grupo es válido
		if ($id<=0){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRGROUPVALID,1);
			die();
		}

		//Verificamos si el grupo existe
		$group=new DTFileGroup($id);
		if ($group->isNew()){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRGROUPEXIST,1);
			die();
		}

		//Verificamos si existe el nombre del grupo
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_groups')." WHERE name='".$name."' AND id_soft=".$item." AND id_group<>".$group->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRNAMEGROUP,1);
			die();
		}
	
	}else{


		//Verificamos si existe el nombre del grupo
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_groups')." WHERE name='".$name."' AND id_soft=".$item;
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./files.php?item='.$item,_AS_DT_ERRNAMEGROUP,1);
			die();
		}
		
		$group = new DTFileGroup();
	}

	$group->setName($name);
	$group->setSoftware($item);


	if (!$group->save()){
		redirectMsg('./files.php?item='.$item,_AS_DT_DBERROR,1);
		die();
	}else{
		redirectMsg('./files.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}


}


/**
* @desc Modifica el grupo al que pertenece cada archivo
**/
function updateGroups(){
	global $util;

	$groups=isset($_REQUEST['groups']) ? $_REQUEST['groups'] : array();
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;


	if (!$util->validateToken()){
		redirectMsg('./files.php?item='.$item,_AS_DT_SESSINVALID, 1);
		die();
	}


	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}
	
	$errors='';
	foreach($groups as $k=>$v){
	
		//Verificamos si el archivo es válido
		if ($k<=0){
			$errors.=sprintf(_AS_DT_ERRVALID,$k);
			continue;
		}

		//Verificamos si el archivo existe
		$fl = new DTFile($k);
		if ($fl->isNew()){
			$errors.=sprintf(_AS_DT_ERREXIST,$k);
			continue;
		}

		$fl->setGroup($v);

		if (!$fl->save()){
			$errors.=sprintf(_AS_DT_ERRUPDATE,$k);
		}
	}
	if ($errors!=''){
		redirectMsg('./files.php?item='.$item,_AS_DT_ERRORS.$errors,1);
		die();
	}else{
		redirectMsg('./files.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}


}


/**
* @desc Modifica el archivo por defecto del software
**/
function defaultFile(){
	global $db,$util;	

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}
	
	
	$sql="UPDATE ".$db->prefix('dtrans_files')." SET `default`=0 WHERE id_soft=$item AND id_file<>$id";
	$result=$db->queryF($sql);

	//Verificamos si archivo es válido
	if ($id<=0){
		redirectMsg('./files.php?item='.$item,_AS_DT_ERRFILEVALID,1);
		die();
	}

	//Verificamos si existe archivo
	$fl=new DTFile($id);
	if ($fl->isNew()){
		redirectMsg('./files.php?item='.$item,_AS_DT_ERRFILEEXIST,1);
		die();
	}	

	
	$fl->setDefault(1);
	if (!$fl->save()){
		redirectMsg('./files.php?item='.$item,_AS_DT_DBERROR,1);
		die();
	}else{
		redirectMsg('./files.php?item='.$item,_AS_DT_DBOK,0);
		die();
	}

}


/**
* @desc Elimina el grupo especificado
**/
function deleteGroups(){

	global $xoopsModule,$util;

	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$ok=isset($_POST['ok']) ? intval($_POST['ok']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMVALID,1);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirectMsg('./files.php',_AS_DT_ERR_ITEMEXIST,1);
		die();
	}

	//Verificamos si grupo es válido
	if ($id<=0){
		redirectMsg('./files.php?item='.$item,_AS_DT_ERRGROUPVALID,1);
		die();
	}

	//Verificamos si el grupo existe
	$group=new DTFileGroup($id);
	if ($group->isNew()){
		redirectMsg('./files.php?item='.$item,_AS_DT_ERRGROUPEXIST,1);
		die();
	}
	
	if  ($ok){
	
		if (!$util->validateToken()){
			redirectMsg('./files.php?item='.$item,_AS_DT_SESSINVALID, 1);
			die();
		}

		if (!$group->delete()){
			redirectMsg('./files.php?item='.$item,_AS_DT_DBERROR,1);
			die();
		}else{
			redirectMsg('./files.php?item='.$item,_AS_DT_DBOK,0);
			die();
		}	

	}else{

		optionsBar();
		xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./items.php'>"._AS_DT_SW."</a> &raquo; "._AS_DT_DELETEGROUP);
		xoops_cp_header();

		$hiddens['ok'] = 1;
		$hiddens['id'] = $id;
		$hiddens['item'] = $item;
		$hiddens['op'] = 'deletegroup';

		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="window.location=\'files.php?item='.$item.'\';"';
		
		$util->msgBox($hiddens, 'files.php', sprintf(_AS_DT_DELETECONFG, $group->name()). '<br /><br />' ._AS_DT_ALLPERM, XOOPS_ALERT_ICON, $buttons, true, '400px');
	
		xoops_cp_footer();

	}



}

$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
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
?>
