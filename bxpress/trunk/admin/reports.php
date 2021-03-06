<?php
// $Id$
// --------------------------------------------------------------
// bXpress Forums
// An simple forums module for XOOPS and Common Utilities
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


define('RMCLOCATION', 'reports');
include 'header.php';
/**
* @desc Muestra la barra de menus
*/
function optionsBar(){
    global $tpl;
    
    $tpl->append('xoopsOptions', array('link' => './reports.php', 'title' => _AS_EXMBB_ALLREPORTS, 'icon' => '../images/report16.png'));
    $tpl->append('xoopsOptions', array('link' => './reports.php?show=1', 'title' => _AS_EXMBB_REVREPORTS, 'icon' => '../images/ok.png'));
    $tpl->append('xoopsOptions', array('link' => './reports.php?show=2', 'title' => _AS_EXMBB_REVNOTREPORTS, 'icon' => '../images/no.png'));
}


function showReports(){
	global $xoopsModule,$xoopsConfig, $xoopsSecurity;
	//Indica la lista a mostrar
	$show=isset($_REQUEST['show']) ? intval($_REQUEST['show']) : '0';
	//$show = 0 Muestra todos los reportes
	//$show = 1 Muestra los reportes revisados
	//$show = 2 Muestra los reportes no revisados
        define('RMCSUBLOCATION',$show==0?'allreps':($show==1?'reviews':'noreviewd'));

        $db = XoopsDatabaseFactory::getDatabaseConnection();
	//Lista de Todos los reportes
	$sql="SELECT * FROM ".$db->prefix('bxpress_report').($show ? ($show==1 ? " WHERE zapped=1" : " WHERE zapped=0 ") : '')." ORDER BY report_time DESC";
	$result=$db->queryF($sql);
        $reports = array();
        
        $tf = new RMTimeFormatter(0, '%T% %d%, %Y% %h%:%i%:%s%');
        
	while ($rows=$db->fetchArray($result)){

		$report= new bXReport();
		$report->assignVars($rows);
		
		$user= new XoopsUser($report->user());
                $post = new bXPost($report->post());
                $topic = new bXTopic($post->topic());
                $forum = new bXForum($post->forum());
                if($report->zappedBy()>0){
                    $zuser = new XoopsUser($report->zappedBy());
                }
		
		$reports[] = array(
                    'id'=>$report->id(),
                    'post'=>array('link'=>$post->permalink(),'id'=>$report->post()),
                    'user'=>$user->uname(),
                    'uid' => $user->uid(),
                    'date'=>  $tf->format($report->time()),
                    'report'=>$report->report(),
                    'forum' => array('link'=>$forum->permalink(),'name'=>$forum->name()),
                    'topic' => array('link'=>$topic->permalink(),'title'=>$topic->title()),
                    'zapped'=>$report->zapped(),
                    'zappedby'=>$report->zappedby()>0 ? array('uid'=>$zuser->uid(),'name'=>$zuser->uname()) : '',
                    'zappedtime'=> $report->zappedtime()>0?$tf->format($report->zappedtime()):''
                );
	}

        RMTemplate::get()->add_local_script('jquery.checkboxes.js','rmcommon','include');
        RMTemplate::get()->add_local_script('admin.js','bxpress');
        RMTemplate::get()->set_help('http://www.redmexico.com.mx/docs/bxpress-forums/introduccion/standalone/1/');
	bXFunctions::menu_bar();
        
        RMTemplate::get()->assign('xoops_pagetitle', __('Reports Management','bxpress'));
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Reports Management','bxpress'));
	xoops_cp_header();
        
        include RMTemplate::get()->get_template('admin/forums_reports.php', 'module','bxpress');
        
	xoops_cp_footer();

}


function mark_read($read=1){
    
    global $xoopsSecurity, $xoopsUser;
    
    if(!$xoopsSecurity->check())
        redirectMsg('reports.php',__('Session token expired!','bxpress'), 1);
    
    $ids = rmc_server_var($_POST, 'ids', array());
    $show = rmc_server_var($_POST, 'show', array());
    
    if(empty($ids))
        redirectMsg('reports.php?show='.$show, __('Select at least one report!','bxpress'), 1);
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "UPDATE ".$db->prefix("bxpress_report")." SET zapped='$read', zappedby='".$xoopsUser->uid()."', zappedtime='".time()."' WHERE report_id IN (".implode(",",$ids).")";

    if($db->queryF($sql))
        redirectMsg('reports.php?show='.$show, __('Database updated successfully!','bxpress'), 0);
    else
        redirectMsg('reports.php?show='.$show, __('Errors ocurred','bxpress').'<br />'.$db->error(), 1);
    
}

/**
* @desc Elimina Reportes
**/
function deleteReports(){
	global $xoopsModule,$xoopsUser, $xoopsSecurity;

	$ids = rmc_server_var($_POST,'ids',array());
        $show = rmc_server_var($_POST,'show',array());

	//Verificamos si los reportes son válidos
	if (empty($ids)){
            redirectMsg('reports.php?show='.$show, __('Select at least one report!','bxpress'), 1);
            die();
	}	

	
	if (!$xoopsSecurity->check()){
            redirectMsg('reports.php?show='.$show, __('Session token expired!','bxpress'),1);
            die();
	}
	
	$errors='';
	foreach ($ids as $id){
            //Verificamos si el reporte es válido
            if ($id<=0){
		$errors.=sprintf(__('ID %s is not valid!','bxpress'),$id);
		continue;			
            }
		
            $report=new bXReport($id);
            //Comprobamos si el reporte existe
            if ($report->isNew()){
		$errors.=sprintf(__('Report with ID %s does not exists!','bxpress'),$id);
		continue;
            }
	
            if (!$report->delete()){
		$errors.=sprintf(__('Report %s could dot be deleted!','bxpress'),$id);
            }

	}

	if ($errors!=''){
            redirectMsg('reports.php?show='.$show,__('Errors ocurred while trying to delete selected reports.','bxpress')."<br />". $errors,1);
	}
	else{
            redirectMsg('./reports.php?show='.$show,__('Reports deleted successfully!','bxpress'),0);
	}

}



$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
	case 'read':
            mark_read(1);
            break;
	case 'notread':
            mark_read(0);
            break;	
	case 'delete':
            deleteReports();
            break;	
	default:
            showReports(0);

}
