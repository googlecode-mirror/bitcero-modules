<h1 class="rmc_titles" xmlns="http://www.w3.org/1999/html"><?php _e('Modules Management','rmcommon'); ?></h1>
<script type="text/javascript">
<!--
    var message = "<?php _e('Do you really want to uninstall selected module?','rmcommon'); ?>";
    var message_upd = "<?php _e('Do you really want to update selected module?','rmcommon'); ?>";
    var message_dis = "<?php _e('Do you really want to disable selected module?','rmcommon'); ?>";
    var message_name = "<?php _e('New name must be different from current name!','rmcommon'); ?>";
-->
</script>

<form action="modules.php" method="post" id="form-modules">
<input type="hidden" name="action" id="mod-action" value="" />
<input type="hidden" name="module" id="mod-dir" value="" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>

<table class="tablesorter" id="des-mods-container">
    <thead>
    <tr>
        <th class="logo"><?php _e('Image','designia'); ?></th>
        <th><?php _e('Name','designia'); ?></th>
        <th><?php _e('Version','designia'); ?></th>
        <th><?php _e('Author','designia'); ?></th>
        <th colspan="4"><?php _e('Options','designia'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th class="logo"><?php _e('Image','designia'); ?></th>
        <th><?php _e('Name','designia'); ?></th>
        <th><?php _e('Version','designia'); ?></th>
        <th><?php _e('Author','designia'); ?></th>
        <th colspan="4"><?php _e('Options','designia'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach($modules as $mod): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>" id="module-<?php echo $mod['dirname']; ?>" valign="middle" align="center">
        <td class="logo">
            <a href="<?php if($mod['active']): ?><?php echo $mod['admin_link']; ?><?php else: ?>#<?php endif; ?>" title="<?php echo $mod['realname']; ?>"><img src="<?php echo $mod['image']; ?>" alt="<?php echo $mod['name']; ?>" /></a>
        </td>
        <td class="name" align="left">
            <?php if($mod['active']): ?>
            <a href="<?php echo $mod['link']; ?>"><?php echo $mod['name']; ?></a>
            <?php else: ?>
            <?php echo $mod['name']; ?>
            <?php endif; ?>
            <a href="#" class="rename"><?php _e('Edit','rmcommon'); ?></a>
            <span class=descriptions><?php echo $mod['description']; ?></span>
        </td>
        <td nowrap="nowrap">
            <?php echo $mod['version']; ?>
        </td>
        <td class="author">
            <?php if($mod['author_mail']!=''): ?>
            <a href="mailto:<?php echo $mod['author_mail']; ?>"><?php echo $mod['author']; ?></a>
            <?php else: ?>
            <?php echo $mod['author']; ?>
            <?php endif; ?>
        </td>
        <td class="actions">
            <a href="#" class="data_button" title="<?php _e('Show Information','designia'); ?>">
                <img src="<?php echo RMCURL; ?>/themes/designia/images/data.png" alt="<?php _e('Show Information','rmcommon'); ?>" />
            </a>
        </td>
        <td class="actions">
            <a href="#" class="update_button" title="<?php _e('Update','rmcommon'); ?>">
                <img src="<?php echo RMCURL; ?>/themes/designia/images/update.png" alt="<?php _e('Update','rmcommon'); ?>" />
            </a>
        </td>
        <?php if($mod['active']): ?>
        <td class="actions">
            <a href="#" class="disable_button" title="<?php _e('Disable','rmcommon'); ?>">
                <img src="<?php echo RMCURL; ?>/themes/designia/images/disable.png" alt="<?php _e('Disable','rmcommon'); ?>" />
            </a>
        </td>
            <?php endif; ?>
        <?php if(!$mod['active']): ?>
        <td class="actions">
            <a href="#" class="enable_button" title="<?php _e('Enable','rmcommon'); ?>">
                <img src="<?php echo RMCURL; ?>/themes/designia/images/enable.png" alt="<?php _e('Enable','rmcommon'); ?>" />
            </a>
        </td>
        <?php endif; ?>
        <td class="actions">
            <a href="#" class="uninstall_button" title="<?php _e('Uninstall','rmcommon'); ?>">
                <img src="<?php echo RMCURL; ?>/themes/designia/images/uninstall.png" alt="<?php _e('Uninstall','rmcommon'); ?>" />
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div id="rename-blocker"></div>
<div id="rename">
	<input type="text" size="50" id="rename-name" value="" />
	<input type="submit" id="rename-save" value="<?php _e('Rename','rmcommon'); ?>" />
	<input type="button" onclick="$('#rename-blocker').click();" value="<?php _e('Cancel','rmcommon'); ?>" />
	<input type="hidden" name="id" id="id-module" value="" />
</div>