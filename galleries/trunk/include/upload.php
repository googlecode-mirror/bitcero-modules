<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
define('RMCLOCATION','images');
include '../../../mainfile.php';

define('GS_URL', XOOPS_URL.'/modules/galleries');
define('GS_PATH', XOOPS_ROOT_PATH.'/modules/galleries');

include GS_PATH.'/class/gsuser.class.php';
include GS_PATH.'/class/gstag.class.php';
include GS_PATH.'/class/gsimage.class.php';
include GS_PATH.'/class/gsset.class.php';

function send_error($message){
    $data['error'] = 1;
    $data['message'] = $message;
    echo json_encode($data);
    die();
}

function saveBulkImages(){
    
    $mc = RMUtilities::module_config('galleries');
    
    XoopsLogger::getInstance()->activated = false;
    XoopsLogger::getInstance()->renderingEnabled = false;
      
    set_time_limit(0);
    
    $security = TextCleaner::getInstance()->decrypt(rmc_server_var($_POST, 'rmsecurity', 0), true);
    $data = explode("|", $security);
    
    $xoopsUser = new XoopsUser($data[1]);
    
    if (!isset($data[1]) || $data[1]!=XOOPS_URL.'/modules/galleries/admin/images.php'){
        send_error(__('You are not allowed to do this action','galleries'));
    }
    
    foreach ($_POST as $k => $v){
        $$k = $v;
    }
    $ruta = "page=$page&search=$search&owner=$uid&sort=$sort&mode=$mode";
    
    if ($xoopsUser->uid()==$uid){
        $xu = $xoopsUser;
    } else {
        $xu = new XoopsUser($uid);
    }
    

    //Verificamos si el usuario se encuentra registrado    
    $user = new GSUser($xu->uname());
    if($user->isNew()){
        //Insertamos información del usuario
        $user->setUid($uid);
        $user->setUname($xu->uname());
        $user->setQuota($mc['quota']*1024*1024);
        $user->setDate(time());

        if(!$user->save()){
            send_error(__('User owner could not be created!','galleries')."<br />".$user->errors());
            die();
        }else{
            mkdir($mc['storedir']."/".$user->uname());
            mkdir($mc['storedir']."/".$user->uname()."/ths");
            mkdir($mc['storedir']."/".$user->uname()."/formats");
        }
    } else {
        @mkdir($mc['storedir']."/".$user->uname());
        @mkdir($mc['storedir']."/".$user->uname()."/ths");
        @mkdir($mc['storedir']."/".$user->uname()."/formats");
    }
    
    // Insertamos las etiquetas
    $tgs = explode(",",$tags);
    /**
    * @desc Almacena los ids de las etiquetas que se asignarán a la imágen
    */
    $ret = array(); 
    foreach ($tgs as $k){
        $k = trim($k);
        if ($k=='') continue;
        // Comprobamos que la palabra tenga la longitud permitida
        if(strlen($k)<$mc['min_tag'] || strlen($k)>$mc['max_tag']){
            continue;
        }
        // Creamos la etiqueta
        $tag = new GSTag($k);
        if (!$tag->isNew()){
            // Si ya existe nos saltamos
            $ret[] = $tag->id(); 
            continue;
        }

        $tag->setTag($k);
        if ($tag->save()){
            $ret[] = $tag->id();
        }
    }    

    $errors = '';
    $k = 1;
    include_once RMCPATH.'/class/uploader.php';
    $updir = $mc['storedir']."/".$xu->uname();
    $upths = $mc['storedir']."/".$xu->uname()."/ths";
    
    // Cargamos la imágen
    if (!file_exists($updir)){
        mkdir($updir,511);
    }

    if (!file_exists($upths)){
        mkdir($upths, 511);
    }

    $uploader = new RMFileUploader($updir, $mc['size_image']*1024, array('gif', 'jpg', 'jpeg', 'png'));

    $err = array();
    if (!$uploader->fetchMedia('Filedata')){
        send_error($uploader->getErrors());
    }

    if (!$uploader->upload()){
        send_error($uploader->getErrors());
    }

    // Insertamos el archivo en la base de datos
    $img = new GSImage();
    $img->setTitle($uploader->savedFileName);
    $img->setOwner($uid);
    $img->setPublic(2);
    $img->setCreated(time());
    $img->setImage($uploader->getSavedFileName());

    if (!$img->save()){
        unlink($uploader->savedDestination);
        send_error(__('File could not be inserted to database!','galleries'));
    }
    
    $user->addPic();
    $img->setTags($ret);
            
    //Albumes
    $sets = explode(",", $sets);
    if (!empty($sets)){
        foreach ($sets as $k => $v){
            $album = new GSSet($v);
            $album->addPic($img->id());
        }
    }

    $ret['message'] = '1';
    $ret['id'] = $img->id();
    echo json_encode($ret);
    die();

}

/**
* Resize images
*/
function gs_resize_images(){
    global $xoopsUser, $xoopsLogger, $xoopsSecurity;
    
    set_time_limit(0);
    
    $mc = RMUtilities::module_config('galleries');
    
    $params = rmc_server_var($_GET, 'data','');
    $id = rmc_server_var($_GET, 'img', 0);
    
    if ($params==''){
        send_error(__('Unauthorized!','galleries'));
    }
    
    if ($id<=0){
        send_error(__('Invalid image!','galleries'));
    }
    
    $params = TextCleaner::decrypt($params);
    $data = explode('|', $params);
    
    if ($data[0]!=$xoopsUser->uid()){
        send_error(__('Unauthorized!','galleries'));
    }
    
    if ($data[1]!=GS_URL.'/admin/images.php'){
        send_error(__('Unauthorized!','galleries'));
    }
    
    if (!$xoopsSecurity->check(false, $data[2])){
        send_error(__('Unauthorized!','galleries'));
    }
    
    $image = new GSImage($id);
    if ($image->isNew()){
        send_error(__('Image not found!','galleries'));
    }
    
    $thSize = $mc['image_ths'];
    $imgSize = $mc['image'];
    
    
    if ($thSize[0]<=0) $thSize[0] = 100;
    if (!isset($thSize[1]) || $thSize[1]<=0) $thSize[1] = $thSize[0];
            
    if ($imgSize[0]<=0) $imgSize[0] = 500;
    if (!isset($imgSize[1]) || $imgSize[1]<=0) $imgSize[1] = $imgSize[0];
    
    $xu = new GSUser($image->owner(), 1);
    
    $updir = rtrim($mc['storedir'], '/')."/".$xu->uname();
    $upurl = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $updir);
    $upths = rtrim($mc['storedir'], '/')."/".$xu->uname()."/ths";
    
    $width = 0;
    $tfile = '';
    
    // Almacenamos la imágen original
    if ($mc['saveoriginal']){
        copy($updir.'/'.$image->image(), $mc['storedir'].'/originals/'.$image->image());
    }

    $fd = pathinfo($updir.'/'.$image->image());
    $filename = $image->image();
        
    $redim = new RMImageResizer($updir.'/'.$image->image(), $updir.'/'.$image->image());
    switch ($mc['redim_image']){
        case 0:
            //Recortar miniatura
            $redim->resizeWidth($imgSize[0]);
            $redim->setTargetFile($upths."/$filename");                
            $redim->resizeAndCrop($thSize[0],$thSize[1]);
            break;    
        case 1: 
            //Recortar imagen grande
            $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
            $redim->setTargetFile($upths."/".$image->image());
            $redim->resizeWidth($thSize[0]);            
            break;
        case 2:
            //Recortar ambas
            $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
            $redim->setTargetFile($upths."/$filename");
            $redim->resizeAndCrop($thSize[0],$thSize[1]);
            break;
        case 3:
            //Redimensionar
            $redim->resizeWidth($imgSize[0]);
            $redim->setTargetFile($upths."/$filename");
            $redim->resizeWidth($thSize[0]);
            break;            
    }
    
    $tfile = $upurl.'/ths/'.$image->image();
    
    $ret['message'] = sprintf(__('%s done!', 'galleries'), $image->image());
    $ret['done'] = 1;
    $ret['file'] = $tfile;
    $ret['title'] = $image->image();
    echo json_encode($ret);
        
    die();
}

$op = rmc_server_var($_REQUEST, 'op', '');

switch($op){
    case 'savebulk':
        XoopsLogger::getInstance()->activated = false;
        XoopsLogger::getInstance()->renderingEnabled = false;
        saveBulkImages();
        break;
    case 'resize':
        XoopsLogger::getInstance()->activated = false;
        XoopsLogger::getInstance()->renderingEnabled = false;
        gs_resize_images();
        break;
}

