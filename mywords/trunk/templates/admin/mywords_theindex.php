<h1 class="rmc_titles mw_titles"><span style="background-position: -96px 0;">&nbsp;</span><?php _e('Dashboard','admin_mywords'); ?></h1>

<div id="mw_widgets_container">

    <div class="mw_right_widgets">
        <!-- Quick publishing -->
        <div class="outer">
            <div class="th"><?php _e('Publish easily','admin_mywords'); ?></div>
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
                    <td><a href="<?php echo RMCURL; ?>/comments.php?module=mywords"><span><?php echo $numcoms; ?></span></a></td>
                    <td><a href="<?php echo RMCURL; ?>/comments.php?module=mywords"><?php _e('Comments','admin_mywords'); ?></a></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="posts.php?status=draft"><span><?php echo $numdrafts; ?></span></a></td>
                	<td><a href="posts.php?status=draft"><?php _e('Drafts','admin_mywords'); ?></a></td>
                	<td></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="posts.php?status=pending"><span><?php echo $numpending; ?></span></a></td>
                	<td><a href="posts.php?status=pending"><?php _e('Pending of Review','admin_mywords'); ?></a></td>
                	<td></td>
                </tr>
                <tr class="mw_qrdata">
                	<td align="right"><a href="categories.php"><span><?php echo $numcats; ?></span></a></td>
                	<td><a href="categories.php"><?php _e('Categories','admin_mywords'); ?></a></td>
                	<td></td>
                </tr>
            </table>
        </div>
        <!-- / End quick overview -->
    </div>
    
</div>
