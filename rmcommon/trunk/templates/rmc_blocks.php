<h1 class="rmc_titles"><?php _e('Blocks Administration','rmcommon'); ?></h1>

<div class="rmc_blocks_options">
    <label>
        <strong><?php _e('Module','rmcommon'); ?></strong><br />
        <select name="module">
            <option value=""><?php _e('All modules','rmcommon'); ?></option>
        <?php foreach($modules as $mod): ?>
            <option value="<?php echo $mod['mid']; ?>"><?php echo $mod['name']; ?></option>
        <?php endforeach; ?>
        </select>
    </label>
    <label>
        <strong><?php _e('Position','rmcommon'); ?></strong><br />
        <select name="position">
            <option value=""><?php _e('All positions','rmcommon'); ?></option>
            <?php foreach($positions as $pos): ?>
            <option value="<?php echo $pos['id']; ?>"><?php echo $pos['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    
    <a href="#" id="newban" class="rmc_menus"><?php _e('Add New Block','rmcommon'); ?></a>
    <div id="megamenu1" class="megamenu">
        <div class="menucont">
        <?php $i = 0; ?>
        <?php foreach($blocks as $dir => $block): ?>
            <?php if(empty($block['blocks'])) continue; ?>
            <?php $i++; ?>
            <div class="column">
                <h3><?php echo $block['name']; ?></h3>
                <ul>
                <?php foreach($block['blocks'] as $id => $bk): ?>
                    <li><a href="#" id="block-<?php echo $dir; ?>-<?php echo md5(serialize($bk)); ?>"><?php echo $bk['name']; ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php if($i>=3): $i=0; ?><br style="clear: left;" /><?php endif; ?>
        <?php endforeach; ?>
        </div>   
    </div>
    <a href="#" id="newpos" class="rmc_menus"><?php _e('Add New Position','rmcommon'); ?></a>
</div>

<div id="form-pos" class="bkbk_forms">
        <div class="formposcontainer">
        <h3>Add Position</h3>
        <form name="frmaddpos" id="frm-add-pos" />
        <label><?php _e('Name','rmcommon'); ?></label>
        <input type="text" name="posname" value="" />
        <span class="desc"><?php _e('Input a name to identify this position (<em>eg. Left blocks</em>)','rmcommon'); ?></span>
        <label><?php _e('Tag Name','rmcommon'); ?></label>
        <input type="text" name="postag" value="" />
        <span class="desc"><?php _e('Specify a name for the smarty tag to use in templates (eg. left_blocks). This tag will be used as Smarty tag (eg. &lt;{$left_blocks}&gt).','rmcommon'); ?></span>
        <input type="button" name="bk_add_pos" id="add-position" value="<?php _e('Add Position','rmcommon'); ?>" />
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </form>
        <span class="other_options"><a href="#" id="exspos"><?php _e('Existing positions','docs'); ?> <span>&#8711;</span></a></span>
    <div id="existing-positions">
        <?php foreach($positions as $pos): ?>
        <span><?php echo $pos['name']; ?> <a href="#" class="edit-<?php echo $pos['id']; ?>"><?php _e('edit','rmcommon'); ?></a></span>
        <?php endforeach; ?>
    </div>
        </div>
</div>
<form name="frmblocks" id="frm-blocks" method="post" action="blocks.php">
<div style="overflow: hidden;">
<table class="outer" border="0">
    <thead>
    <tr>
        <th width="30"><input type="checkbox" id="checkall" /></th>
        <th align="left"><?php _e('Blocks','rmcommon'); ?></th>
        <th width="100"><?php _e('Module','rmcommon'); ?></th>
    </tr>
    </thead>
    <?php if(empty($used_blocks)): ?>
    <tr class="even" align="center">
        <td colspan="3"><?php _e('There are not blocks configured with this options.','rmcommon'); ?></td>
    </tr>
    <?php endif; ?>
    <?php foreach($used_blocks as $block): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>">
        <td><input type="checkbox" name="ids[]" id="item-<?php echo $block['id']; ?>" /></td>
        <td>
            <strong><?php echo $block['title']; ?></strong>
            <span class="description"><?php echo $block['description']; ?></span>
        </td>
        <td><?php echo $block['module']['name']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>