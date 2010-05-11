<?php
// $Id$
// --------------------------------------------------------------
// MetaSEO plugin for Common Utilities
// Optimize searchs by adding meta description and keywords to you rtemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MetaseoPluginRmcommonPreload
{	
	public function eventRmcommonCurrentModuleMenu($menu){
        global $xoopsModule;
        
        if($xoopsModule->getVar('dirname')!='rmcommon') return $menu;
        
        $option = array(
            'title'=>__('Meta Optimizer','metaseo'),
            'link' => 'plugins.php?action=configure&plugin=metaseo',
            'selected' => ''
        );
        
        foreach($menu as $i => $item){
            if ($item['location']!='plugins') continue;
            
            $menu[$i]['options'][] = $option;
            break;
            
        }
        
        return $menu;
        
    }
    
    /**
    * This event can be used on any module, theme or plugin
    * to add description and keywords to meta headers
    * 
    * @param string $desc
    * @param string $keys
    */
    public function eventRmcommonMetaSeo($desc, $keys){
		
		$meta = '<meta name="description" content="'.$desc.'" />';
		$meta .= '<meta name="keywords" content="'.$keys.'" />';
		
		RMTemplate::get()->add_head($meta);
		
    }
	
}
