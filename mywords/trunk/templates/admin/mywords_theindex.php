<h1 class="rmc_titles mw_titles"><span style="background-position: -96px 0;">&nbsp;</span><?php _e('Dashboard','admin_mywords'); ?></h1>

<div id="mw_widgets_container">

    <div class="mw_right_widgets">
        <!-- Quick publishing -->
        <div class="outer">
            <div class="th"><?php _e('MyWords Tools','admin_mywords'); ?></div>
            <?php foreach ($posts as $post): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <strong><a href="<?php echo $post->permalink(); ?>"><?php echo $post->getVar('title'); ?></a></strong><br />
                <?php echo strip_tags(substr($post->content(true), 0, 100)).'...'; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- / End quick publishing -->
    </div>
    
    <div class="mw_left_widgets">
        <!-- Quick overview -->
        <div class="outer">
            <div class="th"><?php _e('Quick Overview','admin_mywords'); ?></div>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr class="mw_qrdata">
                	<td align="right" width="20"><a href="posts.php"><span><?php echo $numposts; ?></span></td>
                    <td><a href="posts.php"><?php _e('Posts', 'admin_mywords'); ?></a></td>
                    <td align="right" width="20"><a href="<?php echo RMCURL; ?>/comments.php?module=mywords"><span><?php echo $numcoms; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/comments.php?module=mywords"><?php _e('Comments','admin_mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="posts.php?status=draft"><span><?php echo $numdrafts; ?></span></a></td>
                	<td><a href="posts.php?status=draft"><?php _e('Drafts','admin_mywords'); ?></a></td>
                	<td align="right" width="20"><a href="<?php echo RMCURL; ?>/editors.php"><span><?php echo $numeditors; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/editors.php"><?php _e('Editors','admin_mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="posts.php?status=pending"><span><?php echo $numpending; ?></span></a></td>
                	<td><a href="posts.php?status=pending"><?php _e('Pending of Review','admin_mywords'); ?></a></td>
                	<td align="right" width="20"><a href="<?php echo RMCURL; ?>/bookmarks.php"><span><?php echo $numsocials; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/bookmarks.php"><?php _e('Social sites','admin_mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="categories.php"><span><?php echo $numcats; ?></span></a></td>
                	<td><a href="categories.php"><?php _e('Categories','admin_mywords'); ?></a></td>
                	<td align="right" width="20"><a href="<?php echo RMCURL; ?>/tags.php"><span><?php echo $numtags; ?></span></a></td>
                    <td><a href="<?php echo XOOPS_URL; ?>/modules/mywords/admin/tags.php"><?php _e('Tags','admin_mywords'); ?></a></td>
                </tr>
            </table><br />
            <span class="descriptions">Esta es la descripcion</span>
        </div>
        <!-- / End quick overview -->
    </div>
    
</div>
