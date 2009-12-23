<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function mywords_images_links($image, $url){
    
    if (defined('MW_POST_URL')){
        $image['links']['mywords'] = array(
            'caption'=>__('Post URL', 'mywords'),
            'value'=>MW_POST_URL
        );
        return $image;
    }

    if (FALSE===strpos($url, XOOPS_URL.'/modules/mywords/')) return $image;
    
    parse_str($url);
    
    if ($id<=0) return $image;
    
    include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwpost.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/mywords/class/mwfunctions.php';
    $post = new MWPost($id);
    if ($post->isNew()) return $image;
    
    global $mc;
    $mc = RMUtilities::get()->module_config('mywords');
    define('MW_POST_URL', $post->permalink());
    
    $image['links']['mywords'] = array(
        'caption'=>__('Post URL', 'mywords'),
        'value'=>MW_POST_URL
    );
    
    return $image;
    
}