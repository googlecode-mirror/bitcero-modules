<h1 class="rmc_titles"><?php _e('Dashboard','bxpress'); ?></h1>
<link href='http://fonts.googleapis.com/css?family=Belgrano' rel='stylesheet' type='text/css'>
<script type="text/javascript">
    var xoops_url = '<?php echo XOOPS_URL; ?>';
</script>
<div class="bx-table">
    <div class="bx_row">
        <div class="bx_cell ds_left">
            
            <!-- Overview -->
            <div class="outer">
                <div class="th"><?php _e('Overview','bxpress'); ?></div>
                <div class="bx-table overvitem">
                    <div class="bx_row">
                        <div class="bx_cell">
                            <a href="categories.php"><?php echo sprintf(__('%s Categories','bxpress'), '<strong>'.$catnum.'</strong>'); ?></a>
                        </div>
                        <div class="bx_cell">
                            <a href="forums.php"><?php echo sprintf(__('%s Forums Available','bxpress'), '<strong>'.$forumnum.'</strong>'); ?></a>
                        </div>
                    </div>
                    <div class="bx_row">
                        <div class="bx_cell">
                            <?php echo sprintf(__('%s Topics Created','bxpress'), '<strong>'.$topicnum.'</strong>'); ?>
                        </div>
                        <div class="bx_cell">
                            <?php echo sprintf(__('%s Posts Sent','bxpress'), '<strong>'.$postnum.'</strong>'); ?>
                        </div>
                    </div>
                    <div class="bx_row">
                        <div class="bx_cell">
                            <a href="announcements.php"><?php echo sprintf(__('%s Announcements Made','bxpress'), '<strong>'.$annum.'</strong>'); ?></a>
                        </div>
                        <div class="bx_cell">
                            <?php echo sprintf(__('%s Files Attached','bxpress'), '<strong>'.$attnum.'</strong>'); ?>
                        </div>
                    </div>
                    <div class="bx_row">
                        <div class="bx_cell">
                            <a href="reports.php"><?php echo sprintf(__('%s Reports Received','bxpress'), '<strong>'.$repnum.'</strong>'); ?></a>
                        </div>
                        <div class="bx_cell">
                            <?php echo sprintf(__('%s Days Running','bxpress'), '<strong>'.$daysnum.'</strong>'); ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="bx_cell ds_right">
            
            <!-- Recent News -->
            <div class="outer">
                <div class="th news"><img src="../images/loading.gif" class="rd_loading_image" /> <?php _e('bXpress News','bxpress'); ?></div>
                <div id="bx-news">
                    <div align="center"><?php _e('Loading news...','bxpress'); ?></div>
                </div>
            </div>
            
            <!-- About bXpress -->
            <div class="outer" id="bx-info">
                <div class="th info"><img src="../images/loading.gif" class="rd_loading_image" /><?php _e('About bXpress','bxpress'); ?></div>
                <div class="even description"></div>
                <div class="odd credits"></div>
                <div class="even donate">
                    <strong><?php _e('If you wish to support my work, you can send money to my PayPal&reg; account (same email).','bxpress'); ?></strong>
                </div>
            </div>
            
            <!-- Other blocks -->
            <?php echo $rblocks; ?>
            
        </div>
    </div>
</div>
