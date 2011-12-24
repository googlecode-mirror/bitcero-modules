<h1 class="rmc_titles"><?php _e('Blocks Administration','rmcommon'); ?></h1>
<?php $from = rmc_server_var($_REQUEST,'from', '')=='positions'?true:false; ?>
<div class="rmc_blocks_options">
    <div id="blocks-modpos"<?php echo $from ? ' style="display: none;"' : ''; ?>>
        <form name="frmModpos" method="get" action="blocks.php">
            <select name="mid" onchange="submit();">
                <option value=""<?php echo $mid==0?' selected="selected"' : ''; ?>><?php _e('All modules','rmcommon'); ?></option>
            <?php foreach($modules as $mod): ?>
                <option value="<?php echo $mod['mid']; ?>"<?php echo $mid==$mod['mid']?' selected="selected"' : ''; ?>><?php echo $mod['name']; ?></option>
            <?php endforeach; ?>
            </select>
            <select name="pos" onchange="submit();">
                <option value="0"<?php echo $pid==0?' selected="selected"':''; ?>><?php _e('All positions','rmcommon'); ?></option>
                <?php foreach($positions as $pos): ?>
                <option value="<?php echo $pos['id']; ?>"<?php echo $pid==$pos['id']?' selected="selected"':''; ?>><?php echo $pos['name']; ?></option>
                <?php endforeach; ?>
            </select>
            
            <select name="visible" onchange="submit();">
                <option value="-1"<?php echo $visible==-1?' selected="selected"':''; ?>><?php _e('All blocks...','rmcommon'); ?></option>
                <option value="0"<?php echo $visible==0?' selected="selected"':''; ?>><?php _e('Hidden blocks...','rmcommon'); ?></option>
                <option value="1"<?php echo $visible==1?' selected="selected"':''; ?>><?php _e('Visible blocks...','rmcommon'); ?></option>
            </select>
        </form>
    </div>
    
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
                    <li><a href="#" id="block-<?php echo $dir; ?>-<?php echo $bk['id']; ?>"><?php echo $bk['name']; ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php if($i>=3): $i=0; ?><br style="clear: left;" /><?php endif; ?>
        <?php endforeach; ?>
        </div>   
    </div>
    <a href="#" id="newpos" class="rmc_menus"><?php _e('Positions','rmcommon'); ?></a>
</div>
<div id="bk-messages" style="display: none;">
    <span class="msg-close"></span>
    <span class="msg"></span>
</div>
<div id="form-pos" class="bkbk_forms"<?php echo $from ? ' style="display: block;"' : ''; ?>>
        <div class="formposcontainer">
        <h3>Add Position</h3>
        <form name="frmaddpos" id="frm-add-pos" method="post" action="blocks.php" />
        <label><?php _e('Name','rmcommon'); ?></label>
        <input type="text" name="posname" value="" />
        <span class="desc"><?php _e('Input a name to identify this position (<em>eg. Left blocks</em>)','rmcommon'); ?></span>
        <label><?php _e('Tag Name','rmcommon'); ?></label>
        <input type="text" name="postag" value="" />
        <span class="desc"><?php _e('Specify a name for the smarty tag to use in templates (eg. left_blocks). This tag will be used as Smarty tag (eg. &lt;{$left_blocks}&gt).','rmcommon'); ?></span>
        <input type="submit" name="bk_add_pos" id="add-position" value="<?php _e('Add Position','rmcommon'); ?>" />
        <input type="hidden" name="action" value="save_position" />
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </form>
        <h4><?php _e('How to implement blocks','rmcommon'); ?></h4>        
        <div class="code">
            <code>&lt;{foreach item="block" from=$xoBlocks.<em>tag</em>}&gt;<br 7>
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;{$block.title}&gt;<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&lt;{$block.content}&gt;<br />
            &lt;{/foreach}&gt;</code>
        </div>
        </div>
</div>

<div style="overflow: hidden;<?php echo $from ? ' display: none;' : ''; ?>" id="blocks-list">
    <form name="frmblocks" id="frm-blocks" method="post" action="blocks.php">

    <div class="rmc_bulkactions">
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','rmcommon'); ?></option>
            <option value="visible"><?php _e('Visible','rmcommon'); ?></option>
            <option value="hidden"><?php _e('Hidden','rmcommon'); ?></option>
            <option value="delete"><?php _e('Delete','rmcommon'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-blocks');" />
    </div>
    
<table class="outer" border="0" id="table-blocks">
    <thead>
    <tr>
        <th width="30"><input type="checkbox" id="checkall" onclick="$('#frm-blocks').toggleCheckboxes(':not(#checkall)');" /></th>
        <th align="left"><?php _e('Block','rmcommon'); ?></th>
        <th width="100"><?php _e('Module','rmcommon'); ?></th>
        <th align="center"><?php _e('Position','rmcommon'); ?></th>
        <th align="center"><?php _e('Active','rmcommon'); ?></th>
        <th align="center" width="20"><?php _e('Order','rmcommon'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th width="30"><input type="checkbox" id="checkallb" onclick="$('#frm-blocks').toggleCheckboxes(':not(#checkallb)');" /></th>
        <th align="left"><?php _e('Block','rmcommon'); ?></th>
        <th><?php _e('Module','rmcommon'); ?></th>
        <th align="center"><?php _e('Position','rmcommon'); ?></th>
        <th align="center"><?php _e('Active','rmcommon'); ?></th>
        <th align="center" width="20"><?php _e('Order','rmcommon'); ?></th>
    </tr>
    </tfoot>
    <?php if(empty($used_blocks)): ?>
    <tr class="even" align="center" id="tr-empty">
        <td colspan="5"><?php _e('There are not blocks configured with this options.','rmcommon'); ?></td>
    </tr>
    <?php endif; ?>
    <?php foreach($used_blocks as $block): ?>
    <tr valign="top" class="<?php echo tpl_cycle("even,odd"); ?>" id="tr-<?php echo $block['id']; ?>">
        <td align="center"><input type="checkbox" name="ids[]" id="item-<?php echo $block['id']; ?>" value="<?php echo $block['id']; ?>" /></td>
        <td>
            <strong><?php echo $block['title']; ?></strong>
            <span class="description"><?php echo $block['description']; ?></span>
            <span class="rmc_options">
                <a class="bk_edit" href="#" id="edit-<?php echo $block['id']; ?>"><?php _e('Settings','rmcommon'); ?></a> |
                <a href="#" onclick="select_option(<?php echo $block['id']; ?>,'delete','frm-blocks');"><?php _e('Delete','rmcommon'); ?></a>
            </span>
        </td>
        <td align="center"><?php echo $block['module']['name']; ?></td>
        <td align="center"><?php echo $block['canvas']['name']; ?></td>
        <td align="center"><img src="images/<?php echo $block['visible']?'done.png':'closeb.png'; ?>" alt="" /></td>
        <td align="center"><?php echo $block['weight']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
    <div class="rmc_bulkactions">
        <select name="actionb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','rmcommon'); ?></option>
            <option value="visible"><?php _e('Visible','rmcommon'); ?></option>
            <option value="hidden"><?php _e('Hidden','rmcommon'); ?></option>
            <option value="delete"><?php _e('Delete','rmcommon'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-blocks');" />
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    </form>
</div>

<!-- Positions -->
<div id="blocks-positions" style="overflow: hidden;<?php echo $from ? ' display: block;' : 'display: none;'; ?>">
    <form name="formPos" id="frm-positions" method="post" action="blocks.php">
        <div class="rmc_bulkactions">
            <select name="action" id="bulk-topp">
                <option value=""><?php _e('Bulk actions...','rmcommon'); ?></option>
                <option value="visiblepos"><?php _e('Visible','rmcommon'); ?></option>
                <option value="hiddenpos"><?php _e('Hidden','rmcommon'); ?></option>
                <option value="deletepos"><?php _e('Delete','rmcommon'); ?></option>
            </select>
            <input type="button" id="the-op-topp" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-positions');" />
        </div>
        <table class="outer" border="0" id="table-positions">
            <thead>
            <tr>
                <th width="30"><input type="checkbox" id="checkallp" onclick="$('#frm-positions').toggleCheckboxes(':not(#checkallp)');" /></th>
                <th width="30" align="left"><?php _e('ID','rmcommon'); ?></th>
                <th align="left"><?php _e('Name','rmcommon'); ?></th>
                <th><?php _e('Smarty Tag','rmcommon'); ?></th>
                <th><?php _e('Active','rmcommon'); ?></th>
            </tr>
            <thead>
            <tfoot>
            <tr>
                <th width="30"><input type="checkbox" id="checkallpb" onclick="$('#frm-positions').toggleCheckboxes(':not(#checkallpb)');" /></th>
                <th width="50" align="left"><?php _e('ID','rmcommon'); ?></th>
                <th align="left"><?php _e('Name','rmcommon'); ?></th>
                <th><?php _e('Smarty Tag','rmcommon'); ?></th>
                <th><?php _e('Active','rmcommon'); ?></th>
            </tr>
            <tfoot>
            <tbody>
            <?php foreach($positions as $pos): ?>
                <tr class="<?php echo tpl_cycle('even,odd'); ?>" id="tr-<?php echo $pos['id']; ?>">
                    <td align="center"><input type="checkbox" name="ids[]" id="itemp-<?php echo $pos['id']; ?>" value="<?php echo $pos['id']; ?>" /></td>
                    <td align="left"><?php echo $pos['id']; ?></td>
                    <td>
                        <?php echo $pos['name']; ?>
                        <span class="rmc_options">
                            <a href="#" onclick="select_option(<?php echo $pos['id']; ?>, 'delete', 'frm-positions')"><?php _e('Delete','rmcommon'); ?></a> |
                            <a href="#" class="edit_position"><?php _e('Edit','rmcommon'); ?></a>
                        </span>
                        <span class="pos_data">
                            <span class="name"><?php echo $pos['name']; ?></span>
                            <span class="tag"><?php echo $pos['tag']; ?></span>
                            <span class="active"><?php echo $pos['active']; ?></span>
                        </span>
                    </td>
                    <td align="center">&lt;{$xoBlocks.<?php echo $pos['tag']; ?>}&gt;</td>
                    <td align="center"><img src="images/<?php echo $pos['active'] ? 'done.png' : 'closeb.png'; ?>" alt="" /></td>
                </tr>    
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="rmc_bulkactions">
            <select name="actionb" id="bulk-bottomp">
                <option value=""><?php _e('Bulk actions...','rmcommon'); ?></option>
                <option value="visiblepos"><?php _e('Visible','rmcommon'); ?></option>
                <option value="hiddenpos"><?php _e('Hidden','rmcommon'); ?></option>
                <option value="deletepos"><?php _e('Delete','rmcommon'); ?></option>
            </select>
            <input type="button" id="the-op-bottomp" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-positions');" />
        </div>
    </form>
</div>
<!--/ Positions -->

<div id="blocker"></div>
<div id="loading"><img src="images/loadinga.gif" width="16" height="16" alt="<?php _e('Loading','rmcomon'); ?>" /><?php _e('Loading data...','rmcommon'); ?></div>
<div id="form-window">
    
</div>