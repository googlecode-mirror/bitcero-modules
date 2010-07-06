<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Available Resources','docs'); ?></h1>

<div class="rd_loptions">
    <?php $nav->display(false); ?>
    <select name="op" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','docs'); ?></option>
        <option value="approve"><?php _e('Approve','docs'); ?></option>
        <option value="unapprove"><?php _e('Unapprove','docs'); ?></option>
        <option value="public"><?php _e('Set as public','docs'); ?></option>
        <option value="draft"><?php _e('Set as draft','docs'); ?></option>
        <option value="qindex"><?php _e('Enable quick index','docs'); ?></option>
        <option value="noqindex"><?php _e('Disable quick index','docs'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-resources');" />
</div>
<form name="frmres" method="POST" action="resources.php" id="frm-resources">
<table class="outer" width="100%" cellspacing="1"> 
	<tr>
	    <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-resources").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','docs'); ?></th>
		<th align="left"><?php _e('Title','docs'); ?></th>
        <th align="left"><?php _e('Description','docs'); ?></th>
		<th><?php _e('Date','docs'); ?></th>
		<th><?php _e('Attributes','docs'); ?></th>
	</tr>
	<?php foreach($resources as $res): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
			<td><input type="checkbox" name="resources[]" value="<?php echo $res['id']; ?>" /></td>
			<td><strong><?php echo $res['id']; ?></strong></td>
			<td align="left">
                <a href="./sections.php?id=<?php echo $res['id']; ?>" ><?php echo $res['title']; ?></a> (<?php echo $res['owname']; ?>)
                <span class="rmc_options">
                    <a href="./resources.php?op=edit&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>" ><?php _e('Edit','docs'); ?></a>
                    | <a href="./resources.php?op=del&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>" ><?php _e('Delete','docs'); ?></a>
                    | <a href="./sections.php?id=<{$resource.id}>"><?php _e('Sections','docs'); ?></a>
                    | <?php if($resource['featured']): ?><a href="./resources.php?op=recommend&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>"><?php _e('Featured','docs'); ?><?php else: ?><a href="./resources.php?op=norecommend&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>"><?php _e('Not featured','docs'); ?><?php endif; ?></a>
                </span>
            </td>
            <td align="left"><?php echo $res['description']; ?></td>
			<td><?php echo $res['created']; ?></td>
			<td>
                <img src="../images/<?php echo $res['approved'] ? 'approved.png' : 'noapproved.png'; ?>" border="0" />
			    <img src="../images/<?php echo $res['public'] ? 'public.png' : 'draft.png'; ?>" border="0" />
			    <img src="../images/<?php echo $res['quick']; ?>" border="0" /></td>
		</tr>
	<?php endforeach; ?>
</table>
<div class="rd_loptions">
    <?php $nav->display(false); ?>
    <select name="opb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','docs'); ?></option>
        <option value="approve"><?php _e('Approve','docs'); ?></option>
        <option value="unapprove"><?php _e('Unapprove','docs'); ?></option>
        <option value="public"><?php _e('Set as public','docs'); ?></option>
        <option value="draft"><?php _e('Set as draft','docs'); ?></option>
        <option value="qindex"><?php _e('Enable quick index','docs'); ?></option>
        <option value="noqindex"><?php _e('Disable quick index','docs'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-resources');" />
</div>
<{$token}>
<input type="hidden" name="op" value="" />
<input type="hidden" name="limit" value="<{$limit}>" />
<input type="hidden" name="pag" value="<{$pag}>" />
</form>
