<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once RMCPATH.'/include/tpl_functions.php';

/**
* This file can handle templates for all modules and themes
*/
class RMTemplate
{
    private $type = 'front';
    /**
    * Stores the information for 'HEAD' section of template
    */
    public $tpl_head = array();
    /**
    * Stores the scripts information to include in theme
    */
    public $tpl_scripts = array();
    /**
    * Stores all styles for HEAD section
    */
    public $tpl_styles = array();
    /**
    * Menu options for current element
    */
    private $tpl_menus = array();
    /**
     * Template Vars
     */
    private $tpl_vars = array();
    /**
    * Messages for template
    */
    private $messages = array();
    /**
    * Menus for admin gui
    */
    private $menus = array();
    /**
    * Toolbar for admin gui
    */
    private $toolbar = array();
    /**
    * Help link
    */
    private $help_link = '';

    /**
     * At this moment this method do nothing
     * Maybe later i will add some functionality... i must to think ;)
     */
    function __construct(){
	//if (!function_exists("xoops_cp_header")) return;
        $this->add_jquery(true);
    }

    /**
     * Use this method to instantiate EXMTemplate
     * @staticvar <type> $instance
     * @return object EXMTemplate
     */
    public function get(){
        static $instance;

        if (!isset($instance)) {
            $instance = new RMTemplate();
	    }

        return $instance;
        
    }

    /**
    * 
    */
    public function header(){
		ob_start();
    }
    
    public function footer(){
        global $xoopsModule, $rmc_config, $xoopsConfig, $xoopsModuleConfig;
		$content = ob_get_clean();
		ob_start();
        
        $rmc_config = RMFunctions::configs();
		$theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';
		
		if (!file_exists(RMCPATH.'/themes/'.$theme.'/admin_gui.php')){
			$theme = 'default';
		}
		
		$rm_theme_url = RMCURL.'/themes/'.$theme;
        
        // Check if there are redirect messages
        $rmc_messages = array();
        if (isset($_SESSION['rmMsg'])){
            foreach ($_SESSION['rmMsg'] as $msg){
                $rmc_messages[] = $msg;
            }
            unset($_SESSION['rmMsg']);
        }
		
		include_once RMCPATH.'/themes/'.$theme.'/admin_gui.php';
		$output = ob_get_clean();
		
		$output = RMEvents::get()->run_event('rmcommon.admin.output', $output);
		
		echo $output;
    }
    
    /**
    * Get a template from Current RMCommon Theme
    * @param string Template file name
    * @param string Elemernt type: module or plugin
    * @param string Module name
    * @param string Plugin name, only when type = plugin
    * @return string Template path
    */
    public function get_template($file, $type='module',$module='rmcommon',$plugin=''){
		global $rmc_config, $xoopsConfig;
        
        
        $type = $type=='' ? 'module' : $type;
        
        if (!function_exists("xoops_cp_header")){
            
            $theme = $xoopsConfig['theme_set'];
            $where = XOOPS_THEME_PATH.'/'.$theme;
            $where .= $type=='module' ? '/modules/' : '/'.$plugin.'s/';
            $where .= $module.($plugin!='' ? '/'.$plugin : '');

            if(is_file($where.'/'.$file)) return $where.'/'.$file;
            
            $where = XOOPS_ROOT_PATH.'/modules/'.$module.'/templates';
            $where .= $type!='module' ? "/$type" : '';
            $where .= "/$file";
            
            
            if(is_file($where)) return $where;

        }
		
		$theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';
		
		$where = $type=='module' ? 'modules/'.$module : ($type=='plugin' ? $module.'/'.$plugin : 'modules/rmcommon');
        
		$lpath = RMCPATH.'/themes/'.$theme.'/'.$where.'/'.$file;

		if (!is_dir(RMCPATH.'/themes/'.$theme)){
			$theme = 'default';
		}
		
		if (file_exists($lpath))
			return $lpath;
		
		if ($type=='plugin'){
			return RMCPATH.'/plugins/'.$plugin.'/templates/'.$file;
		} else {
			return XOOPS_ROOT_PATH.'/'.$where.'/templates/'.$file;
		}
		
    }
    
    /**
    * Get template path for front section
    * @param string File path inside module folder
    * @param string Module directory name
    * @return string
    */
    public function tpl_path($file, $module, $subdir = 'templates'){
		global $xoopsConfig;
		
		$theme = $xoopsConfig['theme_set'];
		$subdir = $subdir == '' ? 'templates' : $subdir;

		if(!is_dir(XOOPS_ROOT_PATH.'/modules/'.$module)) return;
		
		$tpath = XOOPS_THEME_PATH.'/'.$theme.'/modules/'.$module.'/'.($subdir!='templates'?$subdir.'/':'').$file;
		
		if(is_file($tpath))
			return $tpath;
			
		return XOOPS_ROOT_PATH.'/modules/'.$module.'/'.$subdir.'/'.$file;
		
    }
    
    /**
    * Set the location identifier for current page
    * This identifier will help to RMCommon to find widgets, forms, etc
    */
    public function location_id($id){
		
    }
    
    /**
    * Add a help lint to manage diferents sections
    * @param string Link to help resource
    */
    public function set_help($link){
        //trigger_error(__('RMTemplate::set_help is deprecated. Use add_help instead.','rmcommon'), E_USER_WARNING);
        //$this->add_help($caption, $link);
        $this->help_link = $link;
    }
    
    public function help(){
        return $this->help_link;
    }
    
    public function add_help($caption, $link){
        $this->help_link[] = array(
            'caption' => $caption,
            'link' => $link
        );
    }
    
    /**
    * Add a message to show in theme
    * @param string Message to show
    * @param int Level of message (1 will show error)
    */
    public function add_message($message, $level=0){
		$this->messages[] = array('text'=>$message, 'level'=>$level);
    }
    /**
    * Get all messages
    * @return array
    */
    public function get_messages(){
		return $this->messages;
    }

    /**
    * Add elements to "HEAD" section of the page
    * @param string|array Elementos de HEAD
    * @return null
    */
    public function add_head($head){
	// Dynamic header (It must be be an array)
        if (is_array($head)):
            array_merge($this->tpl_head, $head);
        else:
            $this->tpl_head[] = $head;
        endif;
    }
    /**
    * Get all items in head
    * @return array
    */
    public function get_head(){
		return $this->tpl_head;
    }
    
    /**
    * Add scripts to theme.
    * Scripts can be added trough method {@link add_head} passing all "script" tag as parameter
    * but this method offers a shortest way.
    * @param string Script URL
    * @param string Script Type (text/javascript)
    * @return null
    */
    public function add_script($url,$type='text/javascript'){
   
            if (strpos($url, "?")>1){
                if (strpos($url, 'ver=')===FALSE){
                    $url .= "&amp;ver=".RMCVERSION;
                }
            } else {
                $url .= "?ver=".RMCVERSION;
            }
            
            $id = crc32($url.$type);
            if (isset($this->tpl_scripts[$id])) return;
            
            $this->tpl_scripts[$id] = array('url'=>$url,'type'=>$type);
    }
    
    /**
    * This function add a scritp directly from an element
    */
    public function add_local_script($file, $element='rmcommon', $subfolder='', $type='text/javascript', $more=''){
        global $rmc_config, $xoopsConfig;
        
        // Id for element
        $url = XOOPS_URL.'/modules/'.$element.'/'.$subfolder.'/js/'.$file;
        if (strpos($url, "?")>1){
            if (strpos($url, 'ver=')===FALSE){
                $url .= "&amp;ver=".RMCVERSION;
            }
        } else {
            $url .= "?ver=".RMCVERSION;
        }
        
        $id = crc32($url.$type.$more);
        
        if (isset($this->tpl_scripts[$id])) return;
        
        if (!defined('XOOPS_CPFUNC_LOADED')){
            $theme = isset($xoopsConfig['theme_set']) ? $xoopsConfig['theme_set'] : 'default';
            $themepath = XOOPS_THEME_PATH.'/'.$theme;
            $themeurl = XOOPS_THEME_URL.'/'.$theme;
        } else {
            $theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';
            $themepath = RMCPATH.'/themes/'.$theme;
            $themeurl = RMCURL.'/themes/'.$theme;
        }

        $theme_file = $themepath.'/js/'.$element.($element!='' ? '/' : '').($subfolder!='' ? $subfolder.'/' : '').$file;

        if (is_file($theme_file)){
            $url = $themeurl.'/js/'.($element!='' ? $element.'/' : '').($subfolder!='' ? $subfolder.'/' : '').$file;
        } else {
            $url = XOOPS_URL.'/'.($element!='' ? 'modules/'.$element.'/' : '').($subfolder!='' ? $subfolder.'/' : '').'js/'.$file;
        }
        
        if (strpos($url, "?")>1){
            if (strpos($url, 'ver=')===FALSE){
                $url .= "&amp;ver=".RMCVERSION;
             }
        } else {
            $url .= "?ver=".RMCVERSION;
        }
        
        $this->tpl_scripts[$id] = array('url'=>$url,'type'=>$type, 'more'=>$more);
        
    }
	
	public function add_theme_script($script, $theme='', $subfolder='', $type='text/javascript', $more=''){
        global $xoopsConfig, $rmc_config;

		if(defined('XOOPS_CPFUNC_LOADED')){
			
			$theme = $theme=='' ? $rmc_config['theme'] : $theme;
			$file = RMCPATH.'/themes/'.$theme.'/js/'.($subfolder!=''?$subfolder.'/':'').$script;
			$url = str_replace(RMCPATH, RMCURL, $file);
			
		} else {

			$theme = $theme=='' ? $xoopsConfig['theme_set'] :$theme;	
			$file = XOOPS_THEME_PATH.'/'.$theme.'/js/'.($subfolder!=''?$subfolder.'/':'').$script;
			$url = str_replace(XOOPS_THEME_PATH, XOOPS_THEME_URL, $file);
		
		}
		
		$id = crc32($script.$theme.$subfolder.$type.$more);
			
		if(!file_exists($file)) return;
			
		if (strpos($url, "?")>1){
			if (strpos($url, 'ver=')===FALSE){
				$url .= "&amp;ver=".RMCVERSION;
			}
		} else {
			$url .= "?ver=".RMCVERSION;
		}
			 
		$this->tpl_scripts[$id] = array(
			'url'=>$url,
			'type'=>$type,
			'more'=>$more
		);
        
    }
    /**
    * Add jQuery script to site header
    */
    public function add_jquery($ui=true){
        $this->add_script(RMCURL.'/include/js/jquery.min.js');
        if ($ui)
            $this->add_script(RMCURL.'/include/js/jquery-ui.min.js');
    }
    /**
   	* Get all scripts stored in class
   	*/
    public function get_scripts(){
        return $this->tpl_scripts;
    }
    /**
   	* Clear all scripts stores
   	*/
    public function clear_scripts(){
        $this->tpl_scripts = array();
    }
    /**
   	* Assign an array of scripts with values pairs url, type
   	*/
    public function add_scripts_array($scripts){
		if (!is_array($scripts)) return;
		
		foreach ($scripts as $script){
			// Why? becouse we need to add an id to script
			$this->add_script($script['url'], $script['type']);
		}
    }
    
    /**
    * Add CSS style sheet to HEAD section of the HTMl page
    * Note: must be used when stylesheet is an absolute url (or remote url)
    * @param 
    */
    public function add_style($sheet, $element='rmcommon', $subfolder='', $media='all', $more='', $front = false){
        global $rmc_config, $xoopsConfig;
        
		$id = crc32($sheet.$element.$subfolder.$media.$more);
		
		if (isset($this->tpl_styles[$id])) return;

        if (!function_exists("xoops_cp_header")){
            $theme = isset($xoopsConfig['theme_set']) ? $xoopsConfig['theme_set'] : 'default';
            $themepath = XOOPS_THEME_PATH.'/'.$theme;
            $themeurl = XOOPS_THEME_URL.'/'.$theme;
        } else {
            $theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';
            $themepath = RMCPATH.'/themes/'.$theme;
            $themeurl = RMCURL.'/themes/'.$theme;
        }

        $theme_file = $themepath.'/css/'.$element.($element!='' ? '/' : '').($subfolder!='' ? $subfolder.'/' : '').$sheet;

        if (is_file($theme_file)){
            $url = $themeurl.'/css/'.($element!='' ? $element.'/' : '').($subfolder!='' ? $subfolder.'/' : '').$sheet;
        } else {
            $url = XOOPS_URL.'/'.($element!='' ? 'modules/'.$element.'/' : '').($subfolder!='' ? $subfolder.'/' : '').'css/'.$sheet;
        }
        
		if (strpos($url, "?")>1){
		    if (strpos($url, 'ver=')===FALSE){
				$url .= "&amp;ver=".RMCVERSION;
		 	}
        } else {
		    $url .= "?ver=".RMCVERSION;
        }
         
         $this->tpl_styles[$id] = array(
         	'url'=>$url,
	        'rel'=>'stylesheet',
	        'media'=>$media,
	        'more'=>$more
         );
		
    }
    
    /**
    * Add a style for the front section
    */
    public function add_xoops_style($sheet, $element='rmcommon', $subfolder='', $media='all', $more=''){

        $this->add_style($sheet, $element, $subfolder, $media, $more, true);
        
    }
    
    /**
    * Add a style provided by theme
    * @param string File name
    * @param string Theme name where css sheet resides
    * @param string Subfolder inside theme css folder
    * @param string media type
    * @param string Another <style> tag attribs
    */
    public function add_theme_style($sheet, $theme='', $subfolder='', $media='all', $more=''){
        global $xoopsConfig, $rmc_config;

		if(defined('XOOPS_CPFUNC_LOADED')){
			
			$theme = $theme=='' ? $rmc_config['theme'] : $theme;
			$file = RMCPATH.'/themes/'.$theme.'/css/'.($subfolder!=''?$subfolder.'/':'').$sheet;
			$url = str_replace(RMCPATH, RMCURL, $file);
			
		} else {

			$theme = $theme=='' ? $xoopsConfig['theme_set'] :$theme;	
			$file = XOOPS_THEME_PATH.'/'.$theme.'/css/'.($subfolder!=''?$subfolder.'/':'').$sheet;
			$url = str_replace(XOOPS_THEME_PATH, XOOPS_THEME_URL, $file);
		
		}
		
		$id = crc32($sheet.$theme.$subfolder.$media.$more);
			
		if(!file_exists($file)) return;
			
		if (strpos($url, "?")>1){
			if (strpos($url, 'ver=')===FALSE){
				$url .= "&amp;ver=".RMCVERSION;
			}
		} else {
			$url .= "?ver=".RMCVERSION;
		}
			 
		$this->tpl_styles[$id] = array(
			'url'=>$url,
			'rel'=>'stylesheet',
			'media'=>$media,
			'more'=>$more
		);
        
    }
    
    /**
    * Get a style url formatted
    */
    public function style_url($sheet, $element='rmcommon', $subfolder=''){
		global $rmc_config;
    
        $theme = isset($rmc_config['theme']) ? $rmc_config['theme'] : 'default';
        $themepath = RMCPATH.'/themes/'.$theme;
        $themeurl = RMCURL.'/themes/'.$theme;

        $theme_file = $themepath.'/css/'.$element.($element!='' ? '/' : '').($subfolder!='' ? $subfolder.'/' : '').$sheet;
        if (is_file($theme_file)){
            $url = $themeurl.'/css/'.($element!='' ? $element.'/' : '').($subfolder!='' ? $subfolder.'/' : '').$sheet;
        } else {
            $url = XOOPS_URL.'/'.($element!='' ? 'modules/'.$element.'/' : '').($subfolder!='' ? $subfolder.'/' : '').'css/'.$sheet;
        }        
        
		if (strpos($url, "?")>1){
		    if (strpos($url, 'ver=')===FALSE){
				$url .= "&amp;ver=".RMCVERSION;
		 	}
        } else {
		    $url .= "?ver=".RMCVERSION;
        }
         
         return $url;
    }
    
	/**
   	* Get all styles stored in class
   	*/
    public function get_styles(){
        return $this->tpl_styles;
    }
    /**
   	* Clear all styles stores
   	*/
    public function clear_styles(){
        $this->tpl_styles = array();
    }
    /**
   	* Assign an array of scripts with values pairs url, type
   	*/
    public function add_styles_array($styles){
		if (!is_array($styles)) return;
		
		foreach ($styles as $style){
			// Why? becouse we need to add an id to style
			$this->add_style($style['sheet'], $style['system'], $style['subfolder'], $style['media'], $style['more']);
		}
    }

    /**
   * Assign template vars
   * @param string Var name
   * @param any Var value
   */
    public function assign($varname, $value){
        $this->tpl_vars[$varname] = $value;
    }
    /**
    * Store vars inside template as array
    * @param string Var name
    * @param mixed Var valu
    */
    public function append($varname, $value){
		$this->tpl_vars[$varname][] = $value;
    }
    /**
   * Get all template vars as an array
   */
    public function vars(){
        return $this->tpl_vars;
    }
    /**
    * Get a single template var
    * 
    * @param string Var name
    * @return any
    */
    public function get_var($varname){
		if (isset($this->tpl_vars[$varname])){
			return $this->tpl_vars[$varname];
		}
		return false;
    }
    
    /**
    * Add option to menu. This method is only functionall in admin section or with the themes
    * that support this feature
    * 
    * @param string Menu parent name
    * @param string Caption
    * @param string Option link url
    * @param string Option icon url
    * @param string Target window (_clank, _self, etc.)
    */
    public function add_menu_option($caption, $link, $icon='', $target=''){
        if ($caption=='' || $link=='') return;
        
        $id = crc32($link);
        
        if (isset($this->tpl_menus[$id])) return;
        
        $this->tpl_menus[$id] = array('caption'=>$caption,'link'=>$link,'icon'=>$icon,'target'=>$target, 'type'=>'normal');
    }
    
    public function add_separator(){
		$this->tpl_menus = array('type'=>'separator');
    }
    /**
    * Get all menu options
    */
    public function menu_options(){
    	
    	$this->tpl_menus = RMEvents::get()->run_event('rmcommon.menus_options',$this->tpl_menus, $this);
    	
		return $this->tpl_menus;
    }
    
    /**
    * Menu Widgets
    */
    public function add_menu($title, $link, $icon='', $location='', $options=array()){
        $this->menus[] = array(
            'title'     => $title,
            'link'      => $link,
            'icon'      => $icon,
            'location'  => $location,
            'options'   => $options
        );
    }
    
    public function get_menus(){
        return $this->menus;
    }
    
    public function add_tool($title, $link, $icon='', $location=''){
		$this->toolbar[] = array(
			'title'		=> $title,
			'link'		=> $link,
			'icon'		=> $icon,
			'location'	=> $location
		);
    }
    
    public function get_toolbar(){
		return $this->toolbar;
    }
    
}
