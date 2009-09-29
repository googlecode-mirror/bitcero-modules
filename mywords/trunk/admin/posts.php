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
	global $db, $tpl, $adminTemplate, $xoopsModule, $xoopsModuleConfig;
	
	$mc =& $xoopsModuleConfig;
	
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
		$sql .= ($aprovado>=0 ? " AND " : ($cat > 0 ? " AND " : " WHERE ")) . "title LIKE '%$keyw%'";
		$tpl->assign('keyw', $keyw);
	}
	
	/**
	 * Paginacion de Resultados
	 */
	$page = rmc_server_var($_GET, 'page', 1);
	$limit = isset($limit) && $limit>0 ? $limit : 15;
	list($num) = $db->fetchRow($db->query($sql));
	
	$tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
	
	$nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('posts.php?limit='.$limit.'&page={PAGE_NUM}');
	
	$sql .= " ORDER BY pubdate DESC LIMIT $start,$limit";
	$sql = str_replace("SELECT COUNT(*)", "SELECT *", $sql);

	$result = $db->query($sql);
	$posts = array();
	while ($row = $db->fetchArray($result)){
		$post = new MWPost();
		$post->assignVars($row);
		
		# Enlace para el artículo
		$day = date('d', $post->getVar('pubdate'));
		$month = date('m', $post->getVar('pubdate'));
		$year = date('Y', $post->getVar('pubdate'));
		$postlink = MWFunctions::get_url();
		$postlink .= $mc['permalinks']==1 ? '?post='.$post->id() : ($mc['permalinks']==2 ? "$day/$month/$year/".$post->getFriendTitle()."/" : "post/".$post->getID());

		$posts[] = array(
			'id'=>$post->id(), 
			'title'=>$post->getVar('title'),
			'date'=>formatTimeStamp($post->getVar('pubdate')),
			'comments'=>0,
			'uid'=>$post->getVar('author'),
			'uname'=>$post->getVar('authorname'),
			'link'=>$postlink,
			'status'=>$post->getVar('status'),
			'categories'=>$post->get_categories_names(true, ',', true, 'admin'),
			'tags'=>$post->tags(false)
		);
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
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
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
    //RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js');
    //RMTemplate::get()->add_script(RMCURL.'/include/js/forms.js');
    //RMTemplate::get()->add_head('<script type="text/javascript">$("form#mw-form-posts").validate();</script>');
    
	include '../templates/admin/mywords_formposts.php';
	
	xoops_cp_footer();
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
		savePost(0);
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