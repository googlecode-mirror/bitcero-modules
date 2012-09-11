<?php
// $Id$
// --------------------------------------------------------------
// Minifier Plugin for Common Utilities
// Minify all scripts and styles sheets added trough RMTemplate
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class MinifierPluginRmcommonPreload
{
    
    public function eventRmcommonSavePluginSettings($options, $dir, $plugin){
        
        if($dir!='minifier') return true;
        
        if(!is_dir(XOOPS_CACHE_PATH.'/minifier'))
            mkdir(XOOPS_CACHE_PATH.'/minifier', 511);
        
        $file = XOOPS_CACHE_PATH.'/minifier/config.php';
        file_put_contents($file, '<?php'."\n");
        foreach($options as $name => $value){
            
            if(substr($name, 0, 8)=='options_'){
                
                file_put_contents($file, '$min_serveOptions[\''.$name.'\'] = '.$value['value'].";\n", FILE_APPEND);
                
            } else {
                
                file_put_contents($file, '$'.$name.' = '.($value['valuetype']!='int' ? '\''.$value['value'].'\'' : $value['value']).";\n", FILE_APPEND);
                
            }
            
        }
        
        return true;
        
    }
    
    private function check_config(){
        
        $rmf = RMFunctions::get();
        $config = $rmf->plugin_settings('minifier', true);
        
        if(is_file(XOOPS_CACHE_PATH.'/minifier/config.php'))
            return true;
        
        
        
    }
        
    public function eventRmcommonGetScripts($scripts){
        
        $rmf = RMFunctions::get();
        $config = $rmf->plugin_settings('minifier', true);
        
        if(!$config['enable']) return $scripts;
        
        $mini = array();
        
        foreach($scripts as $id => $script){
            
            if(strpos($script['url'], 'jquery.min.js')!==FALSE ||
                strpos($script['url'], 'jquery-ui.min.js')!==FALSE ||
                strpos($script['url'], 'tiny_mce.js')!==FALSE){
                $mini[$id] = array('url'=>$script['url'],'type'=>$script['type'],'more'=>$script['more']);
                continue;
            }
            
            $url = parse_url($script['url']);
            $info = pathinfo($url['path']);
            $path = str_replace($info['basename'], '', $url['path']);
            $identifier = sprintf("%u", crc32($path.$script['type']));
            
            $add = !isset($mini[$identifier]) ? RMCURL.'/plugins/minifier/min/f='.$url['path'] : $mini[$identifier]['url'].','.$url['path'];
            $mini[$identifier] = array(
                'url' => $add,
                'type' => $script['type']
            );
        }
        return $mini;
        
    }
    
    public function eventRmcommonGetStyles($styles){
        
        $rmf = RMFunctions::get();
        $config = $rmf->plugin_settings('minifier', true);
        
        if(!$config['enable']) return $styles;
        
        $mini = array();
        
        foreach($styles as $id => $style){
                       
            $url = parse_url($style['url']);
            $info = pathinfo($url['path']);
            $path = str_replace($info['basename'], '', $url['path']);
            $identifier = sprintf("%u", crc32($path.$style['media']));
            
            $add = !isset($mini[$identifier]) ? RMCURL.'/plugins/minifier/min/f='.$url['path'] : $mini[$identifier]['url'].','.$url['path'];
            $mini[$identifier] = array(
                'url' => $add,
                'type' => $style['type'],
                'media' => $style['media'],
                'rel' => 'stylesheet'
            );
        }
        return $mini;
        
    }
    
}