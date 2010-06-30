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
		<th><?php _e('Title','docs'); ?></th>
		<th><?php _e('Date','docs'); ?></th>
		<th><?php _e('Attributes','docs'); ?></th>
	</tr>
	<?php foreach($resources as $res): ?>
		<tr class="<{cycle values="even,odd"}>" align="center">
			<td><input type="checkbox" name="resources[]" value="<{$resource.id}>" /></td>
			<td><{$resource.id}></td>
			<td align="left"><a href="./sections.php?id=<{$resource.id}>" ><{$resource.title}></a> (<{$resource.owname}>)</td>
			<td><{$resource.created}></td>
			<td><{if $resource.approved}><img src="../images/ok.png" border="0" /><{else}><img src="../images/no.png" border="0" /><{/if}></td>
			<td><{if $resource.public}><img src="../images/ok.png" border="0" /><{else}><img src="../images/no.png" border="0" /><{/if}></td>
			<td><{if $resource.quick}><img src="../images/ok.png" border="0" /><{else}><img src="../images/no.png" border="0" /><{/if}></td>
			<td align="center"><a href="./resources.php?op=edit&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>" ><{$lang_edit}></a> &bull; <a href="./resources.php?op=del&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>" ><{$lang_delete}></a> &bull; <a href="./sections.php?id=<{$resource.id}>"><{$lang_sections}></a> &bull; <{if !$resource.featured }><a href="./resources.php?op=recommend&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>"><{$lang_recommend}><{else}><a href="./resources.php?op=norecommend&amp;id=<{$resource.id}>&amp;limit=<{$limit}>&amp;pag=<{$pag}>"><{$lang_norecommend}><{/if}></a></td>
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
