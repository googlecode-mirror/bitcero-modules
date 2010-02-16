<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MywordsRmcommonPreload
{
	
    public function eventRmcommonLoadRightWidgets($widgets){
		global $xoopsModule;
        
		if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='mywords'))
			return $widgets;
		
	    if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='new_post'){
			include_once '../widgets/widget_publish.php';
			$widgets[] = mw_widget_publish();
			
			include_once '../widgets/widget_categories.php';
			$widgets[] = mw_widget_categories();
	        
	        include_once '../widgets/widget_tags.php';
	        $widgets[] = mw_widget_addtags();
	        
	    }
        
		return $widgets;
	}
    
    public function eventRmcommonGetSystemTools($tools){
        
        load_mod_locale('mywords', 'admin_');
        
        $rtn = array(
            'link'  => '../mywords/admin/',
            'icon'  => '../mywords/images/icon16.png',
            'caption' => __('MyWords Administration', 'admin_mywords')
        );
        
        $tools[] = $rtn;
        
        return $tools;
        
    }
    
    public function eventRmcommonLoadingSingleEditorimgs($items, $url){
    	
    	if (FALSE === strpos($url, 'modules/mywords/admin/posts.php')) return $items;
    	
		parse_str($url);
		if (!isset($id) || $id<=0) return $items;
		
		xoops_load('mwpost.class','mywords');
		xoops_load('mwfunctions','mywords');
		
		$post = new MWPost($id);
		if ($post->isNew()) return $items;
		
		$items['links']['post'] = array('caption'=>__('Link to post','mywords'),'value'=>$post->permalink());
		return $items;
		
    }
	
}
