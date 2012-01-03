<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Emotions for tiny and exmcode editors
*/
include XOOPS_ROOT_PATH."/include/common.php";
include_once RMCPATH.'/loader.php';
XoopsLogger::getInstance()->activated = false;
XoopsLogger::getInstance()->renderingEnabled = false;

load_mod_locale('rmcommon','emots-');

$db = XoopsDatabaseFactory::getDatabaseConnection();
$result = $db->query("SELECT * FROM ".$db->prefix("smiles")." ORDER BY id");
while ($row = $db->fetchArray($result)){
	$emotions[] = array(
		'id'=>$row['id'], 
		'file'=>XOOPS_UPLOAD_URL.'/'.$row['smile_url'],
		'desc'=>$row['emotion'],
		'code'=>$row['code']
	);
}

// Load new icons from plugins
RMEvents::get()->run_event('rmcommon.load_emotions',$emotions,'exmcode');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="editor-popups.js"></script>
<script type="text/javascript" src="<?php echo RMCURL; ?>/include/js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $eurl; ?>/plugins/emotions/css/emotions.css" />
<script type="text/javascript">
	$(document).ready(function(){
		$("img").click(function(){
			exmPopup.insertText($(this).attr("alt"));
			exmPopup.closePopup();
		});
	});
</script>
</head>
<body>
<table width="100%" cellpadding="2" cellspacing="0">
<tr align="center">
<?php $i = 0; ?>
<?php foreach($emotions as $icon): ?>
<?php if($i>=6): ?>
	</tr><tr>
<?php 
	$i=0;
	endif; ?>
<td><img src="<?php echo $icon['file']; ?>" title="<?php echo $icon['desc']; ?>" alt="<?php echo $icon['code']; ?>" /><br /><?php echo $icon['code']; ?></td>
<?php 
	$i++;
endforeach; ?>
</tr>
</table>
</body>
</html>
