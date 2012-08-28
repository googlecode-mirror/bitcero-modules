<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';
//include 'header.php';

$mc = $xoopsModuleConfig;

// Constantes del Módulo
define('DT_PATH',XOOPS_ROOT_PATH.'/modules/dtransport');
define('DT_URL',$mc['permalinks'] ? XOOPS_URL.'/'.trim($mc['htbase'], "/") : XOOPS_URL.'/modules/dtransport');

// Xoops Module Header
$dtfunc = new DTFunctions();

$rmf = RMFunctions::get();
$rmu = RMUtilities::get();

$url = $rmf->current_url();

$rpath = parse_url($url);
$xpath = parse_url(XOOPS_URL);

// Comprobar si el host es correcto
if($rpath['host']!=$xpath['host']){
    /**
     * @todo Agregar header 303
     */
    header("location: ".DT_URL);
    die();
}

if(substr($rpath['path'], 0, strlen($xpath['path']))!=$xpath['path'])
    $dtfunc->error_404();


if($mc['permalinks']){

    $params = trim(str_replace($xpath['path'].'/'.trim($mc['htbase'], '/'), '', rtrim($rpath['path'], "/")), '/');
    $search = array('category','publisher','recents','popular','rated','updated');

    if($params=='')
        $params = array();
    else
        $params = explode("/", trim($params));

    if(empty($params)){
        require_once 'home.php';
        die();
    }

    switch($params[0]){
        case 'category':

            $params = explode("page", implode("/", array_slice($params, 1)));
            $path = $params[0];
            $page = isset($params[1]) ? trim($params[1], '/') : 1;

            require 'category.php';

            break;

        case 'feature':

            if(count($params)>2)
                $dtfunc->error_404();

            $id = $params[1];
            require 'features.php';
            break;

        case 'download':

            if(count($params)>2)
                $dtfunc->error_404();

            $id = $params[1];

            require 'getfile.php';

            break;

        case 'mine':
        case 'recent':
        case 'popular':
        case 'rated':

            $explore = $params[0];
            $page = 1;

            if(count($params)>3)
                $dtfunc->error_404();

            if(isset($params[1])){
                $params = array_slice($params, 1);

                if($params[0]!='page' || !is_numeric($params[1]))
                    $dtfunc->error_404();

                $page = $params[1];
            }

            require 'explore.php';

            break;
            
        case 'tag':
            
            $tag = $params[1];
            $page = 1;
            
            if(count($params)>4)
                $dtfunc->error_404();
                
            if(isset($params[2])){
                $params = array_slice($params, 2);

                if($params[0]!='page' || !is_numeric($params[1]))
                    $dtfunc->error_404();

                $page = $params[3];
            }
            
            require 'tags.php';
            
            break;
            
        case 'platform':
            
            $os = $params[1];
            if(count($params)>4)
                $dtfunc->error_404();
                
            if(isset($params[2])){
                $params = array_slice($params, 2);

                if($params[0]!='page' || !is_numeric($params[1]))
                    $dtfunc->error_404();

                $page = $params[3];
            }
            
            require 'platforms.php';
            
            break;
        
        case 'license':
        
            $lic = $params[1];
            if(count($params)>4)
                $dtfunc->error_404();
                
            if(isset($params[2])){
                $params = array_slice($params, 2);

                if($params[0]!='page' || !is_numeric($params[1]))
                    $dtfunc->error_404();

                $page = $params[3];
            }
            
            require 'licenses.php';
            break;
            
        case 'submit':
            
            if(count($params)>3)
                $dtfunc->error_404();
            
            $action = isset($params[1]) ? $params[1] : rmc_server_var($_REQUEST, 'action', '');
            $id = isset($params[2]) ? $params[2] : rmc_server_var($_REQUEST, 'id', 0);
                
            require 'submit.php';
            
            break;
        
        case 'cp':
            
            if(count($params)>5)
                $dtfunc->error_404();
            
            $action = '';
            $page = isset($params[1]) && $params[1]=='page' ? $params[2] : 1;
            
            if(isset($params[1]) && $params[1]!='page'){
                $action = $params[1];
                $id = $params[2];
            }
            require 'cpanel.php';
            
            break;

        default:

            if(count($params)>2)
                $dtfunc->error_404();

            $id = trim($params[0]);
            $action = isset($params[1]) ? trim($params[1]) : '';

            require 'item.php';
            break;

    }

} else {



}
