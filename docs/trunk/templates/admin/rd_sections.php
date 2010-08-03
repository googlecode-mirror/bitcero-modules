<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php if(!isset($res)): _e('Sections Management','docs'); else: echo sprintf(__('Sections in %s','docs'), $res->getVar('title')); endif; ?></h1>
<form name="frmres" method="GET" action="sections.php">
	<strong><{$lang_res}></strong>
	<select name="id" onchange="submit()">
		<option><{$lang_select}></option>
		<{foreach item=resource from=$resources}>
			<option value="<{$resource.id}>" <{if $id==$resource.id}>selected<{/if}>><{$resource.title}></option>
		<{/foreach}>
	</select>
</form>

<form name="frmsec" method="POST" action="sections.php" id="frm-sections">
<div class="rd_loptions">
    <a href="sections.php?id=<?php echo $id; ?>"><?php _e('List','docs'); ?></a> |
    <a href="sections.php?id=<?php echo $id; ?>&amp;action=new"><?php _e('New Section','docs'); ?></a>
</div>
<table class="outer" width="100%" cellspacing="0">
	<tr class="head" align="center">
		<th width="30"><?php _e('ID','docs'); ?></th>
		<th align="left"><?php _e('Title','docs'); ?></th>
	</tr>
	<?php foreach($sections as $section): ?>
	<tr align="center"  class="<?php echo tpl_cycle("even,odd"); ?>">
		<td><?php echo $section['id']; ?></td>
		<td align="left" style="padding-left:<?php echo($section['indent']*5); ?>px;">
            <?php if($section['indent']>0): ?><img src="<?php echo XOOPS_URL; ?>/images/root.gif" align="absmiddle" alt="" /><?php endif; ?> <?php echo $section['title']; ?>
            <span class="rmc_options">
                <a href="./sections.php?action=edit&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php _e('Edit','docs'); ?></a> |
                <a href="./sections.php?op=delete&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php _e('Delete','docs'); ?></a> |
                <?php if(!$section['featured']): ?><a href="./sections.php?op=recommend&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php _e('Featured','docs'); ?></a><?php else: ?><a href="./sections.php?op=norecommend&amp;sec=<?php echo $section['id']; ?>&amp;id=<?php echo $id; ?>"><?php _e('Normal','docs'); ?></a><?php endif; ?>
            </span>
        </td>
		<td><input type="text" name="orders[<?php echo $section['id']; ?>]" id="order-<?php echo $section['id']; ?>" size="1" value="<?php echo $section['order']; ?>" /></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
</form>
