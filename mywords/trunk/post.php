<?php
// $Id: post.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include 'header.php';

if ($post<=0){
	redirect_header(MWFunctions::get_url(), 2, __(''));
	die();
}

$post = new MWPost($post);
// Comprobamos que exista el post
if ($post->isNew()){
	redirect_header(mw_get_url(), 2, _MS_MW_ERRNOPOST);
	die();
}
// Comprobamos permisos de acceso al post
if (($post->getApproved()==0 || $post->getStatus()!=1) && (!$xoopsUser || !$xoopsUser->isAdmin())){
	redirect_header(mw_get_url(), 2, _MS_MW_ERRNOPOST);
	die();
}

# Generamos los vínculos
$day = date('d', $post->getDate());
$month = date('m', $post->getDate());
$year = date('Y', $post->getDate());
$link = mw_get_url();
$link .= $mc['permalinks']==1 ? '?post='.$post->getID() : ($mc['permalinks']==2 ? "$day/$month/$year/".$post->getFriendTitle()."/" : "post/".$post->getID());

$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;


# Generamos el vínculo para el autor
if ($post->getAuthor()>0){ $author =& $post->getAuthorName(); }  else { unset($author); }
$alink = mw_get_url();
$alink .= $mc['permalinks']==1 ? '?author='.$post->getAuthor() : ($mc['permalinks']==2 ? "author/".$util->sweetstring((isset($author) ? $author : _MS_MW_ANONYMOUS))."/" : "author/".$post->getAuthor());
# Información de Publicación
$publicado = sprintf(_MS_MW_PUBLISH, '<a href="'.$link.'">'.formatTimestamp($post->getDate(),'s').'</a>', formatTimestamp($post->getDate(),'t'),'<a href="'.$alink.'">'.(isset($author) ? $author : _MS_MW_ANONYMOUS)."</a>");
# Texto de continuar leyendo
$texto = $post->getText();

$tpl->assign(
    'post', array(
    'id'                =>$post->getID(),
    'titulo'            =>$post->getTitle(),
    'text'              =>$texto,
	'categos'           =>$post->categos(2),
    'link'              =>$link,
    'lang_permalink'    =>sprintf(_MS_MW_PERMALINK, $post->getTitle()),
	'publicado'         =>$publicado,
    'comments'          =>sprintf(_MS_MW_NUMCOMSL, $post->getComments()),
	'trackbacks'        =>sprintf(_MS_MW_NUMTRACKSL, $post->getTBCount()),
    'continue'          =>'',
    'trackback'         =>$post->getAllowPings(),
    'time'              => $post->getDate(),
    'author'            => $post->getAuthorUser()->uname()
));

$tpl->assign('lang_publish', _MS_MW_CATEGOS);
$tpl->assign('xoops_pagetitle', $post->getTitle());
$tpl->assign('lang_reads', sprintf(_MS_MW_READS, $post->getReads()));

if (empty($xoopsUser) && $mc['comman']){
	$coms = 1;
} elseif (empty($xoopsUser) && !$mc['comman']){
	$coms = 0;
	$tpl->assign('show_edit',0);
} elseif ($xoopsUser){
	$coms = 1;
	if ($post->getAuthor()==$xoopsUser->uid() || $xoopsUser->isadmin()){
		$tpl->assign('show_edit',1);
		$tpl->assign('lang_edit',_EDIT);
		$editlink = mw_get_url() . ($mc['permalinks']==1 ? "edit=".$post->getID() : "edit/".$post->getID());
		if ($xoopsUser->isAdmin()){
			$editlink = mw_get_url().'admin/posts.php?op=edit&amp;post='.$post->getID();
		}
		$tpl->assign('edit_link', $editlink);
	}
	$tpl->assign('xoops_umail', $xoopsUser->email());
}

$tpl->assign('lang_blog', _MS_MW_BLOGNAME);
$tpl->assign('lang_url', mw_get_url());

# Cargamos los comentarios del Artículo	
if ($page<=0){
	$path = explode("/", $request);
	$srh = array_search('page', $path);
	if (isset($path[$srh]) && $path[$srh]=='page')	if (!isset($path[$srh])){ $page = 0; } else { $page = $path[$srh +1]; }
}

# Cargamos los comentarios del Artículo
$tracks = array();
$tracks =& $post->loadTrackbacks(false);
foreach($tracks as $k){
	$texto = $k['excerpt'];
	$texto = $myts->censorString($texto);
	$texto = $myts->displayTarea($texto, 1, 1, 1, 1, 1);
	$tpl->append('trackbacks', array('id'=>$k['id_t'],'texto'=>$texto,'titulo'=>$k['title'],
			'fecha'=>date($mc['date'], $k['fecha']) . " " . date($mc['hour'], $k['fecha']),
			'blogname'=>$k['blog_name'],'url'=>$k['url'],'lecturas'=>$post->getReads()));
}

$xmh .= "\n<script type='text/javascript' src='".mw_get_url()."include/functions.js'></script>";

if ($post->getStatus() || ($xoopsUser && $xoopsUser->uid() != $post->getAuthor())) $post->addReads();

// Navegación entre artículos
$sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE id_post<".$post->getID()." ORDER BY id_post DESC LIMIT 0, 1";
$result = $db->query($sql);
$pn = new MWPost();
// Anterior
if ($db->getRowsNum($result)>0){
    $pn->assignVars($db->fetchArray($result));
    $tpl->assign('prev_post', array('link'=>$pn->getPermaLink(), 'title'=>$pn->getTitle()));
}

// Siguiente
$sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE id_post>".$post->getID()." ORDER BY id_post ASC LIMIT 0, 1";
$result = $db->query($sql);
if ($db->getRowsNum($result)>0){
    $pn->assignVars($db->fetchArray($result));
    $tpl->assign('next_post', array('link'=>$pn->getPermaLink(), 'title'=>$pn->getTitle()));
}

// Sitios de marcadores (Redes Sociales)
foreach ($socials as $bm){
    $tpl->append('socials', array('icon'=>$bm->icon(),'alt'=>$bm->text(),'link'=>$bm->link($post->getTitle(),$post->getPermaLink(), $util->filterTags(substr($post->getHomeText(), 0, 254)))));
}

include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';

$item = $post->getID();
    
XoopsCommentHandler::renderNavbar($item, $post->getTitle(), $post->getPermaLink(), $post->getPermaLink(), 'post');
XoopsCommentHandler::getComments($item, $xoopsModule->mid(), true, $post->getPermaLink());
 
include 'footer.php';

?>