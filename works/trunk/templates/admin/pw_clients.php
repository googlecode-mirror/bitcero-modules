<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Customers','admin_works'); ?></h1>

<form name="frmClients" id="frm-customers" method="POST" action="clients.php">
<div class="pw_options">
    <?php echo $nav->render(false); ?>
    <select name="op" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-customers');" />
</div>


<table width="100%" cellspacing="0" class="outer">
	<tr>	
		<th width="20">
            <input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmClients','checkAll')" />
        </th>
		<th width="30"><?php _e('ID','admin_works'); ?></th>
		<th align="left"><?php _e('Name','admin_works'); ?></th>
        <th align="left"><?php _e('Information','admin_works'); ?></th>
		<th><?php _e('Business','admin_works'); ?></th>
		<th><?php _e('Type','admin_works'); ?></th>
	</tr>
    <?php if(empty($customers)): ?>
    <tr class="even">
        <td colspan="5" align="center"><?php _e('There are not customers registered on Professional Works yet!','admin_works'); ?></td>
    </tr>
    <?php endif; ?>
	<?php foreach($customers as $client): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $client['id']; ?>" id="item-<?php echo $client['id']; ?>" /></td>
		<td><strong><?php echo $client['id']; ?></strong></td>
		<td align="left">
            <strong><? echo $client['name']; ?></strong>
            <span class="rmc_options">
                <a href="./clients.php?op=edit&amp;id=<?php echo $client['id']; ?>&amp;pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>"><?php _e('Edit','admin_works'); ?></a>
                | <a href="javascript:;" onclick="select_option(<?php echo $client['id']; ?>,'delete', 'frm-customers');"><?php _e('Delete','admin_works'); ?></a>
            </span>
        </td>
        <td align="left"><?php echo $client['description']; ?></td>
		<td align="center"><?php echo $client['business']; ?></td>
		<td><?php echo $client['type']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<div class="pw_options">
    <?php $nav->display(); ?>
    <select name="op" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','admin_works'); ?>" />
</div>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
