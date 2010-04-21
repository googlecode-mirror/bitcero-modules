<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Works','admin_works'); ?></h1>
<form name="frmWorks" id="frm-works" method="POST" action="works.php">
<div class="pw_options">
    <?php $nav->display(false); ?>
    <select name="op" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
        <option value="update"><?php _e('Save changes','admin_works'); ?></option>
        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
        <option value="active"><?php _e('Enable categories','admin_works'); ?></option>
        <option value="desactive"><?php _e('Disable categories','admin_works'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-works');" />
</div>
<table class="outer" cellspacing="0" widht="100%">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-works").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><?php _e('ID','admin_works'); ?></th>
        <th><?php _e('Name','admin_works'); ?></th>
        <th><?php _e('Category','admin_works'); ?></th>
        <th><?php _e('Customer','admin_works'); ?></th>
        <th><?php _e('Start date','admin_works'); ?></th>
        <th><?php _e('Featured','admin_works'); ?></th>
        <th><?php _e('Public','admin_works'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkallb" onclick='$("#frm-works").toggleCheckboxes(":not(#checkallb)");' /></th>
        <th width="30"><?php _e('ID','admin_works'); ?></th>
        <th><?php _e('Name','admin_works'); ?></th>
        <th><?php _e('Category','admin_works'); ?></th>
        <th><?php _e('Customer','admin_works'); ?></th>
        <th><?php _e('Start date','admin_works'); ?></th>
        <th><?php _e('Featured','admin_works'); ?></th>
        <th><?php _e('Public','admin_works'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($works)): ?>
    <tr class="even">
        <td colspan="8" align="center"><?php _e('There are not works registered yet!','admin_works'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($works as $work): ?>
	<tr class="<{cycle values='even,odd'}>" align="center">
		<td><input type="checkbox" name="ids[]" value="<{$work.id}>"</td>
		<td><strong><{$work.id}></strong></td>
		<td align="left">
            <{$work.title}>
            <span class="rmc_options">
            <a href="./works.php?op=edit&amp;id=<{$work.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_edit}></a> &bull; <a href="./works.php?op=delete&amp;ids=<{$work.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_delete}></a> &bull; <a href="./images.php?work=<{$work.id}>"><{$lang_images}></a>
            </span>
        </td>
		<td align="left"><{$work.catego}></td>
		<td align="left"><{$work.client}></td>
		<td><{$work.start}></td>
		<td><{if $work.mark}><img src="<{$xoops_url}>/modules/works/images/ok.png" /><{else}><img src="<{$xoops_url}>/modules/works/images/no.png" /><{/if}></td>
		<td><{if $work.public}><img src="<{$xoops_url}>/modules/works/images/ok.png" /><{else}><img src="<{$xoops_url}>/modules/works/images/no.png" /><{/if}></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" /></td>	
		<td colspan="8"><input type="submit" class="formButton" value="<{$lang_delete}>" onclick="document.forms['frmWorks'].op.value='delete';" />
		<input type="submit" class="formButtonBlue" value="<{$lang_pub}>" onclick="document.forms['frmWorks'].op.value='public';" />
		<input type="submit" class="formButtonRed" value="<{$lang_nopub}>" onclick="document.forms['frmWorks'].op.value='nopublic';" />
		<input type="submit" class="formButtonGreen" value="<{$lang_mk}>" onclick="document.forms['frmWorks'].op.value='mark';" />
		<input type="submit" class="formButtonGold" value="<{$lang_nomark}>" onclick="document.forms['frmWorks'].op.value='nomark';" />
		</td>
			
	</tr>
</table>
<div class="pw_options">
    <?php $nav->display(false); ?>
    <select name="opb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
        <option value="update"><?php _e('Save changes','admin_works'); ?></option>
        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
        <option value="active"><?php _e('Enable categories','admin_works'); ?></option>
        <option value="desactive"><?php _e('Disable categories','admin_works'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-works');" />
</div>
<input type="hidden" name="pag" value="<?php echo $page ?>" />
</form>
