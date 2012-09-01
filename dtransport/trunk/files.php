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

$xoopsOption['template_main'] = 'dtrans_files.html';
$xoopsOption['module_subpage'] = 'cp-files';

/**
* Muestra las características existentes de una descarga
*/
function dt_show_files($edit=0){
    global $xoopsOption,$db,$tpl,$xoopsTpl,$xoopsUser,$mc, $dtfunc, $page, $item, $xoopsConfig, $xoopsModuleConfig,$file;
    
    include('header.php');
    $dtfunc->cpHeader($item, sprintf(__('%s files','dtransport'), $item->getVar('name')));
    
    if($file>0 && $edit){
        
        $file = new DTFile($file);
        if($file->isNew() || $file->software()!=$item->id())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/':'/?p=cpanel&amp;action=files&amp;id='.$item->id()), 1, __('Specified feature does not exists!','dtransport'));
               
    }

    $tc = TextCleaner::getInstance();
    $tf = new RMTimeFormatter('', "%m%/%d%/%Y% %h%:%i%");
    $rmu = RMUtilities::get();
    
    $tfiles = $db->prefix('dtrans_files');
    $tgroup = $db->prefix('dtrans_groups');
    
    $sql = "SELECT * FROM $tfiles WHERE id_soft=".$item->id();
    $gcache = array();
    $result=$db->queryF($sql);
    while ($rows=$db->fetchArray($result)){
        $fl = new DTFile();
        $fl->assignVars($rows);
        
        if(!isset($gcache[$fl->group()]))
            $gcache[$fl->group()] = new DTFileGroup($fl->group());
        
        $group = $gcache[$fl->group()];
        
        $xoopsTpl->append('files',array(
            'id'=>$fl->id(),
            'title'=>$fl->title(),
            'date'=> $tf->format($fl->date()),
            'software'=>$item->getVar('name'),
            'remote'=>$fl->remote(),
            'size' => $rmu->formatBytesSize($fl->size()),
            'hits' => $fl->hits(),
            'date' => $tf->format($fl->date()),
            'group' => $group->isNew() ? '' : $group->name(),
            'links' => array(
                'edit' => DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->getVar('nameid').'/edit/'.$fl->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=files&amp;feature='.$fl->id()),
                'delete' => DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->getVar('nameid').'/delete/'.$fl->id().'/' : '/?p=cpanel&amp;id='.$item->id().'&amp;action=delete&amp;feature='.$fl->id())
            )
        ));
    }
    
    $formurl = DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->id().'/save/'.($edit ? $file->id() : '0').'/' : '/p=cpanel');
    
    // files Form
    $form = new RMForm($edit ? sprintf(__('Editing file of "%s"','dtransport'),$item->getVar('name')) : sprintf(__('New file for "%s"','dtransport'),$item->getVar('name')),'frmFile',$formurl);
    $form->setExtra('enctype="multipart/form-data"');

    $form->addElement(new RMFormLabel(__('Download item','dtransport'),$item->getVar('name')));
    
    $form->addElement(new RMFormText(__('File title','dtransport'),'title',50,200,$edit ? $file->title() : ''),true);
    
    //Lista de grupos
    $sql ="SELECT * FROM ".$db->prefix('dtrans_groups')." WHERE id_soft=".$item->id();
    $result=$db->query($sql);
    $groups = array();
    while($rows=$db->fetchArray($result)){
        $group=new DTFileGroup();
        $group->assignVars($rows);

        $groups[] = array(
            'id'=>$group->id(),
            'name'=>$group->name()
        );

    }
    
    $ele = new RMFormSelect(__('Group','dtransport'), 'group', 0, $edit ? $file->group() : '');
    $ele->addOption('', __('Select group...','dtransport'));
    foreach($groups as $group){
        $ele->addOption($group['id'], $group['name']);
    }
    $form->addElement($ele);
    $form->addElement(new RMFormYesNo(__('Default file','dtransport'),'default', $edit ? $file->isDefault() : 0));
    $form->addElement(new RMFormYesNo(__('Remote file','dtransport'),'remote', $edit ? $file->remote() : 0));
    $form->addElement(new RMFormFile(__('File','dtransport'), 'thefile', 50, $xoopsModuleConfig['size_file']*1024*1024));
    if($edit)
        $form->addElement(new RMFormLabel(__('Current file','dtransport'), $file->file()));
    $form->addElement(new RMFormText(__('File URL','dtransport'),'url',50,200,$edit ? $file->title() : ''))->setDescription(__('Used only when remote file is activated.','dtransport'));

    $form->addElement(new RMFormHidden('action', 'save'));
    $form->addElement(new RMFormHidden('id',$item->id()));
    $form->addElement(new RMFormHidden('file',$edit ? $file->id() : 0));
    $form->addElement(new RMFormHidden('op','save'));
        
    $buttons =new RMFormButtonGroup();
    $buttons->addButton('sbt',$edit ? __('Save Changes','dtransport') : __('Save File','dtransport'),'submit');
    $buttons->addButton('cancel',__('Cancel','dtransport'),'button', 'onclick="window.location=\''.(DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->id().'/' : '/?p=cpanel&amp;action=files&amp;id='.$item->id())).'\';"');


    $form->addElement($buttons);
            
    $xoopsTpl->assign('file_form', $form->render());
    
    $tpl->add_xoops_style('cpanel.css','dtransport');
    $tpl->add_head_script('$(document).ready(function(){
        
        $("a.delete").click(function(){
            if(!confirm("'.__('Do you really want to delete selected file?','dtransport').'")) return false;
        });
        
    });');
    
    $xoopsTpl->assign('lang_id', __('ID','dtransport'));
    $xoopsTpl->assign('lang_title', __('Title','dtransport'));
    $xoopsTpl->assign('lang_group', __('Group','dtransport'));
    $xoopsTpl->assign('lang_remote', __('Remote','dtransport'));
    $xoopsTpl->assign('lang_size', __('Size','dtransport'));
    $xoopsTpl->assign('lang_hits', __('Hits','dtransport'));
    $xoopsTpl->assign('lang_date', __('Date','dtransport'));
    $xoopsTpl->assign('lang_edit', __('Edit','dtransport'));
    $xoopsTpl->assign('lang_delete', __('Delete','dtransport'));
    $xoopsTpl->assign('lang_addfile', __('Add File','dtransport'));
    
    $xoopsTpl->assign('edit', $edit);
    
    include 'footer.php';
}

/**
* Save file
*/
function dt_save_file($edit){
    
    global $item, $file, $tpl, $xoopsTpl, $mc, $dtfunc;

    foreach ($_POST as $k=>$v){
        $$k=$v;
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    if ($edit){

        //Verificamos que la característica exista
        $fl = new DTFile($file);
        if ($fl->isNew())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/':'/?p=cpanel&amp;action=files&amp;id='.$item->id()), 1, __('Specified file does not exists!','dtransport'));

    }else{

        $fl = new DTFile();

    }

    $tc = TextCleaner::getInstance();

    //Comprueba que el título de la característica no exista
    $sql="SELECT COUNT(*) FROM ".$db->prefix('dtrans_files')." WHERE title='$title' AND id_file!=".$fl->id()." AND id_soft=".$item->id();
    list($num) = $db->fetchRow($db->queryF($sql));
    if ($num>0)
        redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/edit/'.$fl->id():'/?p=cpanel&amp;action=files&amp;id='.$item->id().'&amp;op=edit&amp;file='.$fl->id()), 1, __('Another log with same title already exists!','dtransport'));

    // Check if a file has been provided
    if($_FILES['thefile']['name']==''){
        // Comprobamos si se ha proporcionado un archivo
        if(!$edit && !$remote)
            redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/edit/'.$fl->id():'/?p=cpanel&amp;action=files&amp;id='.$item->id().'&amp;op=edit&amp;file='.$fl->id()), 1, __('You must provide a file to upload!','dtransport'));
        elseif($remote && $url=='')
            redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/edit/'.$fl->id():'/?p=cpanel&amp;action=files&amp;id='.$item->id().'&amp;op=edit&amp;file='.$fl->id()), 1, __('You must provide a file URL when remote type is activated!','dtransport'));
            
    } else {
        
        if($edit && !$fl->remote()){
            $path = $item->getVar('secure') ? rtrim($mc['directory_secure'], '/').'/'.$fl->file() : rtrim($mc['directory_insecure']).'/'.$fl->file();
            unlink($path);
        }
        
        if($item->getVar('secure'))
            $dir = $mc['directory_secure'];
        else
            $dir = $mc['directory_insecure'];
        
        include RMCPATH.'/class/uploader.php';

        $uploader = new RMFileUploader($dir, $mc['size_file']*1024*1024, $mc['type_file']);

        if (!$uploader->fetchMedia('thefile'))
            redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/edit/'.$fl->id():'/?p=cpanel&amp;action=files&amp;id='.$item->id().'&amp;op=edit&amp;file='.$fl->id()), 1, __('File could not be uploaded!, Please try again.','dtransport').$uploader->getErrors());

        if (!$uploader->upload())
            redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/edit/'.$fl->id():'/?p=cpanel&amp;action=files&amp;id='.$item->id().'&amp;op=edit&amp;file='.$fl->id()), 1, __('File could not be uploaded!, Please try again.','dtransport').$uploader->getErrors());
        
    }
    
    $fl->setSoftware($item->id());
    $fl->setTitle($title);
    $fname = !$uploader && $edit ? ($remote ? $url : $fl->file()) : $uploader->getSavedFileName();
    $fl->setFile($fname);
    $fl->setRemote($remote);
    $fl->setGroup($group);
    $fl->setDefault($default);
    $fl->setDate(time());
    $fl->setSize($remote ? '' : (isset($uploader) ? $uploader->getMediaSize() : $fl->size()));
    $fl->setMime($remote ? '' : (isset($uploader) ? $uploader->getMediaType() : $fl->mime()));

    if (!$fl->save())
        redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/'.($edit ? 'edit/'.$fl->id() : ''):'/?p=cpanel&amp;action=files&amp;id='.$item->id().($edit ? '&amp;op=edit&amp;file='.$fl->id() : '')), 1, __('File could not be saved! Please try again.','dtransport'));
    
    if($fl->isDefault())
        $db->queryF("UPDATE ".$db->prefix("dtrans_files")." SET `default`=0 WHERE id_soft=".$item->id()." AND id_file !=".$fl->id());
        
    redirect_header(DT_URL.($mc['permalinks']?'/cp/files/'.$item->id().'/':'/?p=cpanel&amp;action=files&amp;id='.$item->id()), 1, __('File saved successfully!','dtransport'));
    
}

/**
* Delete files
*/
function dt_delete_file(){
    global $mc, $item, $file;
    
    $fl = new DTFile($file);
    
    if($fl->isNew())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->id() : '/?p=cpanel&amp;action=files&amp;id='.$item->id()), 1, __('Specified file is not valid!','dtransport'));

    if(!$fl->delete())
        redirect_header(DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->id() : '/?p=cpanel&amp;action=files&amp;id='.$item->id()), 1, __('File could not be deleted! Please try again.','dtransport'));
    
    if($item->getVar('secure'))
        $dir = rtrim($mc['directory_secure'], '/');
    else
        $dir = rtrim($mc['directory_insecure'], '/');
    
    if(!$fl->remote())
        unlink($dir.'/'.$fl->file());
    
    redirect_header(DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->id() : '/?p=cpanel&amp;action=files&amp;id='.$item->id()), 1, __('File deleted successfully!','dtransport'));
    
}

