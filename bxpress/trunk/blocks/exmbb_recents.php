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
// @copyright: 2007 - 2008 Red México

function exmbb_recents_show($options){
	
	$util =& RMUtils::getInstance();
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	$xoopsModuleConfig = $util->moduleConfig('exmbb');
	
	$tbl1= $db->prefix('exmbb_posts');
	$tbl2= $db->prefix('exmbb_topics');
	$tbl3=$db->prefix('exmbb_posts_text');
	$sql=" SELECT a.*,b.id_topic,b.title,c.post_text FROM $tbl1 a, $tbl2 b, $tbl3 c WHERE a.id_topic=b.id_topic AND c.post_id=a.id_post 
	".($options[1] ? "" : "GROUP BY a.id_topic")." ORDER BY a.post_time DESC LIMIT 0,$options[0]";
	$result=$db->queryF($sql);
	
	$topics = array();
	$block = array();
	while ($row=$db->fetchArray($result)){
		$post = array();
		$post['id']=$row['id_topic'];
		$post['post'] = $row['id_post'];
		if ($options[2]) $post['date']=formatTimestamp($row['post_time'],'l');
		if ($options[3]) $post['poster']= sprintf(_BS_BB_BY, "<a href='".XOOPS_URL."/modules/exmbb/topic.php?pid=$row[id_post]#p$row[id_post]'>".$row['poster_name']."</a>");
		$post['title'] = $row['title'];
		if ($options[4]) $post['text'] = substr($util->FilterTags($row['post_text']),0,100).'...';
		$topics[] = $post;
	}
	
	// Opciones
	$block['showdates'] = $options[2];
	$block['showuname'] = $options[3];
	$block['showtext'] = $options[4];
	
	$block['topics'] = $topics;
	$block['lang_topic'] = _BS_BB_TOPIC;
	$block['lang_date'] = _BS_BB_DATE;
	$block['lang_poster'] = _BS_BB_POSTER;
		
	return $block;
	
}

function exmbb_recents_edit($options, &$form=null){
	
	$form->addElement(new RMSubTitle(_AS_BKM_BOPTIONS, 1, 'head'));
	$form->addElement(new RMText(_BS_BB_NMTOPICS,'options[0]',10,3,$options[0]), true, 'num');
	$form->addElement(new RMYesNo(_BS_BB_TOPICSREP, 'options[1]', $options[1]));
	$form->addElement(new RMYesNo(_BS_BB_SHOWDATE, 'options[2]',$options[2]));
	$form->addElement(new RMYesNo(_BS_BB_SHOWUNAME, 'options[3]', $options[3]));
	$form->addElement(new RMYesNo(_BS_BB_SHOWRES, 'options[4]', $options[4]));
	
	return $form;
}

?>
