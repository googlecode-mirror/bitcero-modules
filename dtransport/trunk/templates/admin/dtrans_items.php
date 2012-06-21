<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo $loc; ?></h1>

<form name="frmItems" id="frm-items" method="POST" action="items.php">
<div class="dt_table">
    <div class="dt_row">
        <div class="dt_cell">
            <?php _e('Search download:','dtransport'); ?>
            <input type="text" name="search" value="<?php echo $search; ?>" size="15" />
            <input type="submit" value="<?php _e('Search Now!','dtransport'); ?>" /> 
        </div>
        <div class="dt_cell">
            <?php _e('Category:','dtransport'); ?>
            <select name="cat" onchange="submit()">
                <option value="0"><?php _e('Select...','dtransport'); ?></option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"<?php if($cat['id']==$catid): ?> selected="selected"<?php endif; ?>><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="dt_cell">
            <?php echo $navpage; ?>
        </div>
    </div>
</div>

<table width="100%" class="outer" cellspacing="1">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frm1','checkAll')"/></th>
		<th width="20"><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_soft&amp;mode=<?php if($sort=='id_soft'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('ID','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=name&amp;mode=<?php if($sort=='name'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Name','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=secure&amp;mode=<?php if($sort=='secure'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Tipo','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=approved&amp;mode=<?php if($sort=='approved'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Approved','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=screens&amp;mode=<?php if($sort=='screens'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>" title="<?php _e('Screenshots','dtransport'); ?>"><img src="../images/shots.png" alt="<?php _e('Screenshots','dtransport'); ?>" /></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=mark&amp;mode=<?php if($sort=='mark'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Featured','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=daily&amp;mode=<?php if($sort=='daily'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Daily','dtransport'); ?></a></th>
        <th colspan="7"><?php _e('Options','dtransport'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frm1','checkAll')"/></th>
        <th width="20"><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_soft&amp;mode=<?php if($sort=='id_soft'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('ID','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=name&amp;mode=<?php if($sort=='name'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Name','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=secure&amp;mode=<?php if($sort=='secure'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Tipo','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=approved&amp;mode=<?php if($sort=='approved'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Approved','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=screens&amp;mode=<?php if($sort=='screens'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>" title="<?php _e('Screenshots','dtransport'); ?>"><img src="../images/shots.png" alt="<?php _e('Screenshots','dtransport'); ?>" /></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=mark&amp;mode=<?php if($sort=='mark'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Featured','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=daily&amp;mode=<?php if($sort=='daily'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Daily','dtransport'); ?></a></th>
        <th colspan="7"><?php _e('Options','dtransport'); ?></th>
    </tr>
    </tfoot>
    <tbody>
	<?php foreach($items as $item): ?>
	<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>" /></td>
		<td align="center"><strong><?php echo $item['id']; ?></strong></td>
		<td align="left">
            <?php if(!$type && $item['approved']): ?>
			    <a href="<?php echo $item['link']; ?>"><?php echo $item['name']; ?></a>
			<?php else: ?>
				<?php echo $item['name']; ?>
			<?php endif; ?>
			<span style="display:block; font-size: 0.9em; margin-top: 4px; color: #999;">
				<?php echo sprintf(__('Last modification on %s by %s','dtransport'), $item['modified'], $item['uname']); ?>
			</span>
		</td>
		<td align="center"><?php echo $item['secure']?__('Secure','dtransport'):__('Normal','dtransport'); ?></td>
		<td align="center"><img src="../images/<?php echo $item['approved']?'checked.png':'unchecked.png'; ?>" border="0" /></td>
		<td align="center"><?php echo $item['screens']; ?></td>
		<td><img src="../images/<?php echo $item['featured']?'checked.png':'unchecked.png'; ?>" border="0" /></td>
		<td><img src="../images/<?php echo $item['daily']?'checked.png':'unchecked.png'; ?>" border="0" /></td>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;">
            <a href="./items.php?action=edit&amp;id=<?php echo $item['id']; ?>&amp;pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;cat=<?php echo $cat; ?>&amp;type=<?php echo $type; ?>" style="background: url(../images/edit.png) no-repeat center;" title="<?php _e('Edit','dtransport'); ?>"><span><?php _e('Edit','dtransport'); ?></span></a>
        </td>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;">
            <a href="./items.php?action=delete&amp;id=<?php echo $item['id']; ?>&amp;pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;cat=<?php echo $cat; ?>&amp;type=<?php echo $type; ?>" style="background: url(../images/delete.png) no-repeat center;" title="<?php _e('Delete','dtransport'); ?>"><span><?php _e('Delete','dtransport'); ?></span></a>
        </td>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;">
            <a href="./items.php?action=<?php echo $item['secure']?'unlock':'lock'; ?>&amp;id=<?php echo $item['id']; ?>&amp;pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;cat=<?php echo $cat; ?>&amp;type=<?php echo $type; ?>" style="background: url(../images/<?php echo $item['secure']?'unlock.png':'lock.png'; ?>) no-repeat center;" title="<?php $item['secure'] ? _e('Unlock','dtransport') : _e('Lock','dtransport'); ?>"><span><?php $item['secure'] ? _e('Unlock','dtransport') : _e('Lock','dtransport'); ?></span></a>
        </td>
        <?php if($type!='edit'): ?>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;"><a href="./screens.php?item=<?php echo $item['id']; ?>" style="background: url(../images/images.png) no-repeat center;" title="<?php _e('Images','dtransport'); ?>"><span><?php _e('Images','dtransport'); ?></span></a></td>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;"><a href="./features.php?item=<?php echo $item['id']; ?>" style="background: url(../images/features.png) no-repeat center;" title="<?php _e('Features','dtransport'); ?>"><span><?php _e('Features','dtransport'); ?></span></a></td>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;"><a href="files.php?item=<?php echo $item['id']; ?>" style="background: url(../images/files.png) no-repeat center;" title="<?php _e('Files','dtransport'); ?>"><span><?php _e('Files','dtransport'); ?></span></a></td>
        <td class="dt_item_opts" style="padding: 2px; vertical-align: middle; background: #ebebeb;"><a href="./logs.php?item=<?php echo $item['id']; ?>" style="background: url(../images/logs.png) no-repeat center;" title="<?php _e('Logs','dtransport'); ?>"><span><?php _e('Logs','dtransport'); ?></span></a></td>
        <?php endif; ?>
	</tr>
	<?php endforeach; ?>
    </tbody>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" border="0" /></td>
		<td colspan="9">
			<input type="submit" value="<{$lang_app}>" class="formButtonBlue" onclick="document.forms['frm1'].op.value='approve';" />
			<input type="submit" value="<{$lang_noapp}>" class="formButtonRed" onclick="document.forms['frm1'].op.value='noapprove';" />
			<input type="submit" value="<{$lang_downmark}>" class="formButtonGreen" onclick="document.forms['frm1'].op.value='mark';" />
			<input type="submit" value="<{$lang_downdaily}>" class="formButton" onclick="document.forms['frm1'].op.value='daily';" />
		</td>
	</tr>
</table>

<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="op" />
<input type="hidden" name="pag" value="<{$pag}>"/>
<input type="hidden" name="limit" value="<{$limit}>"/>
<input type="hidden" name="type" value="<{$type}>"/>
</form>
