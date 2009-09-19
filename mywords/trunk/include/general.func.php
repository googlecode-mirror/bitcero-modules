<?php
// $Id: general.func.php 42 2009-09-15 20:39:58Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Función para obtener las categorías en un array
 */
function arrayCategos(&$ret,$saltos=0,$parent=0, $exclude=null){
	
	$db = Database::getInstance();
	
	$result = $db->query("SELECT * FROM ".$db->prefix("mw_categos")." WHERE parent='$parent' ORDER BY `id_cat`");
	
	while ($row = $db->fetchArray($result)){
		if (is_array($exclude) && (in_array($row['parent'], $exclude) || in_array($row['id_cat'], $exclude))){
			$exclude[] = $row['id_cat'];
		} else {
			$rtn = array();
			$rtn = $row;
			$rtn['saltos'] = $saltos;
			$ret[] = $rtn;
		}
		arrayCategos($ret, $saltos + 2, $row['id_cat'], $exclude);
	}
	
	return true;
	
}
/**
 * Verifica todas las categorías en las que el editor puede publicar
 */
function editorCategos(&$categos){
	global $xoopsUser, $db;
	
	if ($xoopsUser=='') return;
	$result = $db->query("SELECT * FROM ".$db->prefix("mw_editors")." WHERE uid='".$xoopsUser->uid()."'");
	if ($db->getRowsNum($result)<=0) return;
	
	$ret = array();
	$row = $db->fetchArray($result);
	$allowed = explode(',',$row['categos']);
	foreach (categos as $k){
		if (in_array($k, $allowed)) $ret[] = $k;
	}
	
	return $ret;
	
}
/**
 * Comprueba si un usuario es un editor autorizado
 * @param int $uid Id del usuario a comprobar
 * @return bool
 */
function isEditor($uid, $categos){
	global $db;
	
	$result = $db->query("SELECT * FROM ".$db->prefix("mw_editors")." WHERE uid='$uid'");
	if ($db->getRowsNum($result)<=0) return false;
	
	$row = $db->fetchArray($result);
	$allowed = explode(',',$row['categos']);
	foreach (categos as $k){
		if (in_array($k, $allowed)) return true;
	}
	
	return false;
	
}

/**
 * Devuelve la lista de etiquetas permitidas como
 * un array, una cadena o ambos
 * @param int $as  0 = Array, 1 = String, 2 = Array (ambos)
 * @return array
 * @return string
 */
function getAllowedTags($as = 0){
	global $mc;
	
	$tags = explode(';',$mc['tags']);
	$strtags = '';
	foreach ($tags as $k){
		$st = explode("{",$k);
		$strtags .= "<".$st[0];
		if (count($st)>1){
			$st[1] = str_replace("}",'',$st[1]);
			$allowedtags[$st[0]] = explode(',',str_replace("}",'',$st[1]));
			foreach (explode(",",$st[1]) as $k1){
				$strtags .= " $k1=\"\"";
			}
		}else {
			$allowedtags[$st[0]] = array();;
		}
		$strtags .= "> ";
	}
	
	if ($as==0) return $allowedtags;
	if ($as==1) return $strtags;
	if ($as==2){
		$ret = array();
		$ret['tags'] = $allowedtags;
		$ret['text'] = $strtags;
		return $ret;
	}
}

/**
* @desc Función para incrementar el número de comentarios
*/
function mw_com_update($post, $total){
	$db =& Database::getInstance();
	$sql = "UPDATE ".$db->prefix("mw_posts")." SET comentarios='$total' WHERE id_post='$post'";
	$db->queryF($sql);
}

/**
* Get the list of used metas from db
*/


/**
* Get a list of posts according to given parameters
*/
function mw_get_posts($limit, $object=true, $category=null, $approved=1, $status=1, $read_access = true){
	global $xoopsModuleConfig, $xoopsModule;
	
	$socials = mw_get_bookmarks();
	$util = RMUtils::getInstance();
	
	$mc = ($xoopsModuel && $xoopsModule->dirname()=='mywords' ? $xoopsModuleConfig : $util->moduleConfig('mywords'));
	
	$db = Database::getInstance();
	if (is_null($category)){
		$result = $db->query("SELECT * FROM ".$db->prefix("mw_posts")." WHERE estado=$status AND aprovado='$approved' ORDER BY fecha DESC LIMIT 0,$limit");
	} else {
		$tbl1 = $db->prefix("mw_categos");
		$tbl2 = $db->prefix("mw_catpost");
		$tbl3 = $db->prefix("mw_posts");
		$result = $db->query("SELECT $tbl3.* FROM $tbl2, $tbl3 WHERE $tbl2.cat='$category' AND $tbl3.id_post=$tbl2.post AND $tbl3.estado='$status' AND $tbl3.aprovado='$approved' ORDER BY $tbl3.fecha DESC LIMIT 0,$limit");
	}
	
	$posts = array();
	
	while ($row = $db->fetchArray($result)){
	    $post = new MWPost();
	    $post->assignVars($row);
	    if ($read_access && !$post->readAccess()) continue;
	    
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
	    
	    $ap = array(
	    	'id'				=> $post->getID(),
	    	'title'				=> $post->getTitle(),
	    	'text'				=> $texto,
	        'categories'		=> $post->categos(2),
	        'link'				=> $link, 
	        'lang_permalink'	=> sprintf(_MS_MW_PERMALINK, $post->getTitle()),
	        'published'			=> $publicado,
	        'lang_comment'		=> sprintf(_MS_MW_COMMENTON, $post->getTitle()),
	        'comments_count'	=> $post->getComments(),
	        'comments'			=> sprintf(_MS_MW_NUMCOMS, $post->getComments()),
	        'continue'			=> ($post->moretext() ? sprintf(_MS_MW_CONTINUE, $post->getTitle()) : ''),
	        'bookmarks'			=> $bms,
	        'fields'				=> $post->get_meta()
	    );
	    
	    if ($object){
	    	$ret = new stdClass();
			foreach ($ap as $var => $value){
				$ret->$var = $value;
			}
			$posts[] = $ret;
	    } else {
			$posts[] = $ap;
	    }
	    
	}
	
	return $posts;
	
}

/**
* Get all bookmarks sites or socials sites
*/
function mw_get_bookmarks(){
	$db = Database::getInstance();
	$sql = "SELECT * FROM ".$db->prefix("mw_bookmarks");
	$result = $db->query($sql);
	$socials = array();
	$i = 0;
	while ($row = $db->fetchArray($result)){
	    $socials[$i] = new MWBookmark();
	    $socials[$i]->assignVars($row);
	    $i++;
	}
	return $socials;
}


