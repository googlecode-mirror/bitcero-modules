<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

defined('XOOPS_MAINFILE_INCLUDED') or die("Not allowed");

$xoopsOption['template_main'] = 'dtrans_screens.html';
$xoopsOption['module_subpage'] = 'cp-screens';

/**
* @desc Visualiza las pantallas del software y 
* el formulario de creación de pantallas
**/
function dt_screens($edit=0){
	global $xoopsOption,$db,$tpl,$xoopsTpl,$xoopsUser,$mc, $dtfunc, $page, $item, $xoopsConfig, $xoopsModuleConfig,$screen;
	
	include('header.php');
	$dtfunc->cpHeader($item, sprintf(__('Screenshots for "%s"','dtransport'), $item->getVar('name')));

    $tc = TextCleaner::getInstance();
    
	$sql = "SELECT * FROM ".$db->prefix('dtrans_screens')." WHERE id_soft=".$item->id();
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sc = new DTScreenshot();
		$sc->assignVars($rows);
		
		$xoopsTpl->append('screens',array(
            'id'=>$sc->id(),
            'title'=>$sc->title(),
		    'desc'=> $tc->clean_disabled_tags($sc->desc()),
            'software'=>$item->getVar('name'),
            'image' => $sc->url('ths'),
            'links' => array(
                'edit' => DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->getVar('nameid').'/edit/'.$sc->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=screens&amp;screen='.$sc->id()),
                'delete' => DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->getVar('nameid').'/delete/'.$sc->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=delete&amp;screen='.$sc->id())
            )
        ));
	}

	//Formulario de pantallas
	if ($edit){
		//Verificamos si la pantalla es válida
		if ($screen<=0)
			redirect_header(DT_URL.($mc['permalinks']?'/screens/'.$item->getVar('nameid'):'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Invalid screen','dtransport'));

		//Verificamos si la pantalla existe
		$sc = new DTScreenshot($screen);
		if ($sc->isNew())
			redirect_header(DT_URL.($mc['permalinks']?'/screens/'.$item->getVar('nameid'):'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Invalid screen','dtransport'));

	}
	
	if ($edit || $mc['limit_screen']>$item->getVar('screens')){	
        
        if($edit)
            $faction = DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->id().'/save/'.$sc->id().'/':'');
        else
           $faction = DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->id().'/save/0/':'');
        
		$form = new RMForm($edit ? sprintf(__('Edit Screenshot of %s','dtransport'),$item->getVar('name')) : sprintf(__('Add screen for %s','dtransport'),$item->getVar('name')),'frmscreen', $faction);
		$form->setExtra("enctype='multipart/form-data'");	
	
		$form->addElement(new RMFormLabel(__('Download item','dtransport'),$item->getVar('name')));

	
		$form->addElement(new RMFormText(__('Title','dtransport'),'title',50,100,$edit ? $sc->title() : ''),true);
		$form->addElement(new RMFormEditor(__('Description','dtransport'),'desc','auto','100px',$edit ? $sc->desc() :'','simple'));
		$form->addElement(new RMFormFile(__('Image file','dtransport'),'image',45, $xoopsModuleConfig['image']*1024),$edit ? '':true);
	
		if ($edit){
			$img = "<img src='".$sc->url('ths')."' border='0' />";
			$form->addElement(new RMFormLabel(__('Current image','dtransport'),$img));	
		}	

        $form->addElement(new RMFormHidden('p','cpanel'));
		$form->addElement(new RMFormHidden('action', 'screens'));
        $form->addElement(new RMFormHidden('id',$item->id()));
        $form->addElement(new RMFormHidden('op', 'save'));
		$form->addElement(new RMFormHidden('screen', $edit ? $sc->id() : 0));
		$buttons =new RMFormButtonGroup();
		$buttons->addButton('sbt',$edit ? __('Save Changes','dtransport') : __('Save Screenshot','dtransport'),'submit');
		$buttons->addButton('cancel',__('Cancel','dtransport'),'button', 'onclick="window.location=\''.DT_URL.($mc['permalinks']?'/cp/screens/'.$item->getVar('nameid').'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()).'\';"');

		$form->addElement($buttons);
	
		$xoopsTpl->assign('formscreens',$form->render());

	}
    
    $tpl->add_xoops_style('cpanel.css','dtransport');
    $tpl->add_head_script('$(document).ready(function(){
        
        $("a.delete").click(function(){
            if(!confirm("'.__('Do you really want to delete selected images?','dtransport').'")) return false;
        });
        
    });');
    
    $xoopsTpl->assign('lang_id',__('ID','dtransport'));
    $xoopsTpl->assign('lang_title', __('Title','dtransport'));
    $xoopsTpl->assign('lang_desc', __('Description','dtransport'));
    $xoopsTpl->assign('lang_opts', __('Options','dtransport'));
    $xoopsTpl->assign('lang_edit', __('Edit','dtransport'));
    $xoopsTpl->assign('lang_delete', __('Delete','dtransport'));
    $xoopsTpl->assign('lang_image', __('Image','dtransport'));
    $xoopsTpl->assign('lang_deletescreen',_MS_DT_DELETESCREEN);
    $xoopsTpl->assign('lang_deletescreens',_MS_DT_DELETESCREENS);
    $xoopsTpl->assign('edit', $edit);

	include ('footer.php');

}


/**
* @desc almacena la informacion de la pantalla en la base de datos
**/
function dt_save_screens($edit=0){
	global $item, $xoopsModuleConfig, $screen, $mc;	
    
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

	if ($edit){

		//Verificamos que la pantalla exista
		$sc=new DTScreenshot($screen);
		if ($sc->isNew())
			redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Specified screenshot is not valid!','dtransport'));

		//Comprueba que el título de la pantalla no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_screens')." WHERE title='$title' AND id_soft=".$item->id()." AND id_screen!=".$sc->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0)
			redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/edit/'.$sc->id():'/?p=cpanel&amp;action=screens&amp;id='.$item->id().'&amp;op=edit&amp;screen'.$sc->id()),1, __('Already exist another screenshot with the same name!','dtransport'));	

	}else{

		//Comprueba que el título de la pantalla no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_screens')." WHERE title='$title' AND id_soft=".$item->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0)
			redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Already exist another screenshot with same name!','dtransport'));
		
        $sc=new DTScreenshot();

	}

	$sc->setTitle($title);
	$sc->setDesc($desc);
	$sc->setDate(time());
	$sc->setSoftware($item->id());
	
	//Cargamos la imagen
    // Directorio de almacenamiento
    
    if(isset($_FILES['image']) && $_FILES['image']['name']!=''){
        
        $dir = XOOPS_UPLOAD_PATH.'/screenshots';
        
        // Eliminamos la imagen existente
        if($edit){
            $dir .= '/'.date('Y', $sc->date()).'/'.date('m', $sc->date());
            unlink($dir.'/'.$sc->image());
            unlink($dir.'/ths/'.$sc->image());
            $dir = XOOPS_UPLOAD_PATH.'/screenshots';
        }
        
        if (!is_dir($dir))
            mkdir($dir, 511);

        $dir .= '/'.date('Y', time());
        if (!is_dir($dir))
            mkdir($dir, 511);

        $dir .= '/'.date('m',time());
        if (!is_dir($dir))
            mkdir($dir, 511);

        if (!is_dir($dir.'/ths'))
            mkdir($dir.'/ths', 511);

        if(!is_dir($dir))
            redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Image could not be upload due to an internal error!','dtransport'));
        
	    include RMCPATH.'/class/uploader.php';
	    $uploader = new RMFileUploader($dir, $mc['image']*1024, array('jpg','gif','png'));
	    $err = array();
        if (!$uploader->fetchMedia('image'))
            redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Image could not be upload due to an internal error!','dtransport'));
        
        if (!$uploader->upload())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Image could not be upload due to an internal error!','dtransport'));
            
        $sc->setImage($uploader->getSavedFileName());
        
        // Resize image
        $thumb = explode(":",$mc['size_ths']);
        $big = explode(":",$mc['size_image']);
        $sizer = new RMImageResizer($dir.'/'.$sc->getVar('image'), $dir.'/ths/'.$sc->getVar('image'));

        // Thumbnail
        if(!isset($thumb[2]) || $thumb[2]=='crop'){
            $sizer->resizeAndCrop($thumb[0], $thumb[1]);
        } else {
            $sizer->resizeWidthOrHeight($thumb[0], $thumb[1]);
        }

        // Full size image
        $sizer->setTargetFile($dir.'/'.$sc->image());
        if(!isset($big[2]) || $big[2]=='crop'){
            $sizer->resizeAndCrop($big[0], $big[1]);
        } else {
            $sizer->resizeWidthOrHeight($big[0], $big[1]);
        }
    }
	
	if (!$sc->save()){
		if ($sc->isNew())
			redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Already exist another screenshot with the same name!','dtransport'));    
		 else
			redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/edit/'.$sc->id():'/?p=cpanel&amp;action=screens&amp;id='.$item->id().'&amp;op=edit&amp;screen'.$sc->id()),1, __('Already exist another screenshot with the same name!','dtransport'));    
			
	}else
		redirect_header(DT_URL.($mc['permalinks']?'/cp/screens/'.$item->id().'/':'/?p=cpanel&amp;action=screens&amp;id='.$item->id()),1, __('Screenshot saved successfully!','dtransport'));    

}


/**
* @desc Elmina las pantallas de la base de datos
**/
function dt_delete_screen(){
    global $mc, $item, $screen, $tpl, $xoopsTpl;
    
    $sc = new DTScreenshot($screen);
    
    if($sc->isNew())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->id() : '/?p=cpanel&amp;action=screens&amp;id='.$item->id()), 1, __('Specified screenshot is not valid!','dtransport'));

	if(!$sc->delete())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->id() : '/?p=cpanel&amp;action=screens&amp;id='.$item->id()), 1, __('Screenshot could not be deleted! Please try again.','dtransport'));
    
    redirect_header(DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->id() : '/?p=cpanel&amp;action=screens&amp;id='.$item->id()), 1, __('Screenshot deleted successfully!','dtransport'));

}
