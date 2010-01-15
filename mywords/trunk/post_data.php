<?php
// $Id: post_data.php 13 2009-08-31 00:45:24Z i.bitcero $
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
    } 
    $alink = $author->permalink();
    # Información de Publicación
    $published = sprintf('%s by %s', MWFunctions::format_time($post->getVar('pubdate'),'string'), '<a href="'.$alink.'">'.(isset($author) ? $author->getVar('name') : __('Anonymous','mywords'))."</a>");
    # Texto de continuar leyendo
    $text = $post->content(true);
    
    // Redes Sociales
    $bms = array();
    foreach ($socials as $bm){
        $bms[] = array('icon'=>$bm->getVar('icon'),'alt'=>$bm->getVar('alt'),'link'=>str_replace(array('{URL}','{TITLE}','{DESC}'), array($post->permalink(),$post->getVar('title'),TextCleaner::getInstance()->truncate($text, 50)),$bm->getVar('url')));
    }
    
    RMTemplate::get()->append('posts', array(
        'id'                =>$post->id(),
        'title'            	=>$post->getVar('title'),
        'text'              =>$text,
        'cats'           	=>$post->get_categos('objects'),
        'link'              =>$link,
        'published'         =>$published,
        'comments'          =>$post->getVar('comments'),
        'continue'          =>$post->hasmore_text(),
        'bookmarks'         =>$bms,
        'time'              =>$post->getVar('pubdate'),
        'author'            =>$authors[$post->getVar('author')]->getVar('name'),
        'alink'				=>$alink,
        'edit'              => $xoopsUser && ($xoopsUser->isAdmin() || $author->getVar('uid')==$xoopsUser->uid()),
        'tags'              => $post->tags(true)
    ));
    
}
