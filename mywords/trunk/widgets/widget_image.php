<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Provides a widget to specify the default image for posts
 */
function mw_widget_image(){
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $rm_config;

    $id = rmc_server_var($_REQUEST,'id',0);
    
    $type   = rmc_server_var($_REQUEST, 'type', '');
    $op   = rmc_server_var($_REQUEST, 'op', '');
    $edit = $op=='edit' ? 1 : 0;

    $widget = array();
    $widget['title'] = __('Default Image','mywords');
    $util = new RMUtilities();

    if ($edit){
        //Verificamos que el software sea válido
        if ($id<=0)
            $params = '';

        $post = new MWPost($id);

        if ($post->isNew())
            $params = '';
        else
            $params = $post->getVar('image');

    } else {
        $params = '';
    }

    $widget['content'] = '<form name="frmDefimage" id="frm-defimage" method="post">';
    $widget['content'] .= $util->image_manager('image', $params);
    $widget['content'] .= '</form>';
    return $widget;

}