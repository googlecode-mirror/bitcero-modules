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
include '../admin/header.php';

function send_error($message){
    $data['error'] = 1;
    $data['message'] = $message;
    echo json_encode($data);
    die();
}

function saveBulkImages(){
    global $util, $mc, $xoopsUser;
    
    XoopsLogger::getInstance()->activated = false;
    XoopsLogger::getInstance()->renderingEnabled = false;
    
    set_time_limit(0);
    
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

    if (!$image->save()){
        unlink($uploader->savedDestination);
        send_error(__('File could not be inserted to database!','galleries'));
    }

    $ret['message'] = '1';
    $ret['id'] = $image->id();
    echo json_encode($ret);
    die();
    
    /*
    foreach ($_FILES['image']['name'] as $k => $v){
        if ($v=='') continue;
        
        
        //Imagen
        $filename = '';
        
        if ($up->fetchMedia('image',$k)){

            if (!$up->upload()){
                $errors .= sprintf(__('Image could not be uploaded due to next reason: %s','galleries'), $up->getErrors());
                continue;
            }
                    
            $filename = $up->getSavedFileName();
            $fullpath = $up->getSavedDestination();
            
            $thSize = $mc['image_ths'];
            $imgSize = $mc['image'];
            
            if ($thSize[0]<=0) $thSize[0] = 100;
            if (!isset($thSize[1]) || $thSize[1]<=0) $thSize[1] = $thSize[0];
            
            if ($imgSize[0]<=0) $imgSize[0] = 500;
            if (!isset($imgSize[1]) || $imgSize[1]<=0) $imgSize[1] = $imgSize[0];
            
            // Almacenamos la imágen original
            if ($mc['saveoriginal']){
                copy($fullpath, $mc['storedir'].'/originals/'.$filename);
            }
            
            // Redimensionamos la imagen
            $redim = new RMImageResizer($fullpath, $fullpath);
            switch ($mc['redim_image']){
                case 0:
                    //Recortar miniatura
                    $redim->resizeWidth($imgSize[0]);
                    $redim->setTargetFile($folderths."/$filename");                
                    $redim->resizeAndCrop($thSize[0],$thSize[1]);
                break;    
                case 1: 
                    //Recortar imagen grande
                    $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
                    $redim->setTargetFile($folderths."/$filename");
                    $redim->resizeWidth($thSize[0]);            
                break;
                case 2:
                    //Recortar ambas
                    $redim->resizeWidthOrHeight($imgSize[0],$imgSize[1]);
                    $redim->setTargetFile($folderths."/$filename");
                    $redim->resizeAndCrop($thSize[0],$thSize[1]);
                break;
                case 3:
                    //Redimensionar
                    $redim->resizeWidth($imgSize[0]);
                    $redim->setTargetFile($folderths."/$filename");
                    $redim->resizeWidth($thSize[0]);
                break;            
            }


        }
        
        //Fin de Imagen
        $img->setImage($filename);
        
        if ($up->getErrors()==''){
            if (!$img->save()){
                $errors .= sprintf(__('Image could not be inserted in database!','galleries'), $v)." (".$img->errors().")";
            } else {
                $user->addPic();
                $img->setTags($ret);
            
                //Albumes
                if (!empty($albums)){
                    foreach ($albums as $k => $v){
                        $album = new GSSet($v);
                        $album->addPic($img->id());
                    }
                }
            }
        }else{
            $errors .= $up->getErrors();
        }

        
        ++$k;
    }

    if($errors!=''){
        redirectMsg('./images.php?'.$ruta,__('Errors ocurred while trying to upload images.','galleries').$errors,1);
        die();
    }else{
        redirectMsg('./images.php?'.$ruta,__('Images uploaded successfully!','galleries'),0);
        die();
    }*/

}

$op = rmc_server_var($_REQUEST, 'op', '');

switch($op){
    case 'new':
        formImages();
    break;
    case 'newbulk':
        formBulkImages();
    break;
    case 'edit':
        formImages(1);
    break;
    case 'save':
        saveImages();
    break;
    case 'saveedit':
        saveImages(1);
    break;
    case 'savebulk':
        XoopsLogger::getInstance()->activated = false;
        XoopsLogger::getInstance()->renderingEnabled = false;
        saveBulkImages();
    break;
    case 'delete':
        deleteImages();
    break;
    case 'public':
        publicImages(2);
    break;
    case 'private':
        publicImages(0);
    break;
    case 'privatef':
        publicImages(1);
    break;
    default:
        showImages();
        break;
}

