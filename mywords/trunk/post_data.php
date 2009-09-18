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

while ($row = $db->fetchArray($result)){
    $post = new MWPost();
    $post->assignVars($row);
    
    if (!$post->readAccess()) continue;
    
    # Generamos los vínculos
    $day = date('d', $post->getDate());
    $month = date('m', $post->getDate());
    $year = date('Y', $post->getDate());
    $link = mw_get_url();
    $link .= $mc['permalinks']==1 ? '?post='.$post->getID() : ($mc['permalinks']==2 ? "$day/$month/$year/".$post->getFriendTitle()."/" : "post/".$post->getID());
    # Generamos el vínculo para el autor
    if ($post->getAuthor()>0){ $author =& $post->getAuthorUser(); }  else { unset($author); }
    $alink = mw_get_url();
    $alink .= $mc['permalinks']==1 ? '?author='.$post->getAuthor() : ($mc['permalinks']==2 ? "author/".$util->sweetstring((isset($author) ? $author->uname() : _MS_MW_ANONYMOUS))."/" : "author/".$post->getAuthor());
    # Información de Publicación
    $publicado = sprintf(_MS_MW_PUBLISH, '<a href="'.$link.'">'.formatTimestamp($post->getDate(),'string').'</a>', formatTimestamp($post->getDate(),'t'),'<a href="'.$alink.'">'.(isset($author) ? $author->uname() : _MS_MW_ANONYMOUS)."</a>");
    # Texto de continuar leyendo
    $texto = $post->getHomeText();
    
    // Redes Sociales
    $bms = array();
    foreach ($socials as $bm){
        $bms[] = array('icon'=>$bm->icon(),'alt'=>$bm->text(),'link'=>$bm->link($post->getTitle(),$post->getPermaLink()));
    }
    
    $tpl->append('posts', array(
        'id'                =>$post->getID(),
        'titulo'            =>$post->getTitle(),
        'text'              =>$texto,
        'categos'           =>$post->categos(2),
        'link'              =>$link,
        'lang_permalink'    =>sprintf(_MS_MW_PERMALINK, $post->getTitle()),
        'publicado'         =>$publicado,
        'lang_comment'      =>sprintf(_MS_MW_COMMENTON, $post->getTitle()),
        'numcoms'           =>$post->getComments(),
        'comments'          =>sprintf(_MS_MW_NUMCOMS, $post->getComments()),
        'continue'          =>($post->moretext() ? sprintf(_MS_MW_CONTINUE, $post->getTitle()) : ''),
        'bookmarks'         =>$bms,
        'time'              =>$post->getDate(),
        'author'            =>$post->getAuthorUser()->uname()
    ));
}
?>
