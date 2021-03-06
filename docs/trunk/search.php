<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function show_resources($by, $order='DESC'){
    global $xoopsConfig, $xoopsUser, $page;
    
    if ($by=='') $by = 'created';
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rd_resources")." WHERE public=1 AND approved=1";
    list($num) = $db->fetchRow($db->query($sql));
    
    $page = isset($page) && $page>0 ? $page : 1;
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 
    
    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url(RDFunctions::make_link('explore', array('by'=>$by=='created' ? 'recent' : 'top', 'page'=>'{PAGE_NUM}')));
    
    $sql = "SELECT * FROM ".$db->prefix("rd_resources")." WHERE public=1 AND approved=1 ORDER BY `$by` $order LIMIT $start,$limit";
    $result = $db->query($sql);
    
    $resources = array();
    
    while($row = $db->fetchArray($result)){
        $res = new RDResource();
        $res->assignVars($row);
        $resources[] = array(
            'id' => $res->id(),
            'title' => $res->getVar('title'),
            'desc' => TextCleaner::truncate($res->getVar('description'), 100),
            'link' => $res->permalink(),
            'created' => $res->getVar('created'),
            'owner' => $res->getVar('owner'),
            'uname' => $res->getVar('owname'),
            'reads' => $res->getVar('reads')
        );
    }
    
    RDFunctions::breadcrumb();
    RMBreadCrumb::get()->add_crumb(__('Browsing recent Documents','docs'));
    
    RMTemplate::get()->add_style('docs.css', 'docs');
    
    include 'header.php';
    $xoopsTpl->assign('xoops_pagetitle', $by=='created' ? __('Recent Documents','docs') : __('Top Documents','docs'));
    
    include RMEvents::get()->run_event('docs.template.explore', RMTemplate::get()->get_template('rd_search.php','module','docs'));
    
    include 'footer.php';
    
}

function search_resources(){
    global $xoopsConfig, $xoopsUser, $page, $xoopsTpl;
     
    $keyword = rmc_server_var($_GET, 'keyword', '');
     
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("rd_resources")." WHERE (title LIKE '%$keyword%' OR description LIKE '%$keyword%') AND public=1 AND approved=1";

    list($num) = $db->fetchRow($db->query($sql));
    
    $page = rmc_server_var($_GET, 'page', 1);
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 
    
    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url(RDFunctions::make_link('search').'?keyword='.$keyword.'&amp;page={PAGE_NUM}');
    
    $sql = "SELECT * FROM ".$db->prefix("rd_resources")." WHERE (title LIKE '%$keyword%' OR description LIKE '%$keyword%') AND public=1 AND approved=1 LIMIT $start, $limit";
    $result = $db->query($sql);
    $resources = array();
    
    while($row = $db->fetchArray($result)){
        $res = new RDResource();
        $res->assignVars($row);
        $resources[] = array(
            'id' => $res->id(),
            'title' => $res->getVar('title'),
            'desc' => TextCleaner::truncate($res->getVar('description'), 100),
            'link' => $res->permalink(),
            'created' => $res->getVar('created'),
            'owner' => $res->getVar('owner'),
            'uname' => $res->getVar('owname'),
            'reads' => $res->getVar('reads')
        );
    }
    
    RDFunctions::breadcrumb();
    RMBreadCrumb::get()->add_crumb(__('Browsing recent Documents','docs'));
    
    RMTemplate::get()->add_style('docs.css', 'docs');
    
    include 'header.php';
    $xoopsTpl->assign('xoops_pagetitle', sprintf(__('Search results for "%s"','docs'), $keyword));
    
    include RMEvents::get()->run_event('docs.template.search', RMTemplate::get()->get_template('rd_search.php','module','docs'));
    
    include 'footer.php';
     
}


switch($action){
    case 'search':
        search_resources();
        break;
    case 'explore':
    default:
        show_resources($by=='recent' ? 'created' : 'reads');
        break;
}
