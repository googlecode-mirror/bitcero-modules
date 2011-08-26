<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GalleriesController implements iCommentsController
{
    public function increment_comments_number($comment){
        
        return true;

    }
    
    public function reduce_comments_number($comment){
        
        return true;
        
    }
    
    public function get_item($params, $com, $url = false){
        static $cimgs;
        static $csets;
        
        $params = urldecode($params);        
        parse_str($params);
        
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gstag.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';
        
        if(isset($set) && $set>0){

            if (isset($csets[$set])){
                return $csets[$set]->title();
            }
            
            $sobj = new GSSet($set);
            if($sobj->isNew())
                return __('Unknow element','galleries');
            
            $ret = $sobj->title();
            $csets[$set] = $sobj;
            
            return $ret;
            
        } elseif(isset($image) && $image>0) {
            
            if (isset($cimgs[$image])){
                $ret = $cresources[$res]->getVar('title');
                return $ret;
            }
            
            $img = new GSImage($image);
        
            if($img->isNew())
                return __('Unknow element','docs');
            
            $ret = $img->title(true);
            $cimgs[$image] = $img;
            
            return $ret;
            
        }
        
        
    }
    
    public function get_item_url($params, $com){
        static $cimgs;
        static $csets;
        
        $params = urldecode($params);        
        parse_str($params);
        
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gstag.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';
        
        if(isset($set) && $set>0){

            if (isset($csets[$set])){
                $ret = $csets[$set]->url().'#comment-'.$com->id();
                return $ret;
            }
            
            $sobj = new GSSet($set);
            if($sobj->isNew())
                return '';
            
            $ret = $sobj->url().'#comment-'.$com->id();
            $csets[$set] = $sobj;
            
            return $ret;
            
        } elseif(isset($image) && $image>0) {
            
            if (isset($cimgs[$image])){
                $ret = $cimgs[$image]->permalink().'#comment-'.$com->id();
                return $ret;
            }
            
            $img = new GSImage($image);
        
            if($img->isNew())
                return '';
            
            $ret = $img->permalink().'#comment-'.$com->id();
            $cimgs[$image] = $img;
            
            return $ret;
            
        }
    }
    
    public function get_main_link(){
        
        $mc = RMUtilities::module_config('mywords');
        
        if ($mc['permalinks']>1){
            return XOOPS_URL.$mc['basepath'];
        } else {
            return XOOPS_URL.'/modules/mywords';
        }
        
    }
    
}
