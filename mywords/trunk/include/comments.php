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
* @desc Función para incrementar el número de comentarios
*/
function mw_com_update($post, $total){
	$db =& Database::getInstance();
	$sql = "UPDATE ".$db->prefix("mw_posts")." SET comments='$total' WHERE id_post='$post'";
	$db->queryF($sql);
}
