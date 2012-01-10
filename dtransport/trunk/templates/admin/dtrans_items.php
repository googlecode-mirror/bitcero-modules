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
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_cat&amp;mode=<?php if($sort=='id_cat'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Category','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=secure&amp;mode=<?php if($sort=='secure'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Tipo','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=approved&amp;mode=<?php if($sort=='approved'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Approved','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=screens&amp;mode=<?php if($sort=='screens'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Screenshots','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=mark&amp;mode=<?php if($sort=='mark'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Featured','dtransport'); ?></a></th>
		<th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=daily&amp;mode=<?php if($sort=='daily'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Daily','dtransport'); ?></a></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frm1','checkAll')"/></th>
        <th width="20"><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_soft&amp;mode=<?php if($sort=='id_soft'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('ID','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=name&amp;mode=<?php if($sort=='name'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Name','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_cat&amp;mode=<?php if($sort=='id_cat'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Category','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=secure&amp;mode=<?php if($sort=='secure'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Tipo','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=approved&amp;mode=<?php if($sort=='approved'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Approved','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=screens&amp;mode=<?php if($sort=='screens'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Screenshots','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=mark&amp;mode=<?php if($sort=='mark'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Featured','dtransport'); ?></a></th>
        <th><a href="./items.php?pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;sort=daily&amp;mode=<?php if($sort=='daily'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>&amp;type=<?php echo $type; ?>"><?php _e('Daily','dtransport'); ?></a></th>
    </tr>
    </tfoot>
    <tbody>
	<?php foreach($items as $item): ?>
	<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center">
		<td><input type="checkbox" name="ids[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>" /></td>
		<td align="center"><strong><?php echo $item['id']; ?></strong></td>
		<td align="left"><{if !$type && $item.approved}>
			<a href="<{$item.link}>"><{$item.name}></a>
			<{else}>
				<{$item.name}>
			<{/if}>
			<span style="display:block; font-size: 0.9em; margin-top: 4px; color: #999;">
				<strong><{$lang_date}></strong> <{$item.date}> |
				<strong><{$lang_by}></strong> <{$item.uname}>
			</span>
		</td>
		<td><{$item.category}></td>
		<td align="center"><{if $item.secure}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png" border="0" /><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png" border="0" /><{/if}></td>
		<td align="center"><{if $item.approved}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png" border="0" /><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png" border="0" /><{/if}></td>
		<td align="center"><{$item.screens}></td>
		<td><{if $item.mark}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png" border="0" /><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png" border="0" /><{/if}></td>
		<td><{if $item.daily}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png" border="0" /><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png" border="0" /><{/if}></td>
		<td align="center">
		<a href="./items.php?op=edit&amp;id=<{$item.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>&amp;cat=<{$cat}>&amp;type=<{$type}>" title="<{$lang_edit}>"><img src="../images/itemedit.png" alt="<{$lang_edit}>" /></a>
		&nbsp;<a href="./items.php?op=delete&amp;id=<{$item.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>&amp;cat=<{$cat}>&amp;type=<{$type}>" title="<{$lang_del}>"><img src="../images/itemdelete.png" alt="<{$lang_del}>" /></a>
		<{if $type!=edit}>
		&nbsp;<a href="./screens.php?item=<{$item.id}>" title="<{$lang_screens}>"><img src="../images/screen16.png" alt="<{$lang_screens}>" /></a>
		&nbsp;<a href="./features.php?item=<{$item.id}>" title="<{$lang_features}>"><img src="../images/features16.png" alt="<{$lang_features}>" /></a>
		&nbsp;<a href="files.php?item=<{$item.id}>" title="<{$lang_files}>"><img src="../images/down16.png" alt="<{$lang_files}>" /></a>
		&nbsp;<a href="./logs.php?item=<{$item.id}>" title="<{$lang_logs}>"><img src="../images/logs16.png" alt="<{$lang_logs}>" /></a>
		<{/if}>		
		</td>
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

<{$token}>
<input type="hidden" name="op" />
<input type="hidden" name="pag" value="<{$pag}>"/>
<input type="hidden" name="limit" value="<{$limit}>"/>
<input type="hidden" name="type" value="<{$type}>"/>
</form>
