<h1 class="rmc_titles ct_reply"><?php _e('Reply Message','contact'); ?></h1>

<form name="frmReply" id="frm-reply" method="post" action="index.php">
<table class="outer cm_reply" cellspacing="0" cellpadding="0" />
    <tr class="head">
        <td><?php _e('Subject:','contact'); ?></td>
        <td><input type="text" name="subject" value="<?php echo $subject; ?>" size="50" class="required" /></td>
    </tr>
    <tr class="head">
        <td><?php _e('Replay to:', 'contact'); ?></td>
        <td><strong><?php echo $name; ?></strong><br /><?php echo $email; ?></td>
    </tr>
    <tr class="head">
        <td><?php _e('Message:','contact'); ?></td>
        <td><?php echo $editor->render(); ?></td>
    </tr>
    <tr class="head">
        <td>&nbsp;</td>
        <td>
            <input type="submit" value="<?php _e('Send Email','contact'); ?>" />
            <input type="button" value="<?php _e('Cancel','contact'); ?>" class="cancel_btn" />
        </td>
    </tr>
</table>
<input type="hidden" name="action" value="sendmsg" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>