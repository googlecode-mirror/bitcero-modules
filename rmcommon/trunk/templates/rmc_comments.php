<table class="outer" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" value="" /></th>
        <th><?php _e('Author','rmcommon'); ?></th>
        <th><?php _e('Comment','rmcommon'); ?></th>
        <th><?php _e('In reply to','rmcommon'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" value="" /></th>
        <th><?php _e('Author','rmcommon'); ?></th>
        <th><?php _e('Comment','rmcommon'); ?></th>
        <th><?php _e('In reply to','rmcommon'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(count($comments)<=0): ?>
    <tr class="head">
        <td colspan="4" align="center"><?php _e('There are not comments yet!','rmcommon'); ?></td>
    </tr>
    <?php else: ?>
    <?php foreach ($comments as $com): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
        <td align="center"><input type="checkbox" name="coms[]" id="com-<?php echo $com['id']; ?>" value="<?php echo $com['id']; ?>" /></td>
        <td class="poster_cell"><img class="poster_avatar" src="<?php echo $com['poster']['avatar']; ?>" />
        <strong><?php echo $com['poster']['name']; ?></strong>
        <span class="poster_data"><a href="mailto:<?php echo $com['poster']['email']; ?>"><?php echo $com['poster']['email']; ?></a><br />
        <?php echo $com['ip']; ?></span></td>
        <td><?php echo $com['text']; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
