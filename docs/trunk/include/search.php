<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

/**
* @desc Realiza una búsqueda en el módulo desde EXM
*/
function ahelpSearch($queryarray, $andor, $limit, $offset, $userid){
    global $myts;
    
	include_once (XOOPS_ROOT_PATH."/modules/docs/class/rdsection.class.php");
	include_once (XOOPS_ROOT_PATH."/modules/docs/class/rdresource.class.php");

    $mc = RMUtilities::module_config('docs');
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $tbl1 = $db->prefix("rd_resources");
    $tbl2 = $db->prefix("rd_sections");
    
    $sql = "SELECT a.id_res,a.title,a.description,a.created,a.modified,a.public,a.nameid,a.owner,a.owname,a.approved,b.id_sec,b.title AS stitle,b.content,b.id_res AS sid_res,
	b.nameid AS snameid,b.uid,b.uname,b.created AS screated FROM $tbl1 a, $tbl2 b ";
    $sql1 = '';
    foreach ($queryarray as $k){
        $sql1 .= ($sql1=='' ? '' : " $andor ")." (a.id_res=b.id_res) AND (
        	 (b.title LIKE '%$k%' AND b.id_res=a.id_res) OR 
        	 (b.content LIKE '%$k%' AND b.id_res=a.id_res))";
    }
    $sql .= $sql1!='' ? "WHERE $sql1" : '';
    
    $sql .= " AND approved=1 AND public=1 ORDER BY a.modified DESC LIMIT $offset, $limit";
    $result = $db->queryF($sql);
    
    $ret = array();
    while ($row = $db->fetchArray($result)){
	
	    $res=new RDResource();
	    $res->assignVars($row);
	    
	    $sec=new RDSection();
	    $sec->assignVars($row);
	    $sec->assignVar('title',$row['stitle']);
	    $sec->assignVar('id_res',$row['sid_res']);
	    $sec->assignVar('nameid',$row['snameid']);
	    $sec->assignVar('created',$row['screated']);

        $rtn = array();
        $rtn['image'] = 'images/result.png';
	    $rtn['link'] = $sec->permalink();
	
        $rtn['title'] = $sec->getVar('title');
        $rtn['time'] = $sec->getVar('created');
        $rtn['uid'] = $sec->getVar('uid');
        $rtn['desc'] = TextCleaner::getInstance()->truncate($sec->getVar('content'), 150);
        $ret[] = $rtn;
    }
    
    return $ret;
}
