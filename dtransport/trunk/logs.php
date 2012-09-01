<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

defined('XOOPS_MAINFILE_INCLUDED') or die("Not allowed");

$xoopsOption['template_main'] = 'dtrans_logs.html';
$xoopsOption['module_subpage'] = 'cp-logs';

/**
* Muestra las características existentes de una descarga
*/
function dt_show_logs($edit=0){
    global $xoopsOption,$db,$tpl,$xoopsTpl,$xoopsUser,$mc, $dtfunc, $page, $item, $xoopsConfig, $xoopsModuleConfig,$log;
    
    include('header.php');
    $dtfunc->cpHeader($item, sprintf(__('%s Logs','dtransport'), $item->getVar('name')));
    
    if($log>0 && $edit){
        
        $log = new DTLog($log);
        if($log->isNew() || $log->software()!=$item->id())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/logs/'.$item->id().'/':'/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Specified log does not exists!','dtransport'));
               
    }

    $tc = TextCleaner::getInstance();
    $tf = new RMTimeFormatter('', "%M% %d%, %Y%");
    
    $sql = "SELECT * FROM ".$db->prefix('dtrans_logs')." WHERE id_soft=".$item->id();
    $result=$db->queryF($sql);
    while ($rows=$db->fetchArray($result)){
        $lg = new DTLog();
        $lg->assignVars($rows);
        
        $xoopsTpl->append('logs',array(
            'id'=>$lg->id(),
            'title'=>$lg->title(),
            'date'=> $tf->format($lg->date()),
            'software'=>$item->getVar('name'),
            'links' => array(
                'edit' => DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->getVar('nameid').'/edit/'.$lg->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=logs&amp;log='.$lg->id()),
                'delete' => DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->getVar('nameid').'/delete/'.$lg->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=delete&amp;log='.$lg->id())
            )
        ));
    }
    
    $formurl = DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->id().'/save/'.($edit ? $log->id() : '0').'/' : '/p=cpanel');
    
    // logs Form
    $form = new RMForm($edit ? sprintf(__('Editing log of "%s"','dtransport'),$item->getVar('name')) : sprintf(__('New log for "%s"','dtransport'),$item->getVar('name')),'frmLog',$formurl);

    $form->addElement(new RMFormLabel(__('Download item','dtransport'),$item->getVar('name')));
    

    $form->addElement(new RMFormText(__('Log title','dtransport'),'title',50,200,$edit ? $log->title() : ''),true);
    $form->addElement(new RMFormEditor(__('Log content','dtransport'),'content','auto','350px',$edit ? $log->log('e') : ''),true);

    $form->addElement(new RMFormHidden('action', 'save'));
    $form->addElement(new RMFormHidden('id',$item->id()));
    $form->addElement(new RMFormHidden('log',$edit ? $log->id() : 0));
    $form->addElement(new RMFormHidden('op','save'));
        
    $buttons =new RMFormButtonGroup();
    $buttons->addButton('sbt',_SUBMIT,'submit');
    $buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\''.(DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->id().'/' : '/?p=cpanel&amp;action=logs&amp;id='.$item->id())).'\';"');


    $form->addElement($buttons);
            
    $xoopsTpl->assign('log_form', $form->render());
    
    $tpl->add_xoops_style('cpanel.css','dtransport');
    $tpl->add_head_script('$(document).ready(function(){
        
        $("a.delete").click(function(){
            if(!confirm("'.__('Do you really want to delete selected log?','dtransport').'")) return false;
        });
        
    });');
    
    $xoopsTpl->assign('lang_id', __('ID','dtransport'));
    $xoopsTpl->assign('lang_title', __('Title','dtransport'));
    $xoopsTpl->assign('lang_created', __('Date','dtransport'));
    $xoopsTpl->assign('lang_options', __('Options','dtransport'));
    $xoopsTpl->assign('lang_edit', __('Edit','dtransport'));
    $xoopsTpl->assign('lang_delete', __('Delete','dtransport'));
    $xoopsTpl->assign('lang_addlog', __('Add Log','dtransport'));
    
    $xoopsTpl->assign('edit', $edit);
    
    include 'footer.php';
}

/**
* Save feature
*/
function dt_save_log($edit){
    
    global $item, $log, $tpl, $xoopsTpl, $mc, $dtfunc;

    $query = '';
    foreach ($_POST as $k=>$v){
        $$k=$v;
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    if ($edit){

        //Verificamos que la característica exista
        $lg = new DTLog($log);
        if ($lg->isNew())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/logs/'.$item->id().'/':'/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Specified log does not exists!','dtransport'));

    }else{

        $lg = new DTLog();

    }

    $tc = TextCleaner::getInstance();

    //Comprueba que el título de la característica no exista
    $sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_logs')." WHERE title='$title' AND id_log!=".$lg->id()." AND id_soft=".$item->id();
    list($num) = $db->fetchRow($db->queryF($sql));
    if ($num>0)
        redirect_header(DT_URL.($mc['permalinks']?'/cp/logs/'.$item->id().'/edit/'.$lg->id():'/?p=cpanel&amp;action=logs&amp;id='.$item->id().'/&amp;op=edit&amp;log='.$lg->id()), 1, __('Another log with same title already exists!','dtransport'));

    $lg->setSoftware($item->id());
    $lg->setTitle($title);
    $lg->setLog($content);
    $lg->setDate(time());

    if (!$lg->save())
        redirect_header(DT_URL.($mc['permalinks']?'/cp/logs/'.$item->id().'/':'/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Log could not be saved! Please try again.','dtransport'));

    redirect_header(DT_URL.($mc['permalinks']?'/cp/logs/'.$item->id().'/':'/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Log saved successfully!','dtransport'));
    
}

/**
* Delete logs
*/
function dt_delete_log(){
    global $mc, $item, $log;
    
    $lg = new DTLog($log);
    
    if($lg->isNew())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->id() : '/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Specified log is not valid!','dtransport'));

    if(!$lg->delete())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->id() : '/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Log could not be deleted! Please try again.','dtransport'));
    
    redirect_header(DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->id() : '/?p=cpanel&amp;action=logs&amp;id='.$item->id()), 1, __('Log deleted successfully!','dtransport'));
    
}
