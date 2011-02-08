<h1 class="rmc_titles ct_dashboard"><?php _e('ContactMe! Dashboard','contact'); ?></h1>

<script type="text/javascript">
    <!--
    var cm_select_message = '<?php _e('You must select at least one message to delete!','contact'); ?>';
    var cm_message = '<?php _e('Do you really wish to delete selected messages?','contact'); ?>';
    -->
</script>

<form name="frmMsgs" id="frm-messages" method="post" action="index.php">

<div class="ct_options">
    <?php $nav->display(false); ?>
    <select name="action" id="bulk-top">
        <option value=""><?php _e('Bulk Actions','contact'); ?></option>
        <option value="delete"><?php _e('Delete','contact'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply', 'contact'); ?>" onclick="submit();" />
</div>

    <table class="outer" cellspacing="0" border="0">
        <thead>
            <tr>
                <th width="20" align="center"><input type="checkbox" name="checkall" id="checkall" /></th>
                <th><?php _e('ID', 'contact'); ?></th>
                <th align="left"><?php _e('Sender','contact'); ?></th>
                <th align="left"><?php _e('Sender Data','contact'); ?></th>
                <th align="left"><?php _e('Message', 'contact'); ?></th>
                <th><?php _e('XOOPS User', 'contact'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th width="20"><input type="checkbox" name="checkall" id="checkall" /></th>
                <th><?php _e('ID', 'contact'); ?></th>
                <th><?php _e('Sender','contact'); ?></th>
                <th><?php _e('Sender Data','contact'); ?></th>
                <th><?php _e('Message', 'contact'); ?></th>
                <th><?php _e('XOOPS User', 'contact'); ?></th>
            </tr>
        </tfoot>
        <tbody>
        <?php if(!$messages): ?>
            <tr class="even">
                <td colspan="6" align="center"><?php _e('There are not messages stored in ContactMe! currently.','contact'); ?></td>
            </tr>
        <?php endif; ?>
        <?php foreach($messages as $msg): ?>
            <tr valign="top" class="<?php echo tpl_cycle("even,odd"); ?>">
                <td align="center"><input type="checkbox" name="ids[]" id="item-<?php echo $msg['id']; ?>" value="<?php echo $msg['id']; ?>" /></td>
                <td align="center"><?php echo $msg['id']; ?></td>
                <td>
                    <strong><?php echo $msg['name']; ?></strong>
                    <span class="rmc_options">
                        <a href="#" class="delete" id="delete-<?php echo $msg['id']; ?>"><?php _e('Delete','contact'); ?></a> |
                        <a href="?action=reply&amp;id=<?php echo $msg['id']; ?>"><?php _e('Reply','contact'); ?></a>
                    </span>
                </td>
                <td class="sender_data" nowrap="nowrap">
                    <strong><?php _e('Email:','contact'); ?></strong> <a href="mailto:<?php echo $msg['email']; ?>"><?php echo $msg['email']; ?></a><br />
                    <?php if($msg['company']!=''): ?><strong><?php _e('Company:','contact'); ?></strong> <?php echo $msg['company']; ?><br /><?php endif; ?>
                    <?php if($msg['phone']!=''): ?><strong><?php _e('Phone:','contact'); ?></strong> <?php echo $msg['phone']; ?><br /><?php endif; ?>
                    <strong><?php _e('IP:','contact'); ?></strong> <?php echo $msg['ip']; ?>
                </td>
                <td>
                    <?php echo $msg['subject']; ?><br />
                    <span class="small_date"><?php echo $msg['date']; ?></span>
                    <?php echo $msg['body']; ?>
                </td>
                <td align="center">
                    <?php if($msg['uname']!=''): ?>
                    <a href="<?php XOOPS_URL; ?>/userinfo.php?uid=<?php echo $msg['xuid']; ?>"><?php echo $msg['uname']; ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<div class="ct_options">
    <?php $nav->display(false); ?>
    <select name="actionb" id="bulk-bottom">
        <option value=""><?php _e('Bulk Actions','contact'); ?></option>
        <option value="delete"><?php _e('Delete','contact'); ?></option>
    </select>
    <input type="button" value="<?php _e('Apply', 'contact'); ?>" onclick="submit();" />
</div>
</form>
