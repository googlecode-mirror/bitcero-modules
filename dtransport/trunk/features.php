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

define('DT_LOCATION','features');
include '../../mainfile.php';

$mc=& $xoopsModuleConfig;


/**
* @desc Visauliza las características existentes y el formulario de
* creacion/edicion de características
**/
function features($edit=0){

	global $xoopsOption,$db,$tpl,$xoopsUser,$mc;
	
	$xoopsOption['template_main'] = 'dtrans_createfeatures.html';
	$xoopsOption['module_subpage'] = 'features';

	include('header.php');
	DTFunctions::makeHeader();

	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	//Verificamos que el software sea válido
	if ($item<=0){
		redirect_header('./features.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./features.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	
	//Verificamos si el usuario es el propietario de la descarga
	if ($xoopsUser->uid()!=$sw->uid()){
		redirect_header(XOOPS_URL."/modules/dtransport/",2,_MS_DT_ERRUSER);
		die();
	}


	
	$sql = "SELECT * FROM ".$db->prefix('dtrans_features')." WHERE id_soft=$item ";
	$result = $db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$ft = new DTFeature();
		$ft->assignVars($rows);

		$tpl->append('features',array('id'=>$ft->id(),'title'=>$ft->title(),'created'=>formatTimestamp($ft->created(), 's'),
				'modified'=>formatTimestamp($ft->modified(), 's'), 'software'=>$sw->name()));
	
	}

	$tpl->assign('item',$item);
	$tpl->assign('lang_exists',sprintf(_MS_DT_EXISTS,$sw->name()));
	$tpl->assign('lang_id',_MS_DT_ID);
	$tpl->assign('lang_title',_MS_DT_TITLE);
	$tpl->assign('lang_modified',_MS_DT_MODIFIEDF);
	$tpl->assign('lang_created',_MS_DT_CREATEDF);
	$tpl->assign('lang_software',_MS_DT_SOFTWARE);
	$tpl->assign('lang_options',_OPTIONS);
	$tpl->assign('lang_edit',_EDIT);
	$tpl->assign('lang_del',_DELETE);
	$tpl->assign('parent','features');
	$tpl->assign('lang_deletefeat',_MS_DT_DELETEFEAT);
	$tpl->assign('lang_deletefeats',_MS_DT_DELETEFEATS);
	$tpl->assign('lang_newfeat',_MS_DT_NEWFEATURE);
	$tpl->assign('edit',$edit);
	
	/**
	* Formulario de caracteristicas
	**/
	if ($edit){
		//Verificamos que característica sea válida
		if ($id<=0){
			redirect_header('./features.php?item='.$item,2,_MS_DT_ERRFEATVALID);
			die();
		}

		//Verificamos que la característica exista
		$ft = new DTFeature($id);
		if ($ft->isNew()){
			redirect_header('./features.php?item='.$item,2,_MS_DT_ERRFEATEXIST);
			die();
		}

	}


	$form = new RMForm($edit ? sprintf(_MS_DT_EDITFEATURES,$sw->name()) : sprintf(_MS_DT_NEWFEATURES,$sw->name()),'frmfeats','features.php');

	$sw = new DTSoftware($item);
	$form->addElement(new RMLabel(_MS_DT_SOFTWARE,$sw->name()));
	

	$form->addElement(new RMText(_MS_DT_TITLE,'title',50,200,$edit ? $ft->title() : ''),true);
	$form->addElement(new RMEditor(_MS_DT_CONTENT,'content','50%','350px',$edit ? $ft->content() : '',$xoopsConfig['editor_type']),true);
	if ($edit){
		$dohtml = $ft->getVar('dohtml');
		$dobr = $ft->getVar('dobr');
		$doimage = $ft->getVar('doimage');
		$dosmiley = $ft->getVar('dosmiley');
		$doxcode = $ft->getVar('doxcode');
	} else {
		$dohtml = 1;
		$dobr = 0;
		$doimage = 0;
		$dosmiley = 0;
		$doxcode = 0;
	}
	$form->addElement(new RMTextOptions(_OPTIONS, $dohtml, $doxcode, $doimage, $dosmiley, $dobr));
	
	$form->addElement(new RMHidden('op',$edit ? 'saveedit' : 'save'));
	$form->addElement(new RMHidden('id',$id));
	$form->addElement(new RMHidden('item',$item));
	
	$buttons =new RMButtonGroup();
	$buttons->addButton('sbt',_SUBMIT,'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\'features.php?item='.$item.'\';"');

	$form->addElement($buttons);

	$tpl->assign('form_feat',$form->render());


	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; <a href='".DT_URL."/mydownloads.php'>";
	$location .= _MS_DT_MYDOWNS."</a> &raquo; "._MS_DT_FEATURES;
	$tpl->assign('dt_location', $location);


	$xmh.= "<script type='text/javascript'>\n
	function formFeat(){
		if ($('dtFormFeat').style.display=='block'){\n
			$('dtFormFeat').style.display='none';\n
		}else{\n
				$('dtFormFeat').style.display='block';\n
			}\n
		}\n
	</script>";
	
	include ('footer.php');

}


/**
* @desc Almacena la característica en la base de datos
**/
function saveFeatures($edit=0){
	global $db;
	
	$util=& RMUtils::getInstance();

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	
	//Verificamos que el software sea válido
	if ($item<=0){
		redirect_header('./features.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./features.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}


	if ($edit){
		//Verificamos que característica sea válida
		if ($id<=0){
			redirect_header('./features.php?item='.$item,2,_MS_DT_ERRFEATVALID);
			die();
		}

		//Verificamos que la característica exista
		$ft = new DTFeature($id);
		if ($ft->isNew()){
			redirect_header('./features.php?item='.$item,2,_MS_DT_ERRFEATEXIST);
			die();
		}

		//Comprueba que el título de la característica no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE title='$title' AND id_feat<>".$ft->id()." AND id_soft=".$ft->software();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header('./features.php?op=edit&id='.$id.'&item='.$item,2,_MS_DT_ERRNAME);	
			die();
		}


	}else{
		//Comprueba que el título de la característica no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE title='$title' AND id_soft=".$sw->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header('./features.php?item='.$item,2,_MS_DT_ERRNAME);	
			die();
		}

		$ft = new DTFeature();

	}


	$found=false; 
	$i = 0;
	if ($title!=$ft->title()){
		do{
    			$nameid = $util->sweetstring($title).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_features'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
		}while ($found==true);
		$ft->setNameId($nameid);
	}


	$ft->setSoftware($item);
	$ft->setTitle($title);
	$ft->setContent($content);
	if (!$edit) $ft->setCreated(time());
	$ft->setModified(time());

	
	$ft->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$ft->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$ft->setVar('dobr', isset($dobr) ? 1 : 0);
	$ft->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$ft->setVar('doimage', isset($doimage) ? 1 : 0);

	if (!$ft->save()){
		if ($ft->isNew()){
			redirect_header('./features.php?item='.$item,2,_MS_DT_DBERROR);
			die();
		}else{
			redirect_header('./features.php?op=edit&item='.$item1,1,_MS_DT_DBERROR);
			die();			
		}
	}else{
		redirect_header('./features.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}

}

/**
* @desc Elimina la característica especificada de la base de datos
**/
function deleteFeatures(){
	global $xoopsModule;
	
	$ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$item = isset($_REQUEST['item']) ? intval($_REQUEST['item']) : 0;
	
	//Verificamos que el software sea válido
	if ($item<=0){
		redirect_header('./features.php',2,_MS_DT_ERR_ITEMVALID);
		die();
	}
	//Verificamos que el software exista
	$sw=new DTSoftware($item);
	if ($sw->isNew()){
		redirect_header('./features.php',2,_MS_DT_ERR_ITEMEXIST);
		die();
	}

	//Verificamos si nos proporcionaron alguna caracteristica
	if (!is_array($ids) && $ids<=0){
		redirect_header('./features.php?item='.$item,2,_MS_DT_ERRFEAT);
		die();	
	}

	if (!is_array($ids)){
		$ids=array($ids);
	}

	$errors='';
	foreach ($ids as $k){
		//Verificamos si la característica es válida
		if ($k<=0){
			$errors.=sprintf(_MS_DT_ERRFEATVAL,$k);
			continue;
		}
		//Verificamos si la caracteristica existe
		$ft=new DTFeature($k);
		if ($ft->isNew()){
			$errors.=sprintf(_MS_DT_ERRFEATEX,$k);
			continue;			
		}
		
		if (!$ft->delete()){
			$errors.=sprintf(_MS_DT_ERRFEATDEL,$k);
		}

	}

			
	if ($errors!=''){
		redirect_header('./features.php?item='.$item,2,_MS_DT_DBERROR."<br />".$errors,1);
		die();
	}else{
		redirect_header('./features.php?item='.$item,1,_MS_DT_DBOK);
		die();
	}	

}




$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'edit':
		features(1);
	break;
	case 'save':
		saveFeatures();
	break;
	case 'saveedit':
		saveFeatures(1);
	break;
	case 'delete':
		deleteFeatures();
	break;
	default:
		features();
}
?>
