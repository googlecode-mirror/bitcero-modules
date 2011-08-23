<?php
// $Id: search.php 70 2009-02-09 01:25:31Z BitC3R0 $
// --------------------------------------------------------------
// Reporte de Errores
// CopyRight  2007 - 2008. Red México
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
// @copyright:  2007 - 2008. Red México


/**
* @desc Realiza una búsqueda en el módulo desde EXM
*/
function exmbbSearch($queryarray, $andor, $limit, $offset, $userid=0){
    global $db, $myts, $module;

    /*$util =& RMUtils::getInstance();
    
    $tbl1 = $db->prefix("exmbb_topics");
    $tbl2 = $db->prefix("exmbb_posts_text");
    $tbl3 = $db->prefix("exmbb_posts");
    
    if ($userid<=0){
        $sql = "SELECT a.*,b.*,c.* FROM $tbl1 a, $tbl2 b, $tbl3 c ";
        $sql1 = '';
        foreach ($queryarray as $k){
            $sql1 .= ($sql1=='' ? '' : " $andor ")." (
        	    (a.title LIKE '%$k%' AND a.id_topic=c.id_topic) OR 
        	     (b.post_text LIKE '%$k%' AND b.post_id=c.id_post))";
        }
        $sql .= $sql1!='' ? "WHERE $sql1" : '';
        
        $sql .= $userid>0 ? "GROUP BY c.id_topic"  : " GROUP BY c.id_topic";
        $sql .= " ORDER BY c.post_time DESC LIMIT $offset, $limit";
        
        $result = $db->queryF($sql);
    } else {
        
        $sql = "SELECT a.*, b.*, c.post_text FROM $tbl3 a, $tbl1 b, $tbl2 c WHERE a.uid='$userid' AND b.id_topic=a.id_topic 
                AND c.post_id=a.id_post ";
        $sql1 = '';
        foreach ($queryarray as $k){
            $sql1 .= ($sql1=='' ? 'AND ' : " $andor ")."
                b.title LIKE '%$k%' AND c.post_text LIKE '%$k%'";
        }
        $sql .= $sql1;
        $sql .= "ORDER BY a.post_time DESC
                LIMIT $offset, $limit";

        $result = $db->query($sql);
        
    }
    
    $ret = array();
    while ($row = $db->fetchArray($result)){
        $rtn = array();
        $rtn['image'] = 'images/forum.png';
        $rtn['link'] = 'topic.php?pid='.$row['id_post'];
        $rtn['title'] = $row['title'];
        $rtn['time'] = $row['post_time'];
        $rtn['uid'] = $row['uid'];
        $rtn['desc'] = substr($util->filterTags($row['post_text']), 0, 150).'...';
        $ret[] = $rtn;
    }
    
    return $ret;*/
}
?>
