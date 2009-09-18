<?php
// $Id: posts.php 50 2009-09-17 20:36:31Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','posts');
require 'header.php';

/**
 * Muestra los envíos existentes
 */
function showPosts($aprovado = -1){
	global $db, $tpl, $mc, $adminTemplate, $xoopsModule;
	
	$keyw = '';
	$op = '';
	$cat = 0;
	foreach ($_REQUEST as $k => $v){
		$$k = $v;
	}
	
	$tbl1 = $db->prefix("mw_posts");
	$tbl2 = $db->prefix("mw_catpost");
	
	if ($cat>0){
		$sql = "SELECT COUNT(*) FROM $tbl1 a, $tbl2 b WHERE b.cat='$cat' AND a.id_post=b.post";
	} else {
		$sql = "SELECT COUNT(*) FROM ".$db->prefix("mw_posts");
	}	
	
	if ($aprovado>=0){
		$sql .= $cat > 0 ? " AND a.aprovado='$aprovado'" : " WHERE aprovado=$aprovado";
	}
	
	if (trim($keyw)!=''){
		$sql .= ($aprovado>=0 ? " AND " : ($cat > 0 ? " AND " : " WHERE ")) . "titulo LIKE '%$keyw%'";
		$tpl->assign('keyw', $keyw);
	}
	
	/**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_GET, 'page', '');
	$limit = isset($limit) && $limit>0 ? $limit : 15;
	list($num) = $db->fetchRow($db->query($sql));
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;
    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('posts.php?limit='.$limit.'&page={PAGE_NUM}');
	
	$sql .= " ORDER BY fecha DESC LIMIT $start,$limit";
	$sql = str_replace("SELECT COUNT(*)", "SELECT *", $sql);
	
	$result = $db->query($sql);
	$posts = array();
	while ($row = $db->fetchArray($result)){
		$post = new MWPost();
		$post->assignVars($row);
		
		# Enlace para el artículo
		$day = date('d', $post->getDate());
		$month = date('m', $post->getDate());
		$year = date('Y', $post->getDate());
		$postlink = mw_get_url();
		$postlink .= $mc['permalinks']==1 ? '?post='.$post->getID() : ($mc['permalinks']==2 ? "$day/$month/$year/".$post->getFriendTitle()."/" : "post/".$post->getID());
		
		$posts[] = array('id'=>$post->getID(), 'titulo'=>$post->getTitle(),'fecha'=>formatTimeStamp($post->getDate(),'s'),
				'coms'=>$post->getComments(), 'uid'=>$post->getAuthor(), 'uname'=>$post->getAuthorName(),
				'link'=>$postlink, 'aprovado'=>$post->getApproved(),'tracks'=>$post->getTBCount(),'categos'=>$post->categos(0));
	}
	
	MWFunctions::include_required_files();
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '._AS_MW_POSTSPAGE);
	xoops_cp_header();
	include_once '../templates/admin/mywords_posts.php';    
	xoops_cp_footer();
}
/**
 * Muestra el formulario para la creación de un nuevo artículo
 */
function newForm($edit = 0){
	global $db, $xoopsModule, $myts, $util, $xoopsConfig, $tpl, $xoopsSecurity;
	
	define('RMCSUBLOCATION','new_post');
	
	if ($edit){
		$id = isset($_REQUEST['post']) ? $_REQUEST['post'] : 0;
		if ($id<=0){
			redirectMsg('posts.php', _AS_MW_NOID, 1);
			die();
		}
		$post = new MWPost($id);
		if ($post->isNew()){
			redirectMsg('posts.php', _AS_MW_NOID, 1);
			die();
		}
	}
	
	MWFunctions::include_required_files();
	xoops_cp_location('<a href="./">'.$xoopsModule->name().'</a> &raquo; '.($edit ? _AS_MW_EDITPOSTTITLE : _AS_MW_NEWPOSTTITLE));
	$head = '<script type="text/javascript" src="'.MW_URL.'/include/forms_post.js"></script>';
	xoops_cp_header($head);
	
    include RMCPATH.'/class/form.class.php';
    /*include RMCPATH.'/class/fields/formelement.class.php';
    include RMCPATH.'/class/fields/editor.class.php';*/
    TinyEditor::getInstance()->add_config('elements','content_editor');
	TinyEditor::getInstance()->add_config('theme_advanced_buttons1', 'bold,italic,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,spellchecker,fullscreen,|,exm_more,exm_adv', true);
	TinyEditor::getInstance()->add_config('theme_advanced_buttons2','formatselect,underline,justifyfull,forecolor,|,pastetext,pasteword,removeformat,|,media,charmap,|,outdent,indent,|,undo,redo,|,exm_img,exm_icons,exm_page', true);
    $editor = new RMFormEditor('','content','99%','300px', $edit ? $post->getVar('content','n') : '');
    
    // Get current metas
    $meta_names = MWFunctions::get()->get_metas();
    
	include '../templates/admin/mywords_formposts.php';
	
	xoops_cp_footer();
}
/**
 * Esta función permite guardar y publicar un envío
 */
function savePost($state=0){
	global $db, $util, $xoopsUser, $myts, $mc;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	/*if (!$util->validateToken()){
		redirectMsg('posts.php?op=new', _AS_MW_ERRTOKEN, 1);
		die();
	}*/
	
	if ($titulo==''){
		redirectMsg('posts.php?op=new', _AS_MW_ERRTITLE, 1);
		die();
	}
	$titulo = $myts->addSlashes($titulo);
	
	if ($texto==''){
		redirectMsg('posts.php?op=new', _AS_MW_ERRTEXT, 1);
		die();
	}
	
	if (empty($categos)){
		redirectMsg('posts.php?op=new', _AS_MW_ERRCATS, 1);
		die();
	}
	
	if (!is_dir(XOOPS_ROOT_PATH.'/uploads/mywords')){
        mkdir(XOOPS_ROOT_PATH.'/uploads/mywords');
        chmod(XOOPS_ROOT_PATH.'/uploads/mywords',0777);
    }
	$up = new RMUploader(true);
	$folder = XOOPS_ROOT_PATH.'/uploads/mywords';
	$filename = '';
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['filesize'] * 1024);
	
	if ($up->fetchMedia('blockimg')){
		if ($up->upload()){
			$filename = $up->getSavedFileName();
			$fullpath = $up->getSavedDestination();
			
			$ext = substr($filename, strlen($filename) - 3);
			$redim = new RMImageControl($fullpath, $fullpath);
			$redim->setTargetFile(XOOPS_ROOT_PATH.'/uploads/mywords/'.$filename);
			$redim->resizeAndCrop($mc['imgsize'][0],$mc['imgsize'][1]);
			$imgfile = $filename;
		} else {
			$imgfile = '';
		}
	} else {
		$imgfile = '';
	}
	
	#Guardamos los datos del Post
	$post = new MWPost();
	$post->setTitle($titulo);
	$post->setFriendTitle($util->sweetstring($titulo));
	$post->setAuthor($xoopsUser->uid());
	$post->setAuthorName($xoopsUser->uname());
	$post->setDate(time());
	$post->setModDate(time());
	$post->setText($texto);
	$post->setComments(0);
	$post->addToCategos($categos);
	$post->setAllowPings(1);
	$post->setExcerpt(isset($excerpt) ? $excerpt : '');
	$post->setApproved(1);
	$post->setBlockImage($imgfile);
	if ($state==0 || $state==1){
		$post->setStatus(0);
	} else {
		$post->setStatus(1);
	}
	$post->setTrackBacks($trackbacks);
	$post->setHTML(isset($dohtml) ? 1 : 0);
	$post->setXCode(isset($doxcode) ? 1 : 0);
	$post->setBR(isset($dobr) ? 1 : 0);
	$post->setDoImage(isset($doimage) ? 1 : 0);
	$post->setSmiley(isset($dosmilye) ? 1 : 0);
	
	// Add Metas
	foreach($meta_name as $k => $v){
		$post->add_meta($v, $meta_value[$k]);
	}
	
	if ($post->save()){
		$xoopsUser->incrementPost();
		redirectMsg($state==0 ? 'posts.php?op=edit&post='.$post->getID() : 'posts.php', _AS_MW_DBOK, 0);
	} else {
		redirectMsg('posts.php?op=new', _AS_MW_DBERROR . "<br />" . $post->errors(), 1);
	}
	
	
}
/**
 * Almacena la información de un artículo editado
 */
function saveEdited($status=0){
	global $db, $util, $xoopsUser, $myts, $mc;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	$id = $post;
	if ($id<=0){
		redirectMsg('posts.php', _AS_MW_NOID, 1);
		die();
	}
	/*if (!$util->validateToken()){
		redirectMsg('posts.php?op=edit&post='.$id, _AS_MW_ERRTOKEN, 1);
		die();
	}*/
	$post = new MWPost($id);
	if ($post->isNew()){
		redirectMsg('posts.php', _AS_MW_NOID, 1);
		die();
	}
	
	if ($titulo==''){
		redirectMsg('posts.php?op=edit&post='.$id, _AS_MW_ERRTITLE, 1);
		die();
	}
	
	if ($texto==''){
		redirectMsg('posts.php?op=edit&post='.$id, _AS_MW_ERRTEXT, 1);
		die();
	}
	
	if (empty($categos)){
		redirectMsg('posts.php?op=edit&post='.$id, _AS_MW_ERRCATS, 1);
		die();
	}
	
	if ($titulo_amigo==''){
		redirectMsg('posts.php?op=edit&post='.$id, _AS_MW_ERRFRIENDTITLE, 1);
		die();
	}
	
	$tracks = explode(" ", $trackbacks);
	$pinged = $post->getPinged(true);
	$oktracks = array();
    $errors = '';
	foreach ($tracks as $k){
		if (in_array($k, $pinged)) continue;
		$oktracks[] = $k;
	}
	
	if (!is_dir(XOOPS_ROOT_PATH.'/uploads/mywords')){
        mkdir(XOOPS_ROOT_PATH.'/uploads/mywords', decoct(511));
        chmod(XOOPS_ROOT_PATH.'/uploads/mywords', decoct(511));
    }
	$up = new RMUploader(true);
	$folder = XOOPS_ROOT_PATH.'/uploads/mywords/';
	$filename = '';
	$up->prepareUpload($folder, array($up->getMIME('jpg'),$up->getMIME('png'),$up->getMIME('gif')), $mc['filesize'] * 1024);
	
	if ($up->fetchMedia('blockimg')){
        @unlink(XOOPS_ROOT_PATH.'/uploads/mywords/'.$post->getBlockImage());
		if ($up->upload()){
			$filename = $up->getSavedFileName();
			$fullpath = $up->getSavedDestination();

			$redim = new RMImageControl($fullpath, $fullpath);
			$ext = substr($filename, strlen($filename) - 3);
			$redim->setTargetFile(XOOPS_ROOT_PATH.'/uploads/mywords/'.$filename);
			$redim->resizeAndCrop($mc['imgsize'][0],$mc['imgsize'][1]);
			$imgfile = $filename;
		} else {
            $errors .= $up->getErrors();
			@unlink(XOOPS_ROOT_PATH.'/uploads/mywords/'.$post->getBlockImage());
			$imgfile = '';
		}
	} else {
        $errors .= $up->getErrors();
		$imgfile = $post->getBlockImage();
	}
	
	$post->setTitle($titulo);
	$post->setFriendTitle($util->sweetstring($titulo_amigo));
	
	$autor = new XoopsUser($autor);
	$post->setModDate(time());
	$post->setText($texto);
	$post->setAllowPings($allowpings);
	$post->addToCategos($categos);
	$post->setTrackBacks($oktracks);
	$post->setStatus($estado);
	$post->setExcerpt($excerpt);
	$post->setBlockImage($imgfile);
	$post->setAuthor($autor->uid());
	$post->setAuthorName($autor->uname());
	$post->setHTML(isset($dohtml) ? 1 : 0);
	$post->setXCode(isset($doxcode) ? 1 : 0);
	$post->setBR(isset($dobr) ? 1 : 0);
	$post->setDoImage(isset($doimage) ? 1 : 0);
	$post->setSmiley(isset($dosmiley) ? 1 : 0);
    
    // Add Metas
	foreach($meta_name as $k => $v){
		$post->add_meta($v, $meta_value[$k]);
	}
	
	if ($post->update()){
		redirectMsg($status==0 ? 'posts.php?op=edit&post='.$post->getID() : 'posts.php', _AS_MW_DBOK.($errors!='' ? '<br />'.$errors : ''), 0);
	} else {
		redirectMsg('posts.php?op=edit&post='.$post->getID(), _AS_MW_DBERROR . "<br />" . $post->errors().($errors!='' ? '<br />'.$errors : ''), 1);
	}
}
/**
 * Elimina un artículo de la base de datos
 */
function deletePost(){
	global $util;
	
	$id = isset($_REQUEST['post']) ? $_REQUEST['post'] : 0;
	$ok = isset($_POST['ok']) ? $_POST['ok'] : 0;
	
	if ($ok){
		
		if (!$util->validateToken()){
			redirectMsg('posts.php', _AS_MW_ERRTOKEN, 1);
			die();
		}
		
		$post = new MWPost($id);
		if ($id<=0){
			redirectMsg('posts.php', _AS_MW_NOID, 1);
			die();
		}
		if ($post->isNew()){
			redirectMsg('posts.php', _AS_MW_NOID, 1);
			die();
		}
		
		if ($post->delete()){
			redirectMsg('posts.php', _AS_MW_DBOK, 0);
		} else {
			redirect_header('posts.php', _AS_MW_DBERROR . "<br />" . $post->errors(), 1);
		}
		
	} else {
		
		xoops_cp_header();
		$hiddens['op'] = 'delete';
		$hiddens['post'] = $id;
		$hiddens['ok'] = 1;
		$buttons['sbt']['type'] = 'submit';
		$buttons['sbt']['value'] = _DELETE;
		$buttons['cancel']['type'] = 'button';
		$buttons['cancel']['value'] = _CANCEL;
		$buttons['cancel']['extra'] = 'onclick="history.go(-1);"';
		
		
		$util->msgBox($hiddens, 'posts.php', _AS_MW_CONFIRMDEL, '../images/question.png', $buttons, true, 400);
		xoops_cp_footer();
	}
	
}

function approveBulk($aprovado){
	global $db;
	
	$posts = isset($_REQUEST['posts']) ? $_REQUEST['posts'] : array();
	
	if (count($posts)<=0){
		redirect_header($aprovado ? 'posts.php?op=waiting' : 'posts.php?op=approved', 2, _AS_MW_SELECTONE);
		die();
	}
	
	$sql = "UPDATE ".$db->prefix("mw_posts")." SET aprovado='$aprovado' WHERE ";
	$cond = '';
	foreach ($posts as $k){
		if ($cond==''){
			$cond.="id_post='$k'";
		} else {
			$cond.=" OR id_post='$k'";
		}
	}
	
	$sql .= $cond;
	if ($db->queryF($sql)){
		redirectMsg($aprovado ? 'posts.php?op=waiting' : 'posts.php?op=approved', _AS_MW_DBOK, 0);
	} else {
		redirectMsg($aprovado ? 'posts.php?op=waiting' : 'posts.php?op=approved', _AS_MW_DBERROR . '<br />' . $db->error(), 1);
	}
	
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op){
	case 'new':
		newForm();
		break;
	case 'saveret':
		savePost(0);
		break;
	case 'save':
		savePost(1);
		break;
	case 'publish':
		savePost(2);
		break;
	case 'edit':
		newForm(1);
		break;
	case 'saveretedit':
		saveEdited(0);
		break;
	case 'saveedit':
	case 'publishedit':
		saveEdited(1);
		break;
	case 'delete':
		deletePost();
		break;
	case 'trackbacks':
		include 'trackbacks.php';
		break;
	case 'waiting':
		showPosts(0);
		break;
	case 'approved':
		showPosts(1);
		break;
	case 'aprove':
		approveBulk(1);
		break;
	case 'unaprove':
		approveBulk(0);
		break;
	default:
		showPosts();
		break;
}
?>