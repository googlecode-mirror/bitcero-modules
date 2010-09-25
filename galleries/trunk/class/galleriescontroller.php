<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GalleriesController
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
        
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gstag.class.php';
        
        if(isset($set) && $set>0){

            if (isset($csets[$set])){
                $ret = '<a href="'.$csets[$set]->url().'#comment-'.$com->id().'">'.$csets[$set]->title().'</a>';
                return $ret;
            }
            
            $sobj = new GSSet($set);
            if($sobj->isNew())
                return __('Unknow element','galleries');
            
            $ret = '<a href="'.$sobj->url().'#comment-'.$com->id().'">'.$sobj->title().'</a>';
            $csets[$set] = $sobj;
            
            return $ret;
            
        } elseif(isset($image) && $image>0) {
            
            if (isset($cimgs[$image])){
                $ret = '<a href="'.$cimgs[$image]->permalink().'#comment-'.$com->id().'">'.$cresources[$res]->getVar('title').'</a>';
                return $ret;
            }
            
            $img = new GSImage($image);
        
            if($img->isNew())
                return __('Unknow element','docs');
            
            $ret = '<a href="'.$img->permalink().'#comment-'.$com->id().'">'.$img->title(true).'</a>';
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
