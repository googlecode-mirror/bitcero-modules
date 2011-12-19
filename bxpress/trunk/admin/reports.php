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

        $db = Database::getInstance();
	//Lista de Todos los reportes
	$sql="SELECT * FROM ".$db->prefix('bxpress_report').($show ? ($show==1 ? " WHERE zapped=1" : " WHERE zapped=0 ") : '')." ORDER BY report_time DESC";
	$result=$db->queryF($sql);
        $reports = array();
	while ($rows=$db->fetchArray($result)){

		$report= new bXReport();
		$report->assignVars($rows);
		
		$user= new XoopsUser($report->user());
		
		$reports[] = array(
                    'id'=>$report->id(),
                    'post'=>$report->post(),
                    'user'=>$user->uname(),
                    'date'=>  formatTimestamp($report->time(), 'l'),
                    'report'=>substr($report->report(),0,50),
                    'zapped'=>$report->zapped(),
                    'zappedby'=>$report->zappedby(),
                    'zappedname'=>$report->zappedname(),
                    'zappedtime'=> $report->zappedtime()>0?formatTimestamp($report->zappedtime()):''
                );
	}

        RMTemplate::get()->add_local_script('jquery.checkboxes.js','rmcommon','include');
        RMTemplate::get()->add_local_script('admin.js','bxpress');
        
	bXFunctions::menu_bar();
        
        RMTemplate::get()->assign('xoops_pagetitle', __('Reports Management','bxpress'));
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Reports Management','bxpress'));
	xoops_cp_header();
        
        include RMTemplate::get()->get_template('admin/forums_reports.php', 'module','bxpress');
        
	xoops_cp_footer();

}

/**
* @desc Marca como revisado o no revisado un reporte
* @param int status 1 indica revisar 0 no revisar
**/
function reviewReports($status=1){
	global $xoopsModule,$xoopsUser;

	$id = rmc_server_var($_GET,'report',0);
	$show= rmc_server_var($_GET,'show',0);
	
	// Verificamos si el reporte es válido
	if ($id<=0){
            redirectMsg('./reports.php?show='.$show, __('Please, specify a report to review','bxpress'), 1);
            die();
	}	
	
	$report=new bXReport($id);
	
        //Comprobamos si el reporte existe
	if ($report->isNew()){
            redirectMsg('reports.php?show='.$show, __('Specified report does not exists!','bxpress'), 1);
            die();
        }
        
        $post = new bXPost($report->post());
        if($post->isNew()){
            redirectMsg('reports.php?show='.$show, __('Specified post does not exists!','bxpress'), 1);
            die();
        }
	
        $form = new RMForm(__('Review Report','bxpress'), 'frm-review', 'reports.php');
        $form->addElement(new RMFormSubTitle(__('Please, review this reported message an proceed according to your appreciation.','bxpress'), 0));
        $form->addElement(new RMFormUser(__('User that report:','bxpress'), 'user', false, array($report->user()),36,600,300,0,false));
        $form->addElement(new RMFormLabel(__('Reporter user message','bxpress'), $report->report()));
        $form->addElement(new RMFormLabel(__('Reported post','bxpress'), $post->text(),'postm'));
        $form->element('postm')->setDescription('<a href="'.$post->permalink().'" target="_blank">'.__('View full topic here','bxpress').'</a>');

	bXFunctions::menu_bar();
        
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Reviewing Report','bxpress'));
	xoops_cp_header();
        
        $form->display();
    
	xoops_cp_footer();

}

/**
* @desc Elimina Reportes
**/
function deleteReports(){
	global $db,$xoopsModule,$xoopsUser;

	$util=&RMUtils::getInstance();
	$reports = isset($_REQUEST['reports']) ? $_REQUEST['reports'] : array();
	$show=isset($_REQUEST['show']) ? intval($_REQUEST['show']) : '0';

	//Verificamos si los reportes son válidos
	if (!is_array($reports) || empty($reports)){
		redirectMsg('./reports.php?show='.$show, _AS_EXMBB_ERRREPORTS, 1);
		die();
	}	

	
	if (!$util->validateToken()){
		redirectMsg('./reports.php?show='.$show, _AS_EXMBB_SESSINVALID,0);
		die();
	}
	
	$errors='';
	foreach ($reports as $k){
		//Verificamos si el reporte es válido
		if ($k<=0){
			$errors.=sprintf(_AS_EXMBB_ERRORREPORT,$k);
			continue;
					
		}
		
		$report=new BBReport($k);
		//Comprobamos si el reporte existe
		if ($report->isNew()){
			$errors.=sprintf(_AS_EXMBB_NOTEXIST,$k);
			continue;

		}
	
		if (!$report->delete()){
			$errors.=sprintf(_AS_EXMBB_NOTDELETE,$k);
		}

	

	}

	if ($errors!=''){
		redirectMsg('./reports.php?show='.$show,_AS_BB_ERRACTION."<br />". $errors,1);
	}
	else{
		redirectMsg('./reports.php?show='.$show,_AS_BB_DBOK,0);
	}


}



$op=isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch($op){
	case 'review':
		reviewReports();
	break;
	case 'notreview':
		reviewReports(0);
	break;	
	case 'delete':
		deleteReports();
	break;	
	default:
		showReports(0);

}
?>
