<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo $work->title(); ?> : <?php _e('Work Images','admin_works'); ?></h1>

<form name="frmImgs" id="frm-images" method="POST" action="images.php">
<div class="pw_options">
    <select name="op" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-categos');" />
</div>
<table class="outer" width="100%" cellspacing="1" />
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','admin_works'); ?></th>
		<th width="60"><?php _e('Image','admin_works'); ?></th>
		<th align="left"><?php _e('Title','admin_works'); ?></th>
        <th align="left"><?php _e('Description','admin_works'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','admin_works'); ?></th>
        <th width="60"><?php _e('Image','admin_works'); ?></th>
        <th align="left"><?php _e('Title','admin_works'); ?></th>
        <th align="left"><?php _e('Description','admin_works'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($images)): ?>
    <tr class="head" align="center">
        <td colspan="5"><?php _e('There are not images for this work yet!','admin_works'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($images as $img): ?>
	<tr class="<{cycle values='even,odd'}>" align="center">
		<td><input type="checkbox" name="ids[]" value="<{$img.id}>" /></td>
		<td><strong><{$img.id}></strong></td>
		<td width="100"><img src="<{$xoops_url}>/uploads/works/ths/<{$img.image}>"  /></td>
		<td align="left" valign="top"><strong><{$img.title}></strong><br /><{$img.desc}></td>
		<td><a href="./images.php?op=edit&amp;id=<{$img.id}>&amp;work=<{$img.work}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_edit}></a> &bull; <a href="./images.php?op=delete&amp;ids=<{$img.id}>&amp;work=<{$img.work}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_delete}></a></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<input type="hidden" name="work" value="<?php echo $work->id(); ?>" />
<input type="hidden" name="pag" value="<?php echo $page; ?>" />
</form>
