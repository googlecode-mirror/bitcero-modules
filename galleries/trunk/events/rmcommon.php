<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GalleriesRmcommonPreload
{
    function eventRmcommonIncludeCommonLanguage(){
        global $xoopsConfig;
        
        if (RMFunctions::current_url()==XOOPS_URL.'/modules/galleries/admin/images.php' && $xoopsConfig['closesite']){
            $security = rmc_server_var($_POST, 'rmsecurity', 0);
            $data = TextCleaner::getInstance()->decrypt($security, true);
            $data = explode("|", $data); // [0] = referer, [1] = session_id(), [2] = user, [3] = token
            $xoopsUser = new XoopsUser($data[0]);
            if ($xoopsUser->isAdmin()) $xoopsConfig['closesite'] = 0;
        }
        
    }
    
    public function eventRmcommonGetFeedsList($feeds){
        
        load_mod_locale('galleries');
        include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
        $module = RMFunctions::load_module('galleries');
        $config = RMUtilities::module_config('galleries');

        $data = array(
                'title'    => $module->name(),
                'url'    => GSFunctions::get_url(),
                'module' => 'galleries'
        );
        
        $options[] = array(
            'title'    => __('All Recent Pictures', 'galleries'),
            'params' => 'show=pictures',
            'description' => __('Show all recent pictures','galleries')
        );
        
        $options[] = array(
            'title'    => __('All Recent Albums', 'galleries'),
            'params' => 'show=albums',
            'description' => __('Show all recent albums','galleries')
        );
        
        $feed = array('data'=>$data,'options'=>$options);
        $feeds[] = $feed;
        return $feeds;
        
    }
    
    public function eventRmcommonImgmgrEditorOptions(){
        global $rmc_config;
        // load language
        load_mod_locale("galleries");
        // Insert required script and styles
        RMTemplate::get()->add_head('<script type="text/javascript">var gs_url="'.XOOPS_URL.'/modules/galleries"; var gedt = "'.$rmc_config['editor_type'].'";
            var lang_image = "'.__('Image','galleries').'";
            var lang_thumb = "'.__('Thumbnail','galleries').'";
            var lang_user = "'.__('User Format','galleries').'";
            var lang_search = "'.__('Search Format','galleries').'";
            var lang_desc = "'.__('Add description','galleries').'";
            var lang_insert = "'.__('Insert Now!','galleries').'";</script>');
        RMTemplate::get()->add_local_script('editor.js', 'galleries');
        RMTemplate::get()->add_xoops_style('editor.css', 'galleries');
        
        
        return '<a href="#" id="a-mg">'.__('Galleries','galleries').'</a>';
    }
    
    public function eventRmcommonImgmgrEditorContainers(){
        global $xoopsSecurity;
        
        include RMTemplate::get()->get_template('other/gs_for_editor.php','module','galleries');
        
    }
    
    public function eventRmcommonCodeDecode($text){
        
        if(defined("XOOPS_CPFUNC_LOADED")) return $text;

        $search = "/\[gallery (.*?)\]/is";
        $replace = "$1";
        $amatches = array();
        $count = preg_match_all($search, $text, $amatches);
        if($count<=0) return;
                
        for($ic=0;$ic<$count;$ic++){
            
            $matches = array($amatches[0][$ic],$amatches[1][$ic]);
            $search = explode(" ", strip_tags($matches[1]));

            foreach($search as $i){
                $t = explode("=",$i);
                $$t[0] = $t[1];
            }

            include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';

            $data = GSFunctions::load_images($id,$num,1);
            
            if(empty($data)){
                $text = str_replace($matches[0], '', $text);
                continue;
            }
            
            $images = $data['images'];

            if(!defined("GS_INCLUDED_GALS")){
                if(RMFunctions::plugin_installed("lightbox")){
                    RMLightbox::get()->add_element('.gs_gsl_item a');
                    RMLightbox::get()->render();
                }

                RMTemplate::get()->add_xoops_style('inclusion.css', 'galleries');
                RMTemplate::get()->add_local_script('inclusion.js','galleries');
                RMTemplate::get()->add_head('<script type="text/javascript">var gsurl = "'.XOOPS_URL.'/modules/galleries";</script>');
                define("GS_INCLUDED_GALS",1);
            }

            // Pagination
            $page = $data['current'];
            $limit = $data['limit'];
            $num = $data['total'];
            $set = $data['set'];

            if($full=='true'){
                $nav = new RMPageNav($num, $limit, $page);
                $nav->set_template(RMTemplate::get()->get_template("other/gs_nav_included.php","module","galleries"));
                $nav->target_url($set['id'].','.$limit);
            }
            $content = '';
            ob_start();
            echo '<div class="gals_loader">';
            include RMTemplate::get()->get_template('other/gs_gals_inclusion.php', 'module', 'galleries');
            echo '</div>';
            $content = ob_get_clean();
            
            $text = str_replace($matches[0], $content, $text);
            
        }
        
        return $text;
        
    }
    
}
