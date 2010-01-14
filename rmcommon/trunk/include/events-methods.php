<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rmc_include_styles(){
    global $xoTheme, $xoopsTpl;
    
    //ob_start();
    $xoTheme->render();
    $page = ob_get_clean();
    
    $cf = $xoopsTpl->get_template_vars('cf');
    if(is_array($cf) && !empty($cf)){
        RMTemplate::get()->add_style('comments.css', 'rmcommon');
    }
    
    $pos = strpos($page, "</head>");
    
    include_once RMTemplate::get()->tpl_path('rmc_header.php', 'rmcommon');
    
    echo substr($page, 0, $pos);
    echo $scripts;
    echo $styles;
    echo $heads;
    echo substr($page, $pos);
    
}
