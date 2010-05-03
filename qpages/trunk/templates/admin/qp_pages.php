<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Pages','qpages'); ?></h1>
<script type="text/javascript">
<!--
	var qp_select_message = '<?php _e('Select at least a page to apply this action!','qpages'); ?>';
	var qp_message = '<?php _e('Do you really wisth to delete selected pages?','qpages'); ?>';
-->
</script>
<form name="frmSearch" method="get" action="pages.php" style="margin: 0;">
<div class="qp_options top_options">
    <?php _e('Search:','qpages'); ?>
    <input type="text" name="keyw" value="<?php echo $keyw ?>" size="30" /> &#160; 
    <?php _e('Category','qpages'); ?>
    <select name="cat" onchange="submit();">
        <?php foreach($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"<?php if($category['id']==$catego): ?> selected="selected"<?php endif; ?>><?php echo $category['nombre']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="sbt" value="<?php _e('Apply','qpages'); ?>" class="formButton" />
</div>
</form>

<form name="modPages" id="frm-pages" method="post" action="pages.php">
<div class="qp_options">
	<?php $nav->display(false); ?>
	<select name="op" id="bulk-top">
		<option value="" selected="selected"><?php _e('Bulk actions...','qpages'); ?></option>
		<option value="private"><?php _e('Set as draft','qpages'); ?></option>
		<option value="public"><?php _e('Publish','qpages'); ?></option>
		<option value="linked"><?php _e('Set as linked','qpages'); ?></option>
		<option value="delete"><?php _e('Delete','qpages'); ?></option>
	</select>
	<input type="button" value="<?php _e('Apply','qpages'); ?>" onclick="before_submit('frm-pages');" />
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="outer">
    <thead>
    <tr class="head" align="center">
  	    <th width="20"><input<?php if(empty($categories)): ?> disabled="disabled"<?php endif; ?> type="checkbox" id="checkall" onclick='$("#frm-pages").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="20"><?php _e('ID','qpages'); ?></th>
        <th width="20">&nbsp;</th>
        <th align="left"><?php _e('Title','qpages'); ?></th>
        <th><?php _e('Modified','qpages'); ?></th>
        <th><?php _e('Views','qpages'); ?></th>
        <th><?php _e('Visibility','qpages'); ?></th>
        <th><?php _e('Diplay order','qpages'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
  	    <th width="20"><input<?php if(empty($categories)): ?> disabled="disabled"<?php endif; ?> type="checkbox" id="checkall2" onclick='$("#frm-pages").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="20"><?php _e('ID','qpages'); ?></th>
        <th width="20">&nbsp;</th>
        <th align="left"><?php _e('Title','qpages'); ?></th>
        <th><?php _e('Modified','qpages'); ?></th>
        <th><?php _e('Views','qpages'); ?></th>
        <th><?php _e('Visibility','qpages'); ?></th>
        <th><?php _e('Diplay order','qpages'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($pages)): ?>
        <tr class="even">
            <td align="center" colspan="8"><?php _e('There are not pages registered yet!','qpages'); ?></td>
        </tr>
    <?php endif; ?>
    <?php foreach($pages as $page): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
  	    <td><input type="checkbox" name="ids[]" id="item-<?php echo $page['id']; ?>" value="<?php echo $page['id']; ?>" /></td>
        <td><strong><?php echo $page['id']; ?></strong></td>
        <td>
    	    <img src="../images/page<?php if($page['type']): ?>_go<?php endif; ?>.png" alt="" />
        </td>
        <td align="left">
        	<a href="<?php echo $page['link']; ?>"><strong><?php echo $page['titulo']; ?></strong></a>
        	<span class="rmc_options">
        		<a href="pages.php?op=edit<?php if($page['type']): ?>link<?php endif; ?>&amp;id=<?php echo $page['id']; ?>&amp;cat=<?php echo $cat; ?>" title="<?php _e('Edit','qpages'); ?>"><?php _e('Edit','qpages'); ?></a> &bull;
	    		<a href="javascript:;" title="<?php _e('Delete','qpages'); ?>" onclick="select_option(<?php echo $page['id']; ?>,'delete','frm-pages');"><?php _e('Delete','qpages'); ?></a>
        	</span>
        </td>
        <td><?php echo $page['modificada']; ?></td>
        <td align="center"><?php echo $page['lecturas']; ?></td>
        <td><?php echo $page['estado']; ?></td>
        <td><input type="text" size="5" value="<?php echo $page['order']; ?>" name="porder[<?php echo $page['id']; ?>]" style="text-align: center;" /></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
	
</table>
<div class="qp_options">
	<?php $nav->display(false); ?>
	<select name="opb" id="bulk-bottom">
		<option value="" selected="selected"><?php _e('Bulk actions...','qpages'); ?></option>
		<option value="private"><?php _e('Set as draft','qpages'); ?></option>
		<option value="public"><?php _e('Publish','qpages'); ?></option>
		<option value="linked"><?php _e('Set as linked','qpages'); ?></option>
		<option value="delete"><?php _e('Delete','qpages'); ?></option>
	</select>
	<input type="button" value="<?php _e('Apply','qpages'); ?>" onclick="before_submit('frm-pages');" />
</div>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="cat" value="<?php echo $cat; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
