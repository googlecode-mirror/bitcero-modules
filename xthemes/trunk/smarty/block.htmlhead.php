<?php
// $Id: function.xtheme.php 141 2010-01-19 16:24:37Z i.bitcero $
// --------------------------------------------------------------
// I.Themes
// Module for manage themes by Red Mexico
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// License: GPL v2
// --------------------------------------------------------------

function smarty_block_htmlhead($params, $content, $tpl, &$repeat){
    
    RMTemplate::get()->add_head($content);
    
}
