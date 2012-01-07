<?php
// $Id$
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
include '../../mainfile.php';

$mc=& $xoopsModuleConfig;

/**
* @desc Visualiza los archivos de la descarga y el formulario de ceacion/edicion
**/
function files($editg=0,$edit=0){
	global $xoopsOption,$db,$tpl,$xoopsUser,$mc;
		
	$xoopsOption['template_main'] = 'dtrans_files.html';
	$xoopsOption['module_subpage'] = 'files';

	include('header.php');
	DTFunctionsHandler::makeHeader();

	
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	
	if ($item>0){
		$sw=new DTSoftware($item);
		//Verificamos si el usuario es el propietario de la descarga
		if ($xoopsUser->uid()!=$sw->uid()){
			redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRUSER);
			die();
		}
	

		//Archivos sin grupo
		$sql="SELECT * FROM ".$db->prefix('dtrans_files')." WHERE id_soft=$item AND `group`=0";
		$result=$db->queryF($sql);
		while($rows=$db->fetchArray($result)){
			$fl=new DTFile();
			$fl->assignVars($rows);

			$tpl->append('files',array('id'=>$fl->id(),'file'=>$fl->file(),'downs'=>$fl->hits(),'group'=>$fl->group(),
			'default'=>$fl->isDefault(),'remote'=>$fl->remote(),'type'=>'files','title'=>$fl->title()));
			
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
				'default'=>$fl->isDefault(),'remote'=>$fl->remote(),'type'=>'files'));
			
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
		
		
		$tpl->assign('lang_exist',sprintf(_MS_DT_EXIST,$sw->name()));
		
		
		
	}
	$tpl->assign('lang_id',_MS_DT_ID);
	$tpl->assign('lang_remote',_MS_DT_REMOTE);
	$tpl->assign('lang_group',_MS_DT_GROUP);
	$tpl->assign('lang_file',_MS_DT_FILE);
	$tpl->assign('lang_downs',_MS_DT_DOWNS);
	$tpl->assign('lang_default',_MS_DT_DEFAULTFILE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('item',$item);
	$tpl->assign('parent','files');
	$tpl->assign('lang_select',_SELECT);
	$tpl->assign('lang_save',_MS_DT_SAVE);
	$tpl->assign('lang_deletegr', sprintf(_MS_DT_DELETEGR,$group ? $group->name() : ''));
	$tpl->assign('lang_deletefile',_MS_DT_DELETEFILE);
	$tpl->assign('lang_deletefiles',_MS_DT_DELETEFILES);
	$tpl->assign('lang_creategroup',_MS_DT_CREATEGROUP);
	$tpl->assign('lang_createfile',_MS_DT_CREATEFILE);
	$tpl->assign('edit',$edit);
	$tpl->assign('editg',$editg);



	/**
	* Creamos formulario para crear grupos
	**/

	if ($editg){
		//Verificamos si grupo es válido
		if ($id<=0){
			redirect_header('./files.php?item='.$item,1,_MS_DT_ERRGROUPVALID);
			die();
		}

		//Verificamos si el grupo existe
		$group=new DTFileGroup($id);
		if ($group->isNew()){
			redirect_header('./files.php?item='.$item,1,_MS_DT_ERRGROUPEXIST);
			die();
		}
	}

	$form = new RMForm($editg ? _MS_DT_EDITGROUP : _MS_DT_NEWGROUP,'frmgroup','files.php');
	$form->addElement(new RMText(_MS_DT_NAME,'name',50,100,$editg ? $group->name() : ''));

	$form->addElement(new RMHidden('op',$editg ? 'saveeditgr' : 'savegroup'));
	$form->addElement(new RMHidden('item',$item));
	$form->addElement(new RMHidden('id',$id));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'files.php?item='.$item.'\';"');

	$form->addElement($buttons);
	
	$tpl->assign('form_group',$form->render());
	

	//Fin de formulario de grupos


	/**
	* Formulario para archivos
	**/
	if ($edit){

		//Verificamos si archivo es válido
		if ($id<=0){
			redirect_header('./files.php?item='.$item,2,_MS_DT_ERRFILEVALID);
			die();
		}
	
		//Verificamos si existe archivo
		$file = new DTFile($id);
		if ($file->isNew()){
			redirect_header('./files.php?item='.$item,2,_MS_DT_ERRFILEEXIST);
			die();
		}
		
	}
	


	$form = new RMForm($edit ? sprintf(_MS_DT_EDITFILES,$sw->name()) : sprintf(_MS_DT_NEWFILES,$sw->name()),'frmfile','files.php');
	$form->setExtra("enctype='multipart/form-data'");	

	//Obtenemos las extensiones permitidas
	$ext='';
	foreach ($mc['type_file'] as $k){
		$ext.= $ext=='' ? '' : ',';
		$ext.=$k;
	}

	//Tamaño de archivo
	$size = formatBytesSize($mc['size_file']*1024);
	
	//Archivo
	if ($sw->secure()){
		$f = new RMText(_MS_DT_FILE,'file',50,255,$edit ? $file->file() : '');
		$f->setDescription(sprintf(_MS_DT_DESCTYPEFILE,$ext,$size));
		$form->addElement($f,true);
	}else{
		$f = new RMFile(_MS_DT_FILE,'files',45,$mc['size_file']*1024);
		$f->setDescription(sprintf(_MS_DT_DESCTYPEFILE,$ext,$size));
		$form->addElement($f);
		if ($edit && file_exists($mc['directory_insecure']."/".$file->file())){
			$form->addElement(new RMLabel(_MS_DT_NAMEFILE,$file->file()));

		}
		$form->addElement(new RMText(_MS_DT_FILEURL,'url',50,255,$edit && !file_exists($mc['directory_insecure']."/".$file->file()) ? $file->file() : ''));
		$ele=new RMText(_MS_DT_SIZE,'size',11,11,$edit ? (number_format($file->size()/1024,1)) : '');
		$ele->setDescription(_MS_DT_DESCSIZE);
		$form->addElement($ele);
	}

	
	$form->addElement(new RMYesno(_MS_DT_DEFAULT,'default',$edit ? $file->isDefault() : 0));


	$ele = new RMSelect(_MS_DT_GROUP,'group');
	$ele->addOption(0,_SELECT);
	
	//Lista de grupos
	$sql = "SELECT * FROM ".$db->prefix('dtrans_groups')." WHERE id_soft=".$item;
	$result = $db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ele->addOption($rows['id_group'],$rows['name'],$edit ? ($rows['id_group']==$file->group() ? 1 : 0) : 0);		
	}

	$form->addElement($ele);
	$form->addElement(new RMHidden('op',$edit ? 'saveedit': 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('item',$item));

	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'files.php?item='.$item.'\';"');

	$form->addElement($buttons);
	
	$tpl->assign('form_files',$form->render());

	
	//Fin de Formulario para crear archivos

	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; <a href='".DT_URL."/mydownloads.php'>";
	$location .= _MS_DT_MYDOWNS."</a> &raquo; "._MS_DT_FILES;
	$tpl->assign('dt_location', $location);

	$xmh.= "<script type='text/javascript'>\n
		function formGroup(){
			if ($('dtFormGroup').style.display=='block'){\n
				$('dtFormGroup').style.display='none';\n
			}else{\n
				$('dtFormGroup').style.display='block';\n
			}\n
		}\n
		function formFile(){
			if ($('dtFormFile').style.display=='block'){\n
				$('dtFormFile').style.display='none';\n
			}else{\n
				$('dtFormFile').style.display='block';\n
			}\n
		}\n
	</script>";

	include('footer.php');
}


/**
* @desc Almacena información del archivo en la base de datos
**/
function saveFiles($edit=0){
	global $db,$mc;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}


	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	if ($edit){
		//Verificamos si archivo es válido
		if ($id<=0){
			redirect_header('./files.php?id='.$id.'&item='.$item.'&op=edit',2,_MS_DT_ERRFILEVALID);
			die();
		}

		//Verificamos si existe archivo
		$fl = new DTFile($id);
		if ($fl->isNew()){
			redirect_header('./files.php?id='.$id.'&item='.$item.'&op=edit',2,_MS_DT_ERRFILEEXIST);
			die();
		}

	}else{

		$fl=new DTFile();

	}

	if (!$sw->secure()){
		
		//Archivo
		include_once XOOPS_ROOT_PATH.'/rmcommon/uploader.class.php';
		$up = new RMUploader(false);
		$folder = $mc['directory_insecure'];//ruta
		$filename = '';
		$config=array();
		foreach ($mc['type_file'] as $k)
		{
			$config[]=$up->getMIME($k);	
		}

		$up->prepareUpload($folder, $config, $mc['size_file']*1024);
		
		if ($up->fetchMedia('files')){
		
			if (!$up->upload()){
				if ($fl->isNew()){
					redirect_header('./files.php?op=new&id='.$id.'&item='.$item,2,$up->getErrors());
					die();
				}else{
					redirect_header('./files.php?op=edit&id='.$id.'&item='.$item,2,$up->getErrors());
					die();				
				}
			}	

			if ($edit && $fl->file()!=''){
				@unlink($mc['directory_insecure']."/".$fl->file());
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
				if ($type && !in_array($type['extension'],$mc['type_file'])){
					if ($fl->isNew()){
						redirect_header('./files.php?op=new&id='.$id.'&item='.$item,2,sprintf(_MS_DT_ERRNOTEXT,$type['extension']));	
						die();
					}else{
						redirect_header('./files.php?id='.$id.'&item='.$item.'&op=edit',2,sprintf(_MS_DT_ERRNOTEXT,$type['extension']));
						die();	
					}
				}

				if ($edit && $fl->file()!=''){
					@unlink($mc['directory_insecure']."/".$fl->file());
				}
				$fl->setRemote(1);
				$fl->setFile($url);
				if (!$size){
					if ($fl->isNew()){
						redirect_header('./files.php?op=new&item='.$item,2,_MS_DT_ERRSIZE);	
						die();
					}else{
						redirect_header('./files.php?op=edit&id='.$id.'&item='.$item,2,_MS_DT_ERRSIZE);
						die();	
					}
				}
				$fl->setSize($size*1024);

			}
		}
	}else{

		//Verificamos el tipo de extension del archivo
		$type=explode(".",$file); 
		if (!in_array(end($type),$mc['type_file'])){
			if ($fl->isNew()){
				redirect_header('./files.php?op=new&item='.$item,2,sprintf(_MS_DT_ERRNOTEXT,end($type)));	
				die();
			}else{
				redirect_header('./files.php?id='.$id.'&item='.$item.'&op=edit',2,sprintf(_MS_DT_ERRNOTEXT,end($type)));
				die();	
			}
		}
		
		//Verificamos si el archivo existe en el directorio de descargas seguras
		if (!file_exists($mc['directory_secure']."/".$file)){
			if ($fl->isNew()){
				redirect_header('./files.php?op=new&id='.$id.'&item='.$item,2,sprintf(_MS_DT_ERRNOEXISTFILE,$mc['directory_secure']));	
				die();
			}else{
				redirect_header('./files.php?id='.$id.'&item='.$item.'&op=edit',2,sprintf(_MS_DT_ERRNOEXISTFILE,$mc['directory_secure']));	
				die();	
			}

		}
		
		$up = new RMUploader(false);
		$fl->setSize(fileSize($mc['directory_secure']."/".$file));
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

	if (!$fl->save()){
		if ($fl->isNew()){
			redirect_header('./files.php?op=new&item='.$item,2,_MS_DT_DBERROR);
			die();
		}else{
			redirect_header('./files.php?op=edit&id='.$id.'&item='.$item,2,_MS_DT_DBERROR);
			die();
		}
	
	}else{
		redirect_header('./files.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}

}

/**
* @desc Elimina de la base de datos el archivo especificado
**/
function deleteFiles(){
	global $xoopsModule,$mc;

	$ids=isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	

	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	//Verificamos si nos proporcionaron algun archivo
	if (!is_array($ids) && $ids<=0){
		redirect_header('./files.php?item='.$item,2,_MS_DT_NOTFILE);
		die();	
	}
	
	if (!is_array($ids)){
		$ids=array($ids);
	}

	$errors='';
	$files='';
	foreach ($ids as $k){
			

		//Verificamos si el archivo es válido
		if ($k<=0){
			$errors.=sprintf(_MS_DT_ERRFILEVAL,$k);
			continue;
		}

		//Verificamos si el archivo existe
		$fl=new DTFile($k);
		if ($fl->isNew()){
			$errors.=sprintf(_MS_DT_ERRFILEEX,$k);
			continue;
		}
		$secure=$sw->secure();
		$file=$fl->file();
		if (!$fl->delete()){
			$errors.=sprintf(_MS_DT_ERRFILEDEL,$k);
		}else{
			if ($secure){
				$files.=$file."<br />";
			}
		}
		
	}	

		
	if ($errors!=''){
		redirect_header('./files.php?item='.$item,2,_MS_DT_DBERROR."<br />".$errors);
		die();
	}else{
		if ($files){
			redirect_header('./files.php?item='.$item,2,_MS_DT_DBOK.sprintf(_MS_DT_DELSECURE,$files,$mc['directory_secure']));
		}
			
		redirect_header('./files.php?item='.$item,1,_MS_DT_DBOK);
		die();
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

	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	if ($edit){
		//Verificamos si grupo es válido
		if ($id<=0){
			redirect_header('./files.php?item='.$item,2,_MS_DT_ERRGROUPVALID);
			die();
		}

		//Verificamos si el grupo existe
		$group=new DTFileGroup($id);
		if ($group->isNew()){
			redirect_header('./files.php?item='.$item,2,_MS_DT_ERRGROUPEXIST);
			die();
		}

		//Verificamos si existe el nombre del grupo
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_groups')." WHERE name='".$name."' AND id_soft=".$item." AND id_group<>".$group->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header('./files.php?item='.$item,2,_MS_DT_ERRNAMEGROUP);
			die();
		}
	
	}else{


		//Verificamos si existe el nombre del grupo
		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_groups')." WHERE name='".$name."' AND id_soft=".$item;
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header('./files.php?item='.$item,2,_MS_DT_ERRNAMEGROUP);
			die();
		}
		
		$group = new DTFileGroup();
	}

	$group->setName($name);
	$group->setSoftware($item);


	if (!$group->save()){
		redirect_header('./files.php?item='.$item,2,_MS_DT_DBERROR);
		die();
	}else{
		redirect_header('./files.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}


}


/**
* @desc Elimina el grupo especificado
**/
function deleteGroups(){
	
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$item=isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;

	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	//Verificamos si grupo es válido
	if ($id<=0){
		redirect_header('./files.php?item='.$item,2,_MS_DT_ERRGROUPVALID);
		die();
	}

	//Verificamos si el grupo existe
	$group=new DTFileGroup($id);
	if ($group->isNew()){
		redirect_header('./files.php?item='.$item,2,_MS_DT_ERRGROUPEXIST);
		die();
	}

	if (!$group->delete()){
		redirect_header('./files.php?item='.$item,2,_MS_DT_DBERROR);
		die();
	}else{
		redirect_header('./files.php?item='.$item,1,_MS_DT_DBOK);
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
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}
	
	
	$sql="UPDATE ".$db->prefix('dtrans_files')." SET `default`=0 WHERE id_soft=$item AND id_file<>$id";
	$result=$db->queryF($sql);

	//Verificamos si archivo es válido
	if ($id<=0){
		redirect_header('./files.php?item='.$item,2,_MS_DT_ERRFILEVALID);
		die();
	}

	//Verificamos si existe archivo
	$fl=new DTFile($id);
	if ($fl->isNew()){
		redirect_header('./files.php?item='.$item,2,_MS_DT_ERRFILEEXIST);
		die();
	}	

	
	$fl->setDefault(1);
	if (!$fl->save()){
		redirect_header('./files.php?item='.$item,2,_MS_DT_DBERROR);
		die();
	}else{
		redirect_header('./files.php?item='.$item,1,_MS_DT_DBOK);
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


	//Verificamos si el software es válido
	if ($item<=0){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	
	//Verificamos si existe el software
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./files.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}
	
	$errors='';
	foreach($groups as $k=>$v){
	
		//Verificamos si el archivo es válido
		if ($k<=0){
			$errors.=sprintf(_MS_DT_ERRVALID,$k);
			continue;
		}

		//Verificamos si el archivo existe
		$fl = new DTFile($k);
		if ($fl->isNew()){
			$errors.=sprintf(_MS_DT_ERREXIST,$k);
			continue;
		}

		$fl->setGroup($v);

		if (!$fl->save()){
			$errors.=sprintf(_MS_DT_ERRUPDATE,$k);
		}
	}
	if ($errors!=''){
		redirect_header('./files.php?item='.$item,2,_MS_DT_ERRORS.$errors);
		die();
	}else{
		redirect_header('./files.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}


}



$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'edit':
		files(0,1);
	break;
	case 'editg':
		files(1,0);
	break;
	case 'save':
		saveFiles();
	break;
	case 'saveedit':
		saveFiles(1);
	break;
	case 'delete':
		deleteFiles();
	break;
	case 'savegroup':
		saveGroups();
	break;
	case 'saveeditgr':
		saveGroups(1);
	break;
	case 'deletegroup':
		deleteGroups();
	break;
	case 'default':
		defaultfile();
	break;
	case 'updategroup':
		updateGroups();
	break;
	default:
		files();
}
?>
