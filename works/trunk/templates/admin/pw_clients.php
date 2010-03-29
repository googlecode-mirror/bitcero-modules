<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Customers','admin_works'); ?></h1>

<form name="frmClients" method="POST" action="clients.php">
<div class="pw_options">
    <select name="op" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
    </select>
</div>


<table width="100%" cellspacing="0" class="outer">
	<tr>	
		<th width="20">
            <input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmClients','checkAll')" />
        </th>
		<th width="30"><?php _e('ID','admin_works'); ?></th>
		<th><?php _e('Name','admin_works'); ?></th>
		<th><?php _e('Business','admin_works'); ?></th>
		<th><?php _e('Type','admin_works'); ?></th>
	</tr>
	<?php foreach($clients as $client): ?>
	<tr class="<{cycle values='even,odd'}>" align="center">
		<td><input type="checkbox" name="ids[]" value="<{$client.id}>" /></td>
		<td><strong><{$client.id}></strong></td>
		<td align="left">
            <{$client.name}>
            <span class="rmc_options">
                <a href="./clients.php?op=edit&amp;id=<{$client.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_edit}></a> &bull; <a href="./clients.php?op=delete&amp;ids=<{$client.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>"><{$lang_delete}></a>
            </span>
        </td>
		<td align="left"><{$client.business}></td>
		<td><{$client.type}></td>
	</tr>
	<?php endforeach; ?>
</table>
<input type="hidden" name="op" />
<input type="hidden" name="pag" value="<{$pag}>" />
<input type="hidden" name="limit" value="<{$limit}>" />
</form>
