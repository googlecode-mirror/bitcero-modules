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

$xoopsOption['template_main'] = 'dtrans_features.html';
$xoopsOption['module_subpage'] = 'cp-features';

/**
* Muestra las características existentes de una descarga
*/
function dt_show_features($edit=0){
    global $xoopsOption,$db,$tpl,$xoopsTpl,$xoopsUser,$mc, $dtfunc, $page, $item, $xoopsConfig, $xoopsModuleConfig,$feature;
    
    include('header.php');
    $dtfunc->cpHeader($item, sprintf(__('%s Features','dtransport'), $item->getVar('name')));
    
    if($feature>0 && $edit){
        
        $feature = new DTFeature($feature);
        if($feature->isNew() || $feature->software()!=$item->id())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/features/'.$item->id().'/':'/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Specified feature does not exists!','dtransport'));
               
    }

    $tc = TextCleaner::getInstance();
    
    $sql = "SELECT * FROM ".$db->prefix('dtrans_features')." WHERE id_soft=".$item->id();
    $result=$db->queryF($sql);
    while ($rows=$db->fetchArray($result)){
        $feat = new DTFeature();
        $feat->assignVars($rows);
        
        $xoopsTpl->append('features',array(
            'id'=>$feat->id(),
            'title'=>$feat->title(),
            'content'=> $tc->truncate($tc->clean_disabled_tags($feat->content()), 80),
            'software'=>$item->getVar('name'),
            'links' => array(
                'permalink' => $feat->permalink(),
                'edit' => DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->getVar('nameid').'/edit/'.$feat->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=features&amp;feature='.$feat->id()),
                'delete' => DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->getVar('nameid').'/delete/'.$feat->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=delete&amp;feature='.$feat->id())
            )
        ));
    }
    
    $formurl = DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->id().'/save/'.($edit ? $feature->id() : '0').'/' : '/p=cpanel');
    
    // Features Form
    $form = new RMForm($edit ? sprintf(__('Editing feature of "%s"','dtransport'),$item->getVar('name')) : sprintf(__('New feature for "%s"','dtransport'),$item->getVar('name')),'frmfeat',$formurl);

    $form->addElement(new RMFormLabel(__('Download item','dtransport'),$item->getVar('name')));
    

    $form->addElement(new RMFormText(__('Feature title','dtransport'),'title',50,200,$edit ? $feature->title() : ''),true);
    $form->addElement(new RMFormText(__('Short name','dtransport'), 'nameid', 50, 200, $edit ? $feature->nameId() : ''));
    $form->addElement(new RMFormEditor(__('Feature content','dtransport'),'content','auto','350px',$edit ? $feature->content('e') : ''),true);

    $dtfunc->meta_form('feat', $edit ? $feature->id() : 0, $form);

    $form->addElement(new RMFormHidden('action', 'save'));
    $form->addElement(new RMFormHidden('id',$item->id()));
    $form->addElement(new RMFormHidden('feature',$edit ? $feature->id() : 0));
    $form->addElement(new RMFormHidden('op','save'));
        
    $buttons =new RMFormButtonGroup();
    $buttons->addButton('sbt',_SUBMIT,'submit');
    $buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\''.(DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->id().'/' : '/?p=cpanel&amp;action=features&amp;id='.$item->id())).'\';"');


    $form->addElement($buttons);
            
    $xoopsTpl->assign('feat_form', $form->render());
    
    $tpl->add_xoops_style('cpanel.css','dtransport');
    $tpl->add_head_script('$(document).ready(function(){
        
        $("a.delete").click(function(){
            if(!confirm("'.__('Do you really want to delete selected feature?','dtransport').'")) return false;
        });
        
    });');
    
    $xoopsTpl->assign('lang_id', __('ID','dtransport'));
    $xoopsTpl->assign('lang_title', __('Title','dtransport'));
    $xoopsTpl->assign('lang_content', __('Content','dtransport'));
    $xoopsTpl->assign('lang_edit', __('Edit','dtransport'));
    $xoopsTpl->assign('lang_delete', __('Delete','dtransport'));
    $xoopsTpl->assign('lang_addfeat', __('Add Feature','dtransport'));
    
    $xoopsTpl->assign('edit', $edit);
    
    include 'footer.php';
}

/**
* Save feature
*/
function dt_save_feature($edit){
    
    global $item, $feature, $tpl, $xoopsTpl, $mc, $dtfunc;

    $query = '';
    foreach ($_POST as $k=>$v){
        $$k=$v;
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    if ($edit){

        //Verificamos que la característica exista
        $ft = new DTFeature($feature);
        if ($ft->isNew())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/features/'.$item->id().'/':'/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Specified feature does not exists!','dtransport'));

    }else{

        $ft = new DTFeature();

    }

    $tc = TextCleaner::getInstance();

    if(trim($nameid)=='')
        $nameid = $tc->sweetstring($title);
    else
        $nameid = $tc->sweetstring($nameid);

    //Comprueba que el título de la característica no exista
    $sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_features')." WHERE (title='$title' OR nameid='$nameid' AND id_feat!=".$ft->id()." AND id_soft=".$item->id();
    list($num) = $db->fetchRow($db->queryF($sql));
    if ($num>0)
        redirect_header(DT_URL.($mc['permalinks']?'/cp/features/'.$item->id().'/edit/'.$ft->id():'/?p=cpanel&amp;action=features&amp;id='.$item->id().'/&amp;op=edit&amp;feature='.$ft->id()), 1, __('Another feature with same title already exists!','dtransport'));

    $ft->setSoftware($item->id());
    $ft->setTitle($title);
    $ft->setContent($content);
    if (!$edit) $ft->setCreated(time());
    $ft->setModified(time());
    $ft->setNameId($nameid);

    if (!$ft->save())
        redirect_header(DT_URL.($mc['permalinks']?'/cp/features/'.$item->id().'/':'/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Feature could not be saved! Please try again.','dtransport'));

    if(!$dtfunc->save_meta('feat', $ft->id()))
        redirectMsg(DT_URL.($mc['permalinks']?'/cp/features/'.$item->id().'/':'/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Feature saved correctly, however custom fields could not be stored in database!','dtransport'));

    redirect_header(DT_URL.($mc['permalinks']?'/cp/features/'.$item->id().'/':'/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Featured saved successfully!','dtransport'));
    
}

/**
* Delete features
*/
function dt_delete_feature(){
    global $mc, $item, $feature;
    
    $ft = new DTFeature($feature);
    
    if($ft->isNew())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->id() : '/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Specified feature is not valid!','dtransport'));

    if(!$ft->delete())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->id() : '/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Feature could not be deleted! Please try again.','dtransport'));
    
    redirect_header(DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->id() : '/?p=cpanel&amp;action=features&amp;id='.$item->id()), 1, __('Feature deleted successfully!','dtransport'));
    
}

/**
* Show content for a specific feature
*/
function dt_return_feature(){
    global $mc, $feature, $item;
    
    if($feature<=0)
        return '';
        
    $ft = new DTFeature($feature);
    if($ft->isNew())
        return;
        
    $tpl = RMTemplate::get();
    include $tpl->get_template('dtrans_feature.php', 'module', 'dtransport');
    
}
