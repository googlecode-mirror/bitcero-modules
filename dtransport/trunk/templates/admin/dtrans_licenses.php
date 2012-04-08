<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Licenses Management','dtransport')); ?></h1>
<script type="text/javascript">
    $(document).ready(function(){
        $("#form-new-lic").validate({
            messages: {
                name: "<?php _e('Please specify a name for this license','dtransport'); ?>",
                url: "<?php _e('Please, specify an URL for this license','dtransport'); ?>"
            }
        });
    });
</script>
<div class="dt_table">
    <div class="dt_row">
        <!-- Licences form -->
        <div class="dt_cell width-300" id="">
            <form name="newLic" id="form-new-lic" method="post" action="licenses.php">
                <h3>Add New License</h3>
                <label for="name" class="captions"><?php _e('License name','dtransport'); ?></label>
                <input type="text" name="name" id="name" value="" class="input required" />
                
                <label for="url" class="captions"><?php _e('License URL','dtransport'); ?></label>
                <input type="text" name="url" id="url" value="" class="input url" />
                <span class="description"><?php _e('This URL will be used to give a reference to users that need to know more about specified license.','dtransport'); ?></span>
                
                <label for="type" class="captions"><?php _e('License type','dtransport'); ?></label>
                <label><input type="radio" name="type" id="type-0" value="0" checked="checked" /> <?php _e('Open source license','dtransport'); ?></label>
                <label><input type="radio" name="type" id="type-1" value="1" /> <?php _e('Restrictive license','dtransport'); ?></label>
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<?php echo $xoopsSecurity->createToken(); ?>" />
                <p><input type="submit" id="lic-submit" value="<?php _e('Add License','dtransport'); ?>" /></p>
            </form>
        </div>
        
        <!-- Existing licences -->
        <div class="dt_cell">
            <form name="frmlic" id="frm-lics" method="POST" action="licenses.php">
            <div class="dt_options">
                <select name="action" id="bulk-top">
                    <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
                    <option value="delete"><?php _e('Delete','dtransport'); ?></option>
                </select>
                <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-lics');" />
            </div>
            <table width="100%" class="outer" cellspacing="1">
                <tr align="center">
                    <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-lics").toggleCheckboxes(":not(#checkall)");' /></th>
                    <th width="20"><?php _e('ID','dtransport'); ?></th>
                    <th align="left"><?php _e('Name','dtransport'); ?></th>
                    <th><?php _e('URL','dtransport'); ?></th>
                </tr>
                <?php if(empty($licences)): ?>
                <tr align="center" class="even">
                    <td colspan="4">
                        <span class="error"><?php _e('There are not licenses created yet!','dtransport'); ?></span>
                    </td>
                </tr>
                <?php endif; ?>
                <?php foreach($licences as $lic): ?>
                <tr class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
                    <td><input type="checkbox" name="ids[]" id="item-<?php echo $lic['id']; ?>" value="<?php echo $lic['id']; ?>" /></td>    
                    <td align="center"><strong><?php echo $lic['id']; ?></strong></td>
                    <td>
                        <?php echo $lic['name']; ?>
                        <span class="rmc_options">
                            <a href="licenses.php?action=edit&amp;id=<?php echo $lic['id']; ?>"><?php _e('Edit','dtransport'); ?></a> |
                            <a href="#" onclick="dt_check_delete(<?php echo $lic['id']; ?>, 'frm-lics'); return false;"><?php _e('Delete','dtransport'); ?></a>
                        </span>
                    </td>
                    <td align="center"><a href="<?php echo $lic['url']; ?>" target="_blank"><?php echo $lic['url']; ?></a></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <div class="dt_options">
                <select name="actionb" id="bulk-bottom">
                    <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
                    <option value="delete"><?php _e('Delete','dtransport'); ?></option>
                </select>
                <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-lics');" />
            </div>
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
            </form>
        </div>
    </div>
</div>
