<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!defined('XOOPS_ROOT_PATH')){
    header('location: ./');
    die();
}

// Authors cache
$authors = array();

while ($row = $db->fetchArray($result)){
	
    $post = new MWPost();
    $post->assignVars($row);
    # Generamos los vínculos
    $day = date('d', $post->getVar('pubdate'));
    $month = date('m', $post->getVar('pubdate'));
    $year = date('Y', $post->getVar('pubdate'));
    $link = $post->permalink();
    # Generamos el vínculo para el autor
    if ($post->getVar('author')>0){
    	if(!isset($authors[$post->getVar('author')])) $authors[$post->getVar('author')] = new MWEditor($post->getVar('author'));
    	$author = $authors[$post->getVar('author')];
    	$alink = $author->permalink();
    } else {
		$alink = '';
    }
    
    # Información de Publicación
    $published = sprintf(__('%s by %s', 'mywords'), MWFunctions::format_time($post->getVar('pubdate'),'string'), '<a href="'.$alink.'">'.(isset($author) ? $author->getVar('name') : __('Anonymous','mywords'))."</a>");
    # Texto de continuar leyendo
    if ($post->getVar('visibility')=='password'){
        $text = isset($_SESSION['password-'.$post->id()]) && $_SESSION['password-'.$post->id()]==$post->getVar('password') ? $post->content(true) : MWFunctions::show_password($post);
    } else {
        $text = $post->content(true);
    }
    
    // Redes Sociales
    if($xoopsModuleConfig['showbookmarks']){
        $bms = array();
        foreach ($socials as $bm){
            $bms[] = array('icon'=>$bm->getVar('icon'),'alt'=>$bm->getVar('alt'),'link'=>str_replace(array('{URL}','{TITLE}','{DESC}'), array($post->permalink(),$post->getVar('title'),TextCleaner::getInstance()->truncate($text, 50)),$bm->getVar('url')));
        }
    }
    
    $xoopsTpl->append('posts', array(
        'id'                =>$post->id(),
        'title'            	=>$post->getVar('title'),
        'text'              =>$text,
        'cats'           	=>$post->get_categos('data'),
        'link'              =>$link,
        'published'         =>$published,
        'comments'          =>$post->getVar('comments'),
        'lang_comments'		=>sprintf(__('%u Comments','mywords'), $post->getVar('comments')),
        'continue'          =>$post->hasmore_text(),
        'lang_continue'		=> $post->hasmore_text() ? sprintf(__('Read more about "%s"','mywords'), $post->getVar('title')) : '',
        'bookmarks'         =>$bms,
        'time'              =>$post->getVar('pubdate'),
        'author'            =>$authors[$post->getVar('author')]->getVar('name'),
        'alink'				=>$alink,
        'edit'              => $xoopsUser && ($xoopsUser->isAdmin() || $author->getVar('uid')==$xoopsUser->uid()),
        'tags'              => $post->tags(false),
        'meta'              => $post->get_meta('', false),
        'image'             => $post->getImage($xoopsModuleConfig['list_post_imgs_size'])
    ));
    
}

$xoopsTpl->assign('lang_editpost', __('Edit Post','mywords'));
$xoopsTpl->assign('lang_postedin', __('Posted in:','mywords'));
$xoopsTpl->assign('lang_taggedas', __('Tagged as:','mywords'));
$xoopsTpl->assign('enable_images', $xoopsModuleConfig['list_post_imgs']);
