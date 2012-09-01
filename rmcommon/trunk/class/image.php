<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Class to handle images created from Image Manager
*/

class RMImage extends RMObject
{
    /**
     * Used when a default size is specified
     * @var int
     */
    private $selected_size = 0;
    /**
     * Stores all sizes for category
     * @param array
     */
    private $sizes = array();

	public function __construct($id=null){
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_dbtable = $this->db->prefix("rmc_images");
        $this->setNew();
        $this->initVarsFromTable();
        if ($id==null){
            return;
        }
        
        if ($this->loadValues($id)){
            $this->unsetNew();
        }
	}
	
	public function id(){
		return $this->getVar('id_img');
	}

    /**
     * Load data from database table according to given parameters
     * @param string Params must be passed as string separated by ":" (e.g. 1:3) where second parameter is selected size if any
     * return bool
     */
    public function load_from_params($params){

        if($params=='') return false;
        $p = explode(":", $params);

        if(intval($p[0])<=0) return false;

        if($this->loadValues(intval($p[0]))) $this->unsetNew();
        $this->selected_size = intval($p[1]);
        
        $params[2] = $params[2]!='' ? urldecode($params[2]) : '';
        $params[3] = $params[3]!='' ? urldecode($params[3]) : '';
        
        return $params;

    }

    /**
     * Get all image sizes
     * @return array
     */
    public function get_sizes_data(){
        if(empty($this->sizes)){
            $cat = new RMImageCategory($this->getVar('cat'));
            if($cat->isNew()) return false;
            // Get sizes
            $this->sizes = $cat->getVar('sizes');
        }

        return $this->sizes;
    }

    /**
     * Constructs the URL for image according to defined size
     * @param int Specific size to construct the url
     * @return string
     */
    public function url($size=-1){

        if($size<0 && $this->selected_size>0)
            $size = $this->selected_size;

        if($this->isNew()) return false;

        $this->get_sizes_data();
        
        $url = XOOPS_UPLOAD_URL.'/'.date('Y', $this->getVar('date')).'/'.date('m',$this->getVar('date')).'/';
        if($size>=count($this->sizes)){
            $url .= $this->getVar('file');
            return $url;
        }

        $info = pathinfo($this->getVar('file'));

        $url .= 'sizes/'.$info['filename'].'_'.$this->sizes[$size]['width'].'x'.(isset($this->sizes[$size]['height'])?$this->sizes[$size]['height']:'');
        $url .= '.'.$info['extension'];
        return $url;

    }

    public function get_smallest(){

        if($this->isNew()) return false;

        $this->get_sizes_data();
        $ps = 0; // Previous size
        $small = 0;
        
        foreach($this->sizes as $k => $size){
            $ps = $ps==0?$size['width']:$ps;
            if($size['width']<$ps){
                $ps = $size['width'];
                $small = $k;
            }

        }

        return $this->url($small);

    }
    
    /**
    * Get all image versions with url
    * @return array
    */
    public function get_all_versions(){
        
        if($this->isNew()) return false;
        
        $this->get_sizes_data();
        $ret = array();
        foreach($this->sizes as $k => $size){
            $ret[$size['name']] = $this->url($k);
        }
        
        return $ret;
    }
    
    public function save(){
        
        if ($this->isNew()){
            return $this->saveToTable();
        } else {
            return $this->updateTable();
        }
        
    }
    
    public function delete(){
        $path = XOOPS_UPLOAD_PATH.'/'.date('Y', $this->getVar('date')).'/'.date('m',$this->getVar('date')).'/';
        $sizes = $this->get_sizes_data();
        
        $info = pathinfo($this->getVar('file'));
        foreach($sizes as $size){
            unlink($path.'sizes/'.$info['filename'].'_'.$this->sizes[$size]['width'].'x'.(isset($this->sizes[$size]['height'])?$this->sizes[$size]['height']:'').'.'.$info['extension']);
        }
        unlink($path.$this->getVar('file'));
        
		return $this->deleteFromTable();
    }
	
}
