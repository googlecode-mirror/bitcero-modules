<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'dtrans_index.html';
$xoopsOption['module_subpage'] = 'index';

include 'header.php';

//include XOOPS_ROOT_PATH.'/header.php';

$tpl->add_style('main.css','dtransport');

$dtfunc->makeHeader();

if($mc['dest_download'])
    $xoopsTpl->assign('featured_items', $dtfunc->get_items(0, 'featured', $mc['limit_destdown']));

// Descargas recientes
$xoopsTpl->assign('recent_items', $dtfunc->get_items(0, 'recent', $mc['limit_recents']));
// Descargas mejor valoradas
$xoopsTpl->assign('rated_items', $dtfunc->get_items(0, 'rated', $mc['limit_recents']));
// Descargas actualizadas
$xoopsTpl->assign('updated_items', $dtfunc->get_items(0, 'updated', $mc['limit_recents']));
// Descargas el día
if($mc['daydownload']){
    $xoopsTpl->assign('lang_daydown', __('<strong>Day</strong> Downloads','dtransport'));
    $xoopsTpl->assign('daily_items', $dtfunc->get_items(0, 'daily', $mc['limit_daydownload']));
    $xoopsTpl->assign('daily_width', floor(100/($mc['limit_daydownload'])));
}

$xoopsTpl->assign('lang_recents', __('New Downloads','dtransport'));
$xoopsTpl->assign('lang_bestrated', __('Best Rated','dtransport'));
$xoopsTpl->assign('lang_updated', __('Updated','dtransport'));
$xoopsTpl->assign('lang_featured',__('<strong>Featured</strong> Downloads','dtransport'));
$xoopsTpl->assign('lang_incatego', __('In <a href="%s">%s</a>','dtransport'));
$xoopsTpl->assign('lang_download', __('Download','dtransport'));

$xoopsTpl->assign('lang_ratesite', $xoopsConfig['sitename']);
$xoopsTpl->assign('lang_categos', __('Categories','dtransport'));
include 'footer.php';