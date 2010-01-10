<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Social Sites','admin_mywords'); ?></h1>

<div class="descriptions">
	<?php _e('Social Sites allows to publish directly, on these sites, links and content from MyWords posts.','admin_mywords'); ?>
	<?php _e('You can add new sites easily by configuring parameters for each site (eg. Twitter, Facebook, etc.), then your visitors can recommend posts to other users from these social networks.','armin_mywords'); ?></em>
</div>

<div class="form_options">
	<form name="form_new" id="form-new-bookmark" method="post" action="bookmarks.php">
	<h3 class="form_titles"><?php _e('Create Site','admin_mywords'); ?></h3>
	<label for="new-title">*<?php _e('Site title:','admin_mywords'); ?></label>
	<input type="text" name="title" id="new-title" value="" class="required" />
	<label for="new-alt">*<?php _e('Short description:','admin_mywords'); ?></label>
	<input type="text" name="alt" id="new-alt" value="" class="required" />
	<label for="new-url">*<?php _e('Formated URL:','admin_mywords'); ?></label>
	<input type="text" name="url" id="new-url" value="" class="required" />
	<span class="description"><?php _e('Please, note that the URL can contain parameters {TITLE}, {URL} and {DESC} that will be replaced with their respective values.','admin_mywords'); ?></span>
	<label for="new-icon"><?php _e('Icon:','admin_mywords'); ?></label>
	<div class="icons_sel" id="new-icon">
		<?php foreach($icons as $id => $icon): ?>
			<img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['name']; ?>" id="icon-<?php echo $id; ?>" title="<?php echo $icon['name']; ?>" />
		<?php endforeach; ?>
		<input type="hidden" name="new_icon" id="new-icon-h" value="" />
		<span class="description"><?php echo sprintf(__('You can create new icons by uploading files to %s folder.','admin_mywords'), XOOPS_ROOT_PATH.'/modules/mywords/images'); ?>
	</div>
	<input type="submit" value="<?php _e('Create Site','admin_mywords'); ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="action" value="new" />
	</form>
</div>
<div id="tables-list">
	<form name="frmListB" id="form-list-book" method="post" action="bookmarks.php">
	<table class="outer" cellspacing="0">
		<thead>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-book").toggleCheckboxes(":not(#checkall)");' /></th>
			<th>&nbsp;</th>
			<th align="left"><?php _e('Title','admin_mywords'); ?></th>
			<th align="left"><?php _e('Description','admin_mywords'); ?></th>
			<th><?php _e('URL','admin_mywords'); ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th width="20" align="center"><input type="checkbox" id="checkall" onclick='$("#form-list-book").toggleCheckboxes(":not(#checkall)");' /></th>
			<th>&nbsp;</th>
			<th align="left"><?php _e('Title','admin_mywords'); ?></th>
			<th align="left"><?php _e('Description','admin_mywords'); ?></th>
			<th><?php _e('URL','admin_mywords'); ?></th>
		</tr>
		</tfoot>
		<tbody>
		<?php if(count($bookmarks)<=0): ?>
		<tr class="even">
			<td colspan="5"><?php _e('There are not social sites registered yet!','admin_mywords'); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach($bookmarks as $book): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
			<td align="center"><input type="checkbox" name="books[]" id="book-<?php echo $book['id']; ?>" value="<?php echo $book['id']; ?>" /></td>
			<td align="center"><img src="../images/icons/<?php echo $book['icon']; ?>" alt="<?php echo $book['icon']; ?>" title="<?php echo $book['icon']; ?>" /></td>
			<td>
				<strong><?php echo $book['name']; ?></strong>
				<?php echo $book['active']?'':'['.__('Inactive','admin_mywords').']'; ?>
				<span class="mw_options">
                    <a href="bookmarks.php?id=<?php echo $book['id']; ?>&amp;action=edit"><?php _e('Edit','admin_mywords'); ?></a> |
                    <?php if($book['active']): ?>
                    <a href="javascript:;" onclick="goto_activate(<?php echo $book['id']; ?>,false);"><?php _e('Desactivar','admin_mywords'); ?></a> |
                    <?php else: ?>
                    <a href="javascript:;" onclick="goto_activate(<?php echo $book['id']; ?>,true);"><?php _e('Activar','admin_mywords'); ?></a> |
                    <?php endif; ?>
                    <a href="javascript:;" onclick="goto_delete(<?php echo $book['id']; ?>);"><?php _e('Delete','admin_mywords'); ?></a>
                </span>
			</td>
			<td><?php echo $book['desc']; ?></td>
			<td class="burl"><?php echo $book['url']; ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
		
	</table>
	</form>
</div>

<!-- 
<form name="frmSites" method="post" action="bookmarks.php" id="frmSites" />
<table class="outer" cellspacing="1" width="100%">
    <tr>
        <th colspan="6"><{$lang_title}></th>
    </tr>
    <tr class="head" align="center">
        <td width="20"><input type="checkbox" name="chekall" id="checkall" onclick="xoopsCheckAll('frmSites','checkall');" /></td>
        <td width="20">&nbsp;</td>
        <td align="left"><{$lang_name}></td>
        <td><{$lang_url}></td>
        <td><{$lang_active}></td>
        <td><{$lang_options}></td>
    </tr>
    <{foreach item=bm from=$bookmarks}>
        <tr align="center" class="<{cycle values="even,odd"}>">
            <td><input type="checkbox" name="bm[<{$bm.id}>]" id="bm[<{$bm.id}>]" value="<{$bm.id}>" /></td>
            <td>
                <{if $bm.icon!=''}>
                    <img src="../images/icons/<{$bm.icon}>" alt="" />
                <{else}>
                    &nbsp;
                <{/if}>
            </td>
            <td align="left">
                <strong><{$bm.name}></strong><br />
                <span style="font-size: 0.9em;"><{$bm.text}>
            </td>
            <td align="left"><{$bm.url}></td>
            <td><img src="../images/<{if $bm.active}>ok<{else}>no<{/if}>.png" alt="" /></td>
            <td>
                <a href="?op=edit&amp;id=<{$bm.id}>"><{$lang_edit}></a> &nbsp; | &nbsp; 
                <a href="?op=delete&amp;bm=<{$bm.id}>" onclick="return exmConfirmMsg('<{$lang_confdel}>\n\n<{$bm.name}>');"><{$lang_delete}></a>
            </td>
        </tr>
    <{/foreach}>
    <tr class="foot">
        <td align="right"><img src="../../system/images/root.gif" alt="" /></td>
        <td colspan="5">
            <input type="button" value="<{$lang_activate}>" class="formButtonBlue" onclick="$('op').value='activate'; submit();" />
            <input type="button" value="<{$lang_delete}>" class="formButtonRed" onclick="if(exmConfirmMsg('<{$lang_confdels}>')){ $('op').value='delete'; submit();}" />
            <input type="hidden" name="op" id="op" value="" />
        </td>
    </tr>
</table>
</form>
-->