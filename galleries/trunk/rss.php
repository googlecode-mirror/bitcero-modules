<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


load_mod_locale("galleries");
$show = rmc_server_var($_GET,'show','all');

$xoopsModule = RMFunctions::load_module('galleries');
$config = RMUtilities::module_config('galleries');
include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';

$rss_channel = array();
$db = Database::getInstance();

switch($show){
	case 'pictures':
		

        $sql = "SELECT * FROM ".$db->prefix('gs_images')." WHERE public=2 ORDER BY created DESC LIMIT 0,15";
        $result = $db->query($sql);
		
		$rss_channel['title'] = $xoopsModule->name();
		$rss_channel['link'] = GSFunctions::get_url();
        $rss_channel['description'] = __('These are the recent pictures published on our galleries.','galleries');
		$rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
		$rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
	    $rss_channel['category'] = __('Pictures','galleries');
	    $rss_channel['generator'] = 'MyGalleries 3';
	    $rss_channel['language'] = RMCLANG;
	    
	    $users = array();
	    $rss_items = array();
        
	    while($row = $db->fetchArray($result)){
            $img = new GSImage();
            $img->assignVars($row);
            if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);
            $imglink = $users[$img->owner()]->userURL().($mc['urlmode'] ? 'img/'.$img->id().'/' : '&amp;img='.$img->id());
            
            $desc = '<a href="'.$imglink.'"><img src="'.$users[$img->owner()]->filesURL().'/ths/'.$img->image().'" alt="'.$img->title().'" /></a><br />'.($img->desc()!='' ? $img->desc().'<br />' : '');
            $desc .= sprintf(__('By: <strong>%s</strong>','galleries'), $users[$img->owner()]->uname());
            
	    	$item = array();
			$item['title'] = $img->title();
			$item['link'] = $imglink;
			$item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($desc, ENT_QUOTES));
			$item['pubdate'] = formatTimestamp($img->created(), 'rss');
			$item['guid'] = $imglink;
			$rss_items[] = $item;
	    }
	    
		break;
	
	case 'albums':
        
        $rss_channel['title'] = $xoopsModule->name();
        $rss_channel['link'] = GSFunctions::get_url();
        $rss_channel['description'] = __('These are the recent albums created on our galleries.','galleries');
        $rss_channel['lastbuild'] = formatTimestamp(time(), 'rss');
        $rss_channel['webmaster'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['editor'] = checkEmail($xoopsConfig['adminmail'], true);
        $rss_channel['category'] = __('Pictures Albums','galleries');
        $rss_channel['generator'] = 'MyGalleries 3';
        $rss_channel['language'] = RMCLANG;
        
		$sql = "SELECT * FROM ".$db->prefix('gs_sets')." WHERE public='2' ORDER BY `date` DESC LIMIT 0, 10";
        $result = $db->query($sql);
        $users = array();
        
        while ($rows = $db->fetchArray($result)){
            $set = new GSSet();
            $set->assignVars($rows);

            //Obtenemos una imagen del album
            $sql = "SELECT b.* FROM ".$db->prefix('gs_setsimages')." a, ".$db->prefix('gs_images')." b WHERE";
            $sql.= " a.id_set='".$set->id()."' AND b.id_image=a.id_image AND b.public=2 AND b.owner='".$set->owner()."' ORDER BY RAND() LIMIT 0,4" ;

            $resimg = $db->query($sql);
            if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
            $imgs = '<a href="'.$users[$set->owner()]->userURL().($mc['urlmode'] ? 'set/'.$set->id().'/' : '&amp;set='.$set->id()).'">';
            while($rowimg = $db->fetchArray($resimg)){
                
                $img = new GSImage();
                $img->assignVars($rowimg);
                $urlimg = $users[$set->owner()]->filesURL().'/'.($config['set_format_mode'] ? 'formats/set_' : 'ths/').$img->image();
                
                // Conversion de los formatos
                if (!$img->setFormat() && $config['set_format_mode']){
                    GSFunctions::resizeImage($crop, $users[$set->owner()]->filesPath().'/'.$img->image(),$users[$set->owner()]->filesPath().'/formats/set_'.$img->image(), $width, $height);
                    $img->setSetFormat(1, 1);
                }
                
                $imgs .= '<img src="'.$urlimg.'" alt="'.$set->title().'" /> ';
                
            }
            
            $imgs .= '</a>';
            
            $desc = $imgs.'<br />';
            $desc .= sprintf(__('By: <strong>%s</strong>','galleries'), $set->uname());
            
            $item = array();
            $item['title'] = $set->title();
            $item['link'] = $users[$set->owner()]->userURL().($mc['urlmode'] ? 'set/'.$set->id().'/' : '&amp;set='.$set->id());
            $item['description'] = XoopsLocal::convert_encoding(htmlspecialchars($desc, ENT_QUOTES));
            $item['pubdate'] = formatTimestamp($set->date(), 'rss');
            $item['guid'] = $users[$set->owner()]->userURL().($mc['urlmode'] ? 'set/'.$set->id().'/' : '&amp;set='.$set->id());
            $rss_items[] = $item;

        }
		
}

