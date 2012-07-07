<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Platforms Management','dtransport'); ?></h1>
<script type="text/javascript">
    $(document).ready(function(){
        $("#form-new-platform").validate({
            messages: {
                name: "<?php _e('Please specify a name for this platform','dtransport'); ?>"
            }
        });
    });
</script>
<div class="dt_table">
    <div class="dt_row">
        <!-- Platforms form -->
        <div class="dt_cell width-300" id="">
            <form name="newPlat" id="form-new-platform" method="post" action="platforms.php">
                <h3>Add New Platform</h3>
                <label for="name" class="captions"><?php _e('Platform name','dtransport'); ?></label>
                <input type="text" name="name" id="name" value="<?php echo $edit ? $plat->name() : ''; ?>" class="input required" />
                <input type="hidden" name="action" value="<?php echo $edit ? 'saveedit' : 'save'; ?>" />
                <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
                <p><input type="submit" id="plat-submit" value="<?php $edit ? _e('Save Platform','dtransport') : _e('Add Platform','dtransport'); ?>" /></p>
                <?php if($edit): ?><input type="hidden" name="id" value="<?php echo $plat->id(); ?>" /><?php endif; ?>
            </form>
        </div>
        
        <!-- Existing platforms -->
        <div class="dt_cell">
            <form name="frmplat" id="frm-plats" method="POST" action="platforms.php">
            <div class="dt_options">
                <select name="action" id="bulk-top">
                    <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
                    <option value="delete"><?php _e('Delete','dtransport'); ?></option>
                </select>
                <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-lics');" />
            </div>
            <table class="outer" width="100%" cellspacing="1">
                <thead>
                    <tr class="head" align="center">
                        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-plats").toggleCheckboxes(":not(#checkall)");' /></th>
                        <th><?php _e('ID','dtransport'); ?></th>
                        <th><?php _e('Name','dtransport'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="head" align="center">
                        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-plats").toggleCheckboxes(":not(#checkall2)");' /></th>
                        <th><?php _e('ID','dtransport'); ?></th>
                        <th><?php _e('Name','dtransport'); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if(empty($platforms)): ?>
                    <tr class="even" align="center">
                        <td colspan="3"><?php _e('There are not platforms created yet!', 'dtransport'); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach($platforms as $plat): ?>
                    <tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
                        <td><input type="checkbox" name="ids[]" id="item-<?php echo $plat['id']; ?>" value="<?php echo $plat['id']; ?>" /></td>
                        <td width="20"><strong><?php echo $plat['id']; ?></strong></td>
                        <td align="left">
                            <?php echo $plat['name']; ?>
                            <span class="rmc_options">
                                <a href="platforms.php?action=edit&amp;id=<?php echo $plat['id']; ?>"><?php _e('Edit','dtransport'); ?></a> |
                                <a href="#" onclick="dt_check_delete(<?php echo $plat['id']; ?>, 'frm-plats'); return false;"><?php _e('Delete','dtransport'); ?></a>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
            <div class="dt_options">
                <select name="actionb" id="bulk-bottom">
                    <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
                    <option value="delete"><?php _e('Delete','dtransport'); ?></option>
                </select>
                <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-lics');" />
            </div>
            </form>
        </div>
    </div>
</div>
