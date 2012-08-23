<?php
// $Id: comments.php 902 2012-01-03 07:09:16Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortes <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rmc_bkcustom_show($options){
    
    $ret['content'] = $options[0];
    
    return $ret;
    
}

function rmc_bkcustom_edit($options){
    
    $form = '<strong>'.__('Custom Content:','rmcommon').'</strong><br /><textarea name="options[0]" cols="45" rows="15" style="width: 97%;">'.$options[0].'</textarea>';
    
    return $form;
    
}
