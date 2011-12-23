<?php

$tpl = RMTemplate::get();

$scripts = '';
$tscript = '';
foreach ($tpl->tpl_scripts as $script){
    if(strpos($script['url'], 'jquery.min.js')!==FALSE || strpos($script['url'], 'jquery-ui.min.js')){
        $tscript .= '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
    } else {
        $scripts .= '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
    }
}

$scripts = $tscript.$scripts;
unset($tscript);

$styles = '';        
foreach ($tpl->tpl_styles as $style){
    $styles .= '<link rel="stylesheet" type="text/css" media="'.$style['media'].'" href="'.$style['url'].'"'.($style['more']!=''?' '.$style['more']:'').' />'."\n";
}

$heads = '';
foreach ($tpl->tpl_head as $head){
    $heads .= $head."\n";
}
