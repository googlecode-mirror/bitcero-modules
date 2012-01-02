<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load('visualization', '1.0', {'packages':['corechart']});
function drawVisualization(w,h) {
      // Create and populate the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string','x');
      <?php foreach($forums as $f): ?>
      data.addColumn('number', '<?php echo $f->name(); ?>');
      <?php endforeach; ?>
      <?php foreach($days_rows as $r): ?>
      data.addRow(<?php echo $r; ?>);    
      <?php endforeach; ?>

      // Create and draw the visualization.
      new google.visualization.LineChart(document.getElementById('activity')).
          draw(data, {curveType: "none",
                      width: w, height: h,
                      vAxis: {maxValue: <?php echo $max; ?>},
                      chartArea:{left:30,top:20,height:"90%"}
                      }
              );
}
</script>
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
                            <a href="categos.php"><?php echo sprintf(__('%s Categories','bxpress'), '<strong>'.$catnum.'</strong>'); ?></a>
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
            
            <!-- Activity -->
            <div class="outer">
                <div class="th"><?php _e('Activity','bxpress'); ?></div>
                <div class="even">
                    <ul id="activity-options">
                        <li class="activity pressed"><?php _e('Last 30 days','bxpress'); ?></li>
                        <li class="recent"><?php _e('Recent Posts in Topics','bxpress'); ?></li>
                        <li class="topten"><?php _e('Popular Topics','bxpress'); ?></li>
                    </ul>
                    <div id="activity"></div>
                    <div id="recent">
                        <?php foreach($topics as $t): ?>
                        <div class="<?php echo tpl_cycle("even,odd"); ?>">
                            <strong><a href="<?php echo $t['link']; ?>"><?php echo $t['title']; ?></a></strong>
                            <span class="tdata">
                            <?php echo sprintf(__('Forum: %s','bxpress'), '<a href="'.$t['forum']['link'].'">'.$t['forum']['name'].'</a>'); ?><br />
                            <?php echo $t['post']['date']; ?> | 
                            <em><a target="_blank" href="<?php echo XOOPS_URL; ?>/userinfo.php?uid=<?php echo $t['post']['uid']; ?>"><?php echo $t['post']['by']; ?></a></em>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="topten">
                        <?php foreach($poptops as $t): ?>
                        <div class="<?php echo tpl_cycle("even,odd"); ?>">
                            <strong><a href="<?php echo $t['link']; ?>"><?php echo $t['title']; ?></a></strong>
                            <span class="tdata">
                            <?php echo sprintf(__('Forum: %s','bxpress'), '<a href="'.$t['forum']['link'].'">'.$t['forum']['name'].'</a>'); ?><br />
                            <?php echo $t['date']; ?> | <?php echo sprintf(__('Replies: %s','bxpress'), '<strong>'.$t['replies'].'</strong>'); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Other blocks -->
            <?php echo $lblocks; ?>
            
        </div>
        <div class="bx_cell ds_right">
            
            <!-- About bXpress -->
            <div class="outer" id="bx-info">
                <div class="th info"><img src="../images/loading.gif" class="rd_loading_image" /><?php _e('About bXpress','bxpress'); ?></div>
                <div class="even description"></div>
                <div class="odd credits"></div>
                <div class="even donate">
                    <strong><?php _e('If you wish to support my work, you can send money to my PayPal&reg; account (i.bitcero@gmail.com).','bxpress'); ?></strong>
                </div>
            </div>
            
            <!-- Recent News -->
            <div class="outer">
                <div class="th news"><img src="../images/loading.gif" class="rd_loading_image" /> <?php _e('bXpress News','bxpress'); ?></div>
                <div id="bx-news">
                    <div align="center"><?php _e('Loading news...','bxpress'); ?></div>
                </div>
            </div>
            
            <!-- Other blocks -->
            <?php echo $rblocks; ?>
            
        </div>
    </div>
</div>
