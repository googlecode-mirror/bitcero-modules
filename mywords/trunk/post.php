<?php
// $Id: post.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_post.html';
include 'header.php';

if ($post<=0){
	redirect_header(MWFunctions::get_url(), 2, __(''));
	die();
}

$post = new MWPost($post);
// Comprobamos que exista el post
if ($post->isNew()){
	redirect_header(mw_get_url(), 2, __('Document not found','mywords'));
	die();
}
// Comprobamos permisos de acceso al post
if (!$post->user_allowed()){
	redirect_header(MWFunctions::get_url(), 2, _MS_MW_ERRNOPOST);
	die();
}

# Generamos los vínculos
$day = date('d', $post->getVar('pubdate'));
$month = date('m', $post->getVar('pubdate'));
$year = date('Y', $post->getVar('pubdate'));

$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;


# Generamos el vínculo para el autor
$editor = new MWEditor($post->getVar('author'));
# Texto de continuar leyendo

$xoopsTpl->assign('xoops_pagetitle', $post->getVar('title'));

# Cargamos los comentarios del Artículo	
if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	{
		if (!isset($path[$srh])){ 
			$page = 0; 
		} else { 
			$page = $path[$srh +1]; 
		}
	}
}

$xmh .= "\n<script type='text/javascript' src='".MW_URL."include/functions.js'></script>";

$post->add_read();

// Navegación entre artículos
$sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE id_post<".$post->id()." ORDER BY id_post DESC LIMIT 0, 1";
$result = $db->query($sql);
$pn = new MWPost();
// Anterior
if ($db->getRowsNum($result)>0){
    $pn->assignVars($db->fetchArray($result));
    $xoopsTpl->assign('prev_post', array('link'=>$pn->permalink(), 'title'=>$pn->getVar('title')));
}

// Siguiente
$sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE id_post>".$post->id()." ORDER BY id_post ASC LIMIT 0, 1";
$result = $db->query($sql);
if ($db->getRowsNum($result)>0){
    $pn->assignVars($db->fetchArray($result));
    $xoopsTpl->assign('next_post', array('link'=>$pn->permalink(), 'title'=>$pn->getVar('title')));
}

if($xoopsUser && ($xoopsUser->isAdmin() || $editor->getVar('uid')==$xoopsUser->uid())){
	$edit = '<a href="'.XOOPS_URL.'/modules/mywords/admin/posts.php?op=edit&amp;id='.$post->id().'">'.__('Edit Post','mywords').'</a>';
	$xoopsTpl->assign('edit_link', $edit);
	unset($edit);
}

$xoopsTpl->assign('lang_reads', sprintf(__('%u views','mywords'), $post->getVar('reads')));

// Tags
$tags = $post->tags(true);
$tags_list = '';
foreach($tags as $i => $tag){
    $tags_list .= ($tags_list==''?'':', ').'<a href="'.$tag->permalink().'">'.$tag->getVar('tag').'</a>';
}

// Post data
$post_arr = array(
	'id'	=> $post->id(),
	'title'	=> $post->getVar('title'),
	'published' => $publicado = sprintf(__('Published on %s at %s by %s','mywords'), '<a href="'.$post->permalink().'">'.formatTimestamp($post->getVar('pubdate'),'s').'</a>', date('H:i',$post->getVar('pubdate')),'<a href="'.$editor->permalink().'">'.(isset($editor) ? $editor->getVar('name') : __('Anonymous','mywords'))."</a>"),
    'text'    => $post->content(false, $page),
    'cats'  => $post->get_categories_names(),
    'tags'  => $tags_list
);

// Plugins?
$post_arr = RMEvents::get()->run_event('mywords.view.post', $post_arr, $post);
$xoopsTpl->assign('post', $post_arr);

// Social sites
foreach($socials as $site){
	$xoopsTpl->append('socials', array(
		'title' => $site->getVar('title'),
		'icon'	=> $site->getVar('icon'),
		'url'	=> $site->link($post->getVar('title'), $post->permalink(), TextCleaner::truncate($post->content(true), 60)),
		'alt'	=> $site->getVar('alt')
	));
}

unset($tags_list);

// Comments
// When use the common utilities comments system you can choose between
// use of Common Utilities templates or use your own templates
// We will use MyWords included templates
RMFunctions::get_comments('mywords','post='.$post->id());

// Comments form
RMFunctions::comments_form('mywords', 'post='.$post->id(), 'module', MW_PATH.'/class/mywordscontroller.php');

// Language
$xoopsTpl->assign('lang_publish', __('Published in','mywords'));
$xoopsTpl->assign('lang_tagged',__('Tagged as','mywords'));
$xoopsTpl->assign('lang_numcoms', sprintf(__('%u Comments', 'mywords'), $post->getVar('comments')));
 
include 'footer.php';
