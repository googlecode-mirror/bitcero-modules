<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $xoops_langcode; ?>" lang="<?php echo $xoops_langcode; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $xoops_charset; ?>" />
	<meta http-equiv="content-language" content="<?php echo $xoops_langcode; ?>" />
	<title><?php _e('Notes & References','docs'); ?> &raquo; <?php echo $xoops_sitename; ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo $theme_css; ?>" />
	<style type="text/css">
		body{
			background:#FFF;
			margin: 10px;

		}
		.outer{
			width: 100%;
		}
	</style>
	<script type="text/javascript">
		function insert(id_ref){
			window.opener.insertReference(id_ref);	
			window.close();	
		}
	</script>
</head>
<body>
<div id='nav'>
 <form name="frm" method="POST" action="./references.php">
 <table class="outer" cellspacing="1" width="100%">
        <tr class="even">
	    <td><strong><?php _e('Buscar','docs'); ?></strong>
		<input type="text" name="search" size="15" value="<?php echo $search; ?>"/>
		<input name="sbtsearch" class="formButton" value="<?php _e('Buscar','docs'); ?>" type="submit"/>
        <input name="id" value="<?php echo $id; ?>" type="hidden" />
        <input name="section" value="<?php echo $id_sec; ?>" type="hidden" />
        <input name="pag" value="<?php echo $page; ?>" type="hidden" />    
	    </td>
            <td align="right" class="options_top">
            <ul>
                <li>
                    <a href="javascript:;" id="option-top"><?php _e('Options','docs'); ?></a>
                    <ul>
                        <?php foreach($options as $opt): ?>
                        <li><a title="<?php echo $opt['tip']; ?>" href="<?php echo $opt['href']; ?>"<?php if($opt['attrs']!=''): echo $opt['attrs']; endif; ?>><?php echo $opt['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
            </td>
        </tr>
    </table>
  </form>
</div>
<?php foreach($rmc_messages as $message): ?>
<div class="<?php if($message['level']): ?>errorMsg<?php else: ?>infoMsg<?php endif; ?>">
    <?php echo html_entity_decode($message['text']); ?>
</div>
<?php endforeach; ?>
<form name="frmref" method="POST" action="references.php">
<table class='outer' cellspacing="1">
	<tr>
		<th colspan="4"><?php _e('Existing notes and references','docs'); ?></th>
	</tr>
	<tr class="head" align="center">
		<td><input type="checkbox" name="checkAll" onchange="xoopsCheckAll('frmref', 'checkAll');"/></td>
		<td><?php _e('ID','docs'); ?></td>
		<td><?php _e('Title','docs'); ?></td>
		<td><?php _e('Options','docs'); ?></td>
		
	</tr>
	<?php foreach($references as $ref): ?>
	<tr align="center" class="<?php echo tpl_cycle("even,odd"); ?>">
		<td width="20" align="center"><input type="checkbox" name="refs[]" value="<?php echo $ref['id']; ?>" /></td>
		<td><?php echo $ref['id']; ?></td>
		<td align="left"><a href="javascript:;" onclick="insert(<?php echo $ref['id']; ?>);"><?php echo $ref['title']; ?></a></td>
		<td><a href="javascript:;" onclick="insert(<{$ref.id}>);"><{$lang_insert}></a> &bull; 
		<a href="./references.php?op=edit&amp;section=<{$id_sec}>&amp;id=<{$id}>&amp;text=<{$id_text}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>&amp;search=<{$search}>&amp;ref=<{$ref.id}>"><{$lang_edit}></a></td>
	</tr>
	<?php endforeach; ?>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" alt="" /></td>
		<td colspan="3">
		<input name="delete" class="formButton" type="submit" value="<{$lang_del}>" onclick="document.forms['frmref'].op.value='delete'; return confirm('<{$lang_confirm}>');" />
		<input name="close" class="formButton" type="button" value="<{$lang_close}>" onclick="tinyMCEPopup.close();" />
		</td>
	</tr>
</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input name="op" type="hidden" />
<input name="id" value="<{$id}>" type="hidden" />
<input name="id_sec" value="<{$id_sec}>" type="hidden" />
<input name="limit" value="<{$limit}>" type="hidden" />
<input name="pag" value="<{$pag}>" type="hidden" />
<input name="search" value="<{$search}>" type="hidden" />
</form>
<?php $nav->display(false); ?>

<?php echo $other_content; ?>

<div id="resources-list" title="<?php _e('Select Resource','docs'); ?>"></div>
<div id="resources-form" title="<?php _e('Create Note','docs'); ?>">
<form name="frmRefs" id="frm-refs" method="post" action="references.php">
<label><?php _e('Title:','docs'); ?></label>
<input type="text" name="title" id="note-title" value="" class="required" />
<label><?php _e('Content:','docs'); ?></label>
<textarea name="reference" id="note-content" cols="45" rows="6" class="required"></textarea>
<input type="hidden" name="action" value="save" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
</div>
</body>
</html>
