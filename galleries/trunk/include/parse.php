<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if($xoopsModuleConfig['urlmode']){
    // Get parameters directly from URL when mod_rewrite is enabled
    $params = str_replace(XOOPS_URL, '', RMFunctions::current_url());
    $params = str_replace($xoopsModuleConfig['urlbase'], '', $params);
    $params = trim($params, '/');
    $params = explode("/", $params);

    foreach($params as $i => $p){
        
        switch($p){
            case 'cp':
                // Control Panel
                $cp = isset($params[$i+1]) ? $params[$i+1] : 'images';
                break;
            case 'pag':
                // Page number
                $page = $params[$i+1];
                break;
            case 'usr':
                // User
                $usr = $params[$i+1];
                break;
            case 'explore':
                // Browse photos
                $explore = $params[$i+1];
                break;
            case 'postcard':
                $postcard = $params[$i+1];
                break;
            case 'submit':
                $submit = true;
                break;
            case 'img':
                $img = $params[$i+1];
                break;
            case 'tag':
                $tag = $params[$i+1];
                break;
            case 'set':
                $set = $params[$i+1];
                break;
            case 'search':
                $search = isset($params[$i+1]) ? $params[$i+1] : 1;
                break;
            case 'postcard':
                $postcard = $params[$i+1];
                break;
            case 'id':
                $id = $params[$i+1];
                break;
            case 'ids':
                $ids = $params[$i+1];
                break;
            case 'add':
                $add = $params[$i+1];
                break;
            case 'referer':
                $referer = $params[$i+1];
                break;
        }
        
    }
}
