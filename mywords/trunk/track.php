<?php
// $Id: track.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include 'header.php';

function response($error, $msg){
	echo "<?xml version=\"1.0\" encoding=\""._CHARSET."\"?>
  <response>
  <error>$error</error>
  ".($msg!='' ? "<message>$msg</message>" : '')."
</response>";
}

foreach ($_GET as $k => $v){
	$$k = $v;
}

if (count($_POST)==0){
	header('location: '.mw_get_url());
	die();
}

foreach ($_POST as $k => $v){
	$$k = $v;
}

if ($post<=0){
	response(1, _MS_MW_ERRNOPOST);
	die();
}

include_once XOOPS_ROOT_PATH.'/rmcommon/object.class.php';
include_once XOOPS_ROOT_PATH.'/modules/mywords/class/post.class.php';
$post = new MWPost($post);

if ($mc['pings']==0){
	response(1, _MS_MW_NOPINGS);
	die();
}

if (!$post->getAllowPings()){
	response(1, _MS_MW_NOPINGS);
	die();
}

if ($url==''){
	response(1, _MS_MW_URLMISSING);
	die();
}

if ($title==''){
	response(1, _MS_MW_NOTITLE);
	die();
}

if ($excerpt==''){
	response(1, _MS_MW_NOEXCERPT);
	die();
}

if ($blog_name==''){
	response(1, _MS_MW_NOBLOGNAME);
	die();
}

$allowedtags = array (
	'a' => array (
		'href' => array (), 
		'title' => array ()
	), 
	'abbr' => array (
		'title' => array ()
	), 
	'acronym' => array (
		'title' => array ()
	), 
	'b' => array (), 
	'blockquote' => array (
		'cite' => array ()
	),
	'code' => array (),
	'em' => array (),
	'i' => array (),
	'strike' => array (),
	'strong' => array (),
	);


$title = RMUtils::filterTags($title, array(), array());
$excerpt = RMUtils::filterTags($excerpt, $allowedtags);
$blog_name = RMUtils::filterTags($blog_name);

$db = Database::getInstance();
// Guardamos los datos en la base de datos
$sql = "SELECT COUNT(*) FROM ".$db->prefix('mw_trackbacks')." WHERE post='".$post->getID()."' AND title='$title' AND url='$url' LIMIT 0,1";
list($num) = $db->fetchRow($db->query($sql));
if ($num>0){
	response(1, _MS_MW_EXISTSTRACK);
	die();
}

$sql = "INSERT INTO ".$db->prefix('mw_trackbacks')." (`fecha`,`title`,`blog_name`,`excerpt`,`url`,`post`)
		VALUES ('".time()."','$title','$blog_name','$excerpt','$url','".$post->getID()."')";
if ($db->queryF($sql)){
	$post->setTBCount($post->getTBCount() + 1);
	$post->update();
	response(0,'');
	die();
} else {
	response(1, _MS_MW_ERRDB);
	die();
}

?>