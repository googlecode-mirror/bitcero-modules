<?php

$tpl = RMTemplate::get();

$scripts = '';
foreach ($tpl->tpl_scripts as $script){
    $scripts .= '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
}

$styles = '';        
foreach ($tpl->tpl_styles as $style){
    $styles .= '<link rel="stylesheet" type="text/css" media="'.$style['media'].'" href="'.$style['url'].'"'.($style['more']!=''?' '.$style['more']:'').' />'."\n";
}

$heads = '';
foreach ($tpl->tpl_head as $head){
    $heads .= $head."\n";
}
