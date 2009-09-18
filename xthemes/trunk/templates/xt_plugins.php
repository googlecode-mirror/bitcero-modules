<?php xt_menu_options(); ?>
<h1>Active Plugins</h1>
<table class="outer" cellspacing="0" width="100%">
    <tr>
        <th align="left">Plugin Name</th>
        <th align="center">Version</th>
        <th align="center">Author</th>
        <th>&nbsp;</th>
    </tr>
    <?php if(empty($active_plugins)): ?>
    <tr align="center" class="plug_item">
    	<td colspan="4">There are not plugins activated yet</td>
    </tr>
    <?php endif; ?>
    <?php foreach($active_plugins as $dir => $info): ?>
    <tr class="plug_item">
        <td><strong><?php echo $info['name']; ?></strong><br />
        <span class="desc"><?php echo $info['description']; ?></span></td>
        <td align="center"><?php echo $info['version']; ?></td>
        <td align="center">
            <?php if($info['url']!=''): ?>
                <a href="<?php echo $info['url']; ?>" target="_blank"><?php echo $info['author']; ?></a>
            <?php else: ?>
                <?php echo $info['author']; ?>
            <?php endif; ?>
        </td>
        <td align="center">
            <a href="index.php?op=activate-plugin&amp;plugin=<?php echo $dir; ?>">Deactivate</a> |
            <a href="index.php?op=config-plugin&amp;plugin=<?php echo $dir; ?>">Configuration</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table><br />
<h1>Inactive Plugins</h1>
<table class="outer" cellspacing="0" width="100%">
    <tr>
        <th align="left">Plugin Name</th>
        <th align="center">Version</th>
        <th align="center">Author</th>
        <th>&nbsp;</th>
    </tr>
    <?php foreach($inactive_plugins as $dir => $info): ?>
    <tr class="plug_item">
        <td><strong><?php echo $info['name']; ?></strong><br />
        <span class="desc"><?php echo $info['description']; ?></span></td>
        <td align="center"><?php echo $info['version']; ?></td>
        <td align="center">
            <?php if($info['url']!=''): ?>
                <a href="<?php echo $info['url']; ?>" target="_blank"><?php echo $info['author']; ?></a>
            <?php else: ?>
                <?php echo $info['author']; ?>
            <?php endif; ?>
        </td>
        <td align="center">
            <a href="index.php?op=activate-plugin&amp;plugin=<?php echo $dir; ?>">Activate</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table><br />