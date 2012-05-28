<h1 class="rmc_titles" xmlns="http://www.w3.org/1999/html"><?php _e('Modules Management','rmcommon'); ?></h1>
<script type="text/javascript">
<!--
    var message = "<?php _e('Do you really want to uninstall selected module?','rmcommon'); ?>";
    var message_upd = "<?php _e('Do you really want to update selected module?','rmcommon'); ?>";
    var message_dis = "<?php _e('Do you really want to disable selected module?','rmcommon'); ?>";
    var message_name = "<?php _e('New name must be different from current name!','rmcommon'); ?>";
    var message_wname = "<?php _e('You must provide a new name!','designia'); ?>";
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
            <a href="<?php if($mod['active']): ?><?php echo $mod['admin_link']; ?><?php else: ?>#<?php endif; ?>" title="<?php echo $mod['realname']; ?>"><img src="<?php echo $mod['icon']; ?>" alt="<?php echo $mod['name']; ?>" /></a>
        </td>
        <td class="name" align="left">
            <span class="the_name">
            <?php if($mod['active']): ?>
            <a href="<?php echo $mod['link']; ?>"><?php echo $mod['name']; ?></a>
            <?php else: ?>
            <?php echo $mod['name']; ?>
            <?php endif; ?>
            </span>
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
            <span class="hidden_data">
                <span class="adminlink"><?php echo $mod['admin_link']; ?></span>
                <span class="link"><?php echo $mod['link']; ?></span>
                <span class="name"><?php echo $mod['name']; ?></span>
                <span class="id"><?php echo $mod['id']; ?></span>
                <span class="icon"><?php echo $mod['icon']; ?></span>
                <span class="image"><?php echo $mod['image']; ?></span>
                <span class="oname"><?php echo $mod['realname']; ?></span>
                <span class="version"><?php echo $mod['version']; ?></span>
                <span class="dirname"><?php echo $mod['dirname']; ?></span>
                <span class="author"><?php echo $mod['author']; ?></span>
                <span class="mail"><?php echo $mod['author_mail']; ?></span>
                <span class="web"><?php echo $mod['author_web']; ?></span>
                <span class="url"><?php echo $mod['author_url']; ?></span>
                <span class="license"><?php echo $mod['license']; ?></span>
                <span class="help"><?php echo $mod['help']; ?></span>
            </span>
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

<div id="info-blocker"></div>
<div id="info-module">
    <div class="th"><?php _e('Module Information','designia'); ?></div>
    <div class="info_container">
	    <div class="header">
            <img src="#" alt="#" />
            <h3></h3>
            <span class="desc"></span>
        </div>
        <div class="dark_bg info_sep"></div>
        <table class="thedata" cellspacing="4">
            <tr>
                <td class="caption"><?php _e('Current Version:','designia'); ?></td>
                <td class="caption"><?php _e('Directory:','designia'); ?></td>
            </tr>
            <tr>
                <td class="version"><span></span></td>
                <td class="dirname"><span></span></td>
            </tr>
            <tr>
                <td class="caption"><?php _e('Author(s):','designia'); ?></td>
                <td class="caption"><?php _e('Web site:','designia'); ?></td>
            </tr>
            <tr>
                <td class="author"><span><?php _e('Not provided','designia'); ?></span></td>
                <td class="web"><span><?php _e('Not provided','designia'); ?></span></td>
            </tr>
            <tr>
                <td class="caption"><?php _e('License:','designia'); ?></td>
                <td class="caption"><?php _e('Help:','designia'); ?></td>
            </tr>
            <tr>
                <td class="license"><span></span></td>
                <td class="help"><span><?php _e('Not provided','designia'); ?></span></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="button" class="buttonOrange info_close" value="<?php _e('Close Window','designia'); ?>" />
                </td>
            </tr>
        </table>
	</div>
</div>