<?php
// $Id$
// --------------------------------------------------------
// Gallery System
// Manejo y creación de galerías de imágenes
// CopyRight © 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.org
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
// --------------------------------------------------------
// @copyright: 2008 Red México

define('GS_LOCATION','index');
include '../../mainfile.php';

$xoopsOption['template_main'] = 'gs_index.html';
$xoopsOption['module_subpage'] = 'index';
include 'header.php';

GSFunctions::makeHeader();

$tpl->assign('lang_lastphotos', _MS_GS_LASTPHOTOS);
$tpl->assign('th_width', $mc['image_ths'][0]);
$tpl->assign('last_cols', $mc['last_cols']>0 ? $mc['last_cols'] : 3);
$tpl->assign('td_width', floor(100/$mc['last_cols']));
$tpl->assign('last_sets', _MS_GS_LASTSETS);
$tpl->assign('lang_created', _MS_GS_SETCREATED);
$tpl->assign('lang_by', _MS_GS_SETBY);
$tpl->assign('lang_pics', _MS_GS_SETPICS);
$tpl->assign('lang_other', _MS_GS_OTHERSETS);
$tpl->assign('lang_view', _MS_GS_VIEW);
$tpl->assign('lang_moresets', _MS_GS_MORE);
$tpl->assign('explore_sets_link', GS_URL.($mc['urlmode'] ? '/explore/sets/' : '/explore.php?by=explore/sets'));
$tpl->assign('lang_setbrowse', _MS_GS_BROWSESET);
$tpl->assign('explore_imgs_link', GS_URL.($mc['urlmode'] ? '/explore/photos/' : '/explore.php?by=explore/photos'));
$tpl->assign('lang_imgbrowse', _MS_GS_BROWSEIMGS);
$tpl->assign('explore_tags_link', GS_URL.($mc['urlmode'] ? '/explore/tags/' : '/explore.php?by=explore/tags'));
$tpl->assign('lang_tagbrowse', _MS_GS_BROWSETAGS);

// ültimas Fotos
$mc['last_num'] = $mc['last_num']<=0 ? 10 : $mc['last_num'];
$result = $db->query("SELECT * FROM ".$db->prefix("gs_images")." WHERE public=2 ORDER BY created DESC LIMIT 0,$mc[last_num]");
$users = array();
while ($row = $db->fetchArray($result)){
	$img = new GSImage();
	$img->assignVars($row);
	if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);
	$imglink = $users[$img->owner()]->userURL().'img/'.$img->id().'/';
	$tpl->append('last_images', array('id'=>$img->id(),'title'=>$img->title(),
			'image'=>$users[$img->owner()]->filesURL().'/ths/'.$img->image(),
			'by'=>sprintf(_MS_GS_BY, '<a href="'.$users[$img->owner()]->userUrl().'">'.$users[$img->owner()]->uname().'</a>'),
			'link'=>$imglink));
}

// Álbumes Recientes
$result = $db->query("SELECT * FROM ".$db->prefix("gs_sets")." WHERE public='2' ORDER BY date DESC LIMIT 0,".($mc['sets_num']>0 ? $mc['sets_num'] : 5));
while ($row = $db->fetchArray($result)){
	$set = new GSSet();
	$set->assignVars($row);
	$pics = $set->getPics('RAND()');
	$imgs = @array_slice($pics, 0, 4);
	if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
	$images = array();
	if (!empty($imgs)){
		// Obtenemos las primeras 4 imágenes
		foreach ($imgs as $k){
			$img = new GSImage($k);
			if ($img->isPublic()!=2) continue;

			if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);
			$imglink = $users[$img->owner()]->userURL().'img/'.$img->id().'/';
			$images[] = array('id'=>$img->id(),'title'=>$img->title(),
				'image'=>$users[$img->owner()]->filesURL().'/ths/'.$img->image(),
				'by'=>sprintf(_MS_GS_BY, '<a href="'.$users[$img->owner()]->userUrl().'">'.$users[$img->owner()]->uname().'</a>'),
				'link'=>$imglink);
		}
	}
	$tpl->append('sets', array('id'=>$set->id(),'title'=>$set->title(),'images'=>$images,
		'link'=>$users[$set->owner()]->userUrl().'set/'.$set->id(),
		'date'=>formatTimestamp($set->date(), 'string'), 'by'=>'<a href="'.$users[$set->owner()]->userUrl().'">'.$users[$set->owner()]->uname().'</a>',
		'picsnum'=>count($pics)));
}

$result = $db->query("SELECT * FROM ".$db->prefix("gs_sets")." WHERE public='2' ORDER BY date DESC LIMIT $mc[sets_num],".($mc['sets_num']>0 ? $mc['sets_num'] : 5));
while ($row = $db->fetchArray($result)){
	$set = new GSSet();
	$set->assignVars($row);
	if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
	$tpl->append('other_sets', array('id'=>$set->id(),'title'=>$set->title(),'link'=>$users[$set->owner()]->userUrl().'set/'.$set->id(),
		'date'=>formatTimestamp($set->date(), 'string'), 'by'=>'<a href="'.$users[$set->owner()]->userUrl().'">'.$users[$set->owner()]->uname().'</a>',
		'picsnum'=>count($pics)));
}

$xmh .= "<link href='".GS_URL."/styles/index.css' type='text/css' media='all' rel='stylesheet' />\n";

include 'footer.php';

?>
