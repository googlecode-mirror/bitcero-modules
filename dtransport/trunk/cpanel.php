<?php
// $Id: index.php 1008 2012-07-31 18:58:34Z i.bitcero $
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if(!$xoopsUser)
    redirect_header(DT_URL, 1, __('Please login before to access this page!','dtransport'));

if(!$mc['send_download']){
    header('Location: '.DT_URL);
    die();
}

if($action!=''){
    //Verificamos si el elemento es válido
    if ($id=='' && $id<=0)
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'),2,__('Item not found. Please try again!','dtransport'));

    //Verificamos si el elemento existe
    $item = new DTSoftware($id);
    if ($item->isNew())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'),2,__('Item not found. Please try again!','dtransport'));
            
    if($item->getVar('uid')!=$xoopsUser->uid())
        redirect_header(DT_URL, 1, __('You can not edit this download item!','dtransport'));
}

switch($action){
    
    case 'screens':
        $op = '';
        if(count($params)>=4){
            $params = array_slice($params, 3);
            $op = $params[0];
            $screen = $params[1];
        }
        
        require 'screens.php';
        
        switch($op){
            case 'save':
                dt_save_screens($screen>0?1:0);
                break;                
            default:
                dt_screens($op=='edit'&&$screen>0 ? 1 : 0);
                break;
        }
        break;
    
    case 'delete':
                    
        $item->setVar('delete', 1);
        $item->licences();
        $item->tags();
        $item->categories();
        $item->platforms();
        if($item->save())
            redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'), 2, sprintf(__('Item marked to deletion successfully! From now and on, "%s" will be unavailable for download while administrators delete it.','dtransport'), $item->getVar('name')));
        else          
            redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'), 2, __('Item could not be marked to deletion! Please try again.','dtransport'));            
            
        break;
    
    case 'canceldel':
        
        $item->setVar('delete', 0);
        $item->licences();
        $item->tags();
        $item->categories();
        $item->platforms();
        if($item->save())
            redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'), 2, sprintf(__('Item restored successfully!','dtransport'), $item->getVar('name')));
        else
            redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'), 2, __('Item could not be restored! Please try again.','dtransport'));
    
    default:
        
        $xoopsOption['template_main'] = 'dtrans_cp.html';
        $xoopsOption['module_subpage'] = 'cp-list';
        
        include 'header.php';
        
        $dtfunc->makeHeader(__('Control Panel','dtransport'));
        
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software")." WHERE uid=".$xoopsUser->uid()." ORDER BY `created` DESC";
        list($num) = $db->fetchRow($db->query($sql));
        
        $limit = 15;
        $tpages = ceil($num / $limit);
        if ($tpages<$page && $tpages>0){
            header('location: '.DT_URL.($mc['permalinks']?'/cp/':'/?p=cpanel'));
            die();
        }

        $p = $page>0 ? $page-1 : $page;
        $start = $num<=0 ? 0 : $p * $limit;

        $nav = new RMPageNav($num, $limit, $page);
        $nav->target_url(DT_URL.($mc['permalinks']?'/cp/page/{PAGE_NUM}/':'/?p=cpanel&amp;page={PAGE_NUM}'));
        $xoopsTpl->assign('pagenav', $nav->render(true));
        
        $sql = str_replace("COUNT(*)",'*',$sql)." LIMIT $start, $limit";
        $result = $db->query($sql);
        
        $tf = new RMTimeFormatter('', "%M%-%d%-%Y%");
        
        while($row = $db->fetchArray($result)){
            $item = new DTSoftware();
            $item->assignVars($row);
            $xoopsTpl->append('items', array(
                'id' => $item->id(),
                'name' => $item->getVar('name'),
                'links' => array(
                    'permalink' => $item->permalink(),
                    'edit' => $mc['permalinks'] ? DT_URL.'/submit/edit/'.$item->id() : '/?p=submit&amp;action=edit&amp;id='.$item->id(),
                    'delete' => $mc['permalinks'] ? DT_URL.'/cp/delete/'.$item->id() : '/?p=cpanel&amp;action=delete&amp;id='.$item->id(),
                    'features' => $mc['permalinks'] ? DT_URL.'/cp/features/'.$item->id() : '/?p=cpanel&amp;action=features&amp;id='.$item->id(),
                    'logs' => $mc['permalinks'] ? DT_URL.'/cp/logs/'.$item->id() : '/?p=cpanel&amp;action=logs&amp;id='.$item->id(),
                    'screens' => $mc['permalinks'] ? DT_URL.'/cp/screens/'.$item->id() : '/?p=cpanel&amp;action=screens&amp;id='.$item->id(),
                    'canceldel' => $mc['permalinks'] ? DT_URL.'/cp/canceldel/'.$item->id() : '/?p=cpanel&amp;action=canceldel&amp;id='.$item->id()                    
                ),
                'secure' => $item->getVar('secure'),
                'approved' => $item->getVar('approved'),
                'created' => array('time'=>$item->getVar('created'),'formated'=>$tf->format($item->getVar('created'))),
                'modified' => array('time'=>$item->getVar('modified'),'formated'=>$tf->format($item->getVar('modified'))),
                'hits' => $item->getVar('hits'),
                'deletion' => $item->getVar('delete')
            ));
        }
        
        // Idioma
        $xoopsTpl->assign('lang_id', __('ID','dtransport'));
        $xoopsTpl->assign('lang_name', __('Name','dtransport'));
        $xoopsTpl->assign('lang_protected', __('Protected','dtransport'));
        $xoopsTpl->assign('lang_approved', __('Approved','dtransport'));
        $xoopsTpl->assign('lang_created', __('Created','dtransport'));
        $xoopsTpl->assign('lang_modified', __('Modified','dtransport'));
        $xoopsTpl->assign('lang_hits', __('Hits','dtransport'));
        $xoopsTpl->assign('lang_edit', __('Edit','dtransport'));
        $xoopsTpl->assign('lang_delete', __('Delete','dtransport'));
        $xoopsTpl->assign('lang_todelete', __('Waiting Deletion','dtransport'));
        $xoopsTpl->assign('lang_features', __('Features','dtransport'));
        $xoopsTpl->assign('lang_logs', __('Logs','dtransport'));
        $xoopsTpl->assign('lang_screens', __('Screenshots','dtransport'));
        $xoopsTpl->assign('lang_canceldel', __('Cancel Deletion','dtransport'));
                        
        $tpl->add_xoops_style('cpanel.css','dtransport');
        
        include 'footer.php';
        break;
        
}