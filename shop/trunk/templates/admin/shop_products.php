<h1 class="rmc_titles shop_titles"><span style="background-position: -96px 0;">&nbsp;</span><?php _e('Products','shop'); ?></h1>

<form action="products.php" method="post" name="frmProds" id="frm-prods">
<div class="shop_options">
    <?php echo isset($nav) ? $nav->render(false) : ''; ?>
    <select name="action" id="bulk-top">
        <option value="" selected="selected"><?php _e('Bulk actions','shop'); ?></option>
        <option value="delete"><?php _e('Delete','shop'); ?></option>
        <option value="unavailable"><?php _e('Out of stock','shop'); ?></option>
        <option value="available"><?php _e('In stock','shop'); ?></option>
    </select>
    <input type="button" class="button" value="<?php _e('Apply','shop'); ?>" />
    &nbsp; &nbsp; &nbsp;
    <?php _e('Search:','shop'); ?>
    <input type="text" size="20" name="bname" value="<?php echo $bname; ?>" />
    <input type="submit" value="<?php _e('Search','shop'); ?>" id="search" />
    &nbsp; &nbsp;
    <a href="products.php"><?php _e('Show all','shop'); ?></a>
</div>

<table class="outer" width="100%">
    <thead>
    <tr>
        <th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="50"><img src="../images/image.gif" alt="" /></th>
        <th><?php _e('Name','shop'); ?></th>
        <th><?php _e('Price', 'shop'); ?></th>
        <th><?php _e('Type', 'shop'); ?></th>
        <th><?php _e('In Stock', 'shop'); ?></th>
        <th><?php _e('Created', 'shop'); ?></th>
        <th><?php _e('Modified', 'shop'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="50"><img src="../images/image.gif" alt="" /></th>
        <th align="left"><?php _e('Name','shop'); ?></th>
        <th><?php _e('Price', 'shop'); ?></th>
        <th><?php _e('Type', 'shop'); ?></th>
        <th><?php _e('In Stock', 'shop'); ?></th>
        <th><?php _e('Created', 'shop'); ?></th>
        <th><?php _e('Modified', 'shop'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(empty($products)): ?>
    <tr class="even">
        <td colspan="8" align="center"><?php _e('There are not products created yet!','shop'); ?></td>
    </tr>
    <?php endif; ?>
    <?php foreach($products as $prod): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
        <td><input type="checkbox" name="ids[]" value="<?php echo $prod['id']; ?>" id="item-<?php echo $prod['id']; ?>" /></td>
        <td class="prdimg"><?php if($prod['image']!=''): ?><img src="<?php echo $prod['image']; ?>" alt="" /><?php endif; ?></td>
        <td align="left">
            <strong><?php echo $prod['name']; ?></strong>
            <span class="rmc_options">
                <a href="products.php?action=edit&amp;id=<?php echo $prod['id']; ?>&amp;page=<?php echo $page; ?>&amp;bname=<?php echo $bname; ?>"><?php echo __('Edit','shop'); ?></a> |
                <a href="#" onclick="select_option(<?php echo $prod['id']; ?>, 'delete', 'frm-prods');"><?php _e('Delete','shop'); ?></a> |
                <a href="products.php?action=images&amp;id=<?php echo $prod['id']; ?>&amp;page=<?php echo $page; ?>&amp;bname=<?php echo $bname; ?>"><?php _e('Images','shop'); ?></a>
            </span>
        </td>
        <td><?php echo $prod['price']; ?></td>
        <td><?php echo $prod['type']; ?></td>
        <td><img src="../images/<?php echo $prod['stock']?'ok.png':'outofstock.png'; ?>" alt="" /></td>
        <td><?php echo $prod['created']; ?></td>
        <td><?php echo $prod['modified']; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="shop_options">
    <?php echo isset($nav) ? $nav->render(false) : ''; ?>
    <select name="actionb" id="bulk-bottom">
        <option value="" selected="selected"><?php _e('Bulk actions','shop'); ?></option>
        <option value="delete"><?php _e('Delete','shop'); ?></option>
        <option value="unavailable"><?php _e('Out of stock','shop'); ?></option>
        <option value="available"><?php _e('In stock','shop'); ?></option>
    </select>
    <input type="button" class="button" value="<?php _e('Apply','shop'); ?>" />
</div>
<input name="page" type="hidden" value="<?php echo $page; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>