<?php
// $Id$
// --------------------------------------------------------------
// Foros EXMBB
// Módulo para el manejo de Foros en EXM
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.xoopsmexico.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------------
// @author: BitC3R0
// @copyright: 2007 - 2008 Red México


define('BB_LOCATION', 'reports');
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
	global $tpl,$db,$xoopsModule,$xoopsConfig,$adminTemplate,$util;

	//Indica la lista a mostrar
	$show=isset($_REQUEST['show']) ? intval($_REQUEST['show']) : '0';
	//$show = 0 Muestra todos los reportes
	//$show = 1 Muestra los reportes revisados
	//$show = 2 Muestra los reportes no revisados

	//Lista de Todos los reportes
	$sql="SELECT * FROM ".$db->prefix('exmbb_report').($show ? ($show==1 ? " WHERE zapped=1" : " WHERE zapped=0 ") : '')." ORDER BY report_time DESC";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){

		$report= new BBReport();
		$report->assignVars($rows);
		
		$user= new XoopsUser($report->user());
		
		$tpl->append('reports',array('id'=>$report->id(),'post'=>$report->post(),'user'=>$user->uname(),
		'date'=>date($xoopsConfig['datestring'],$report->time()),'report'=>substr($report->report(),0,50),'zapped'=>$report->zapped(),
		'zappedby'=>$report->zappedby(),'zappedname'=>$report->zappedname(),
		'zappedtime'=>date($xoopsConfig['datestring'],$report->zappedtime())));
	}

	
	$tpl->assign('show',$show);
	$tpl->assign('lang_reports',_AS_EXMBB_LISTREPORTS);
	$tpl->assign('lang_id',_AS_BB_ID);
	$tpl->assign('lang_report',_AS_EXMBB_REPORT);
	$tpl->assign('lang_post',_AS_EXMBB_POST);
	$tpl->assign('lang_user',_AS_EXMBB_USER);
	$tpl->assign('lang_date',_AS_EXMBB_DATE);
	$tpl->assign('lang_zapped',_AS_EXMBB_ZAPPED);
	$tpl->assign('lang_zappedname',_AS_EXMBB_ZAPPEDNAME);
	$tpl->assign('lang_zappedtime',_AS_EXMBB_ZAPPEDTIME);
	$tpl->assign('lang_options',_AS_EXMBB_OPTIONS);
	$tpl->assign('lang_delete',_DELETE);
	$tpl->assign('lang_review',_AS_EXMBB_REVIEW);
	$tpl->assign('lang_notreview',_AS_EXMBB_NOTREVIEW);
	$tpl->assign('token',$util->getToken());
	$tpl->assign('lang_repreview',_AS_EXMBB_REPREVIEW);
	$tpl->assign('lang_repnotreview',_AS_EXMBB_REPNOTREVIEW);
	$tpl->assign('lang_delreport',_AS_EXMBB_DELREPORT);
	$tpl->assign('lang_delreports',_AS_EXMBB_DELREPORTS);

	$adminTemplate = "admin/forums_reports.html";
	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_EXMBB_REPORTS);
	xoops_cp_header();
    
	xoops_cp_footer();

}

/**
* @desc Marca como revisado o no revisado un reporte
* @param int status 1 indica revisar 0 no revisar
**/
function reviewReports($status=1){
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
	

		$report->setZappedBy($xoopsUser->uid());
		$report->setZappedName($xoopsUser->uname());
		$report->setZappedTime(time());
		$report->setZapped($status);

		if (!$report->save()){
			$errors.=sprintf(_AS_EXMBB_NOTSAVE, $k);
		}

	}
	if ($errors!=''){
		redirectMsg('./reports.php?show='.$show,_AS_BB_ERRACTION."<br />". $errors,1);
	}
	else{
		redirectMsg('./reports.php?show='.$show,_AS_BB_DBOK,0);
	}

	

	optionsBar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; "._AS_EXMBB_REPORTS);
	xoops_cp_header();
    
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
