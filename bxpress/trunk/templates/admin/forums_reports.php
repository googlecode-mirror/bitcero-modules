<h1 class="rmc_titles"><?php _e('Reports','bxpress'); ?></h1>

<form name="frmReports" id="frm-reports" method="POST" action="reports.php">
    <div class="bxpress_options">
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','bxpress'); ?></option>
            <option value="review"><?php _e('Mark as reviewed','bxpress'); ?></option>
            <option value="notreview"><?php _e('Mark as not reviewed','bxpress'); ?></option>
            <option value="delete"><?php _e('Delete','bxpress'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-reports');" />
        &nbsp; &nbsp;
        <a href="resources.php"><?php _e('Show All','bxpress'); ?></a>
    </div>
<table class="outer" width="100%" cellspacing="1">
    <thead>
	<tr align="center"> 
            <th><input type="checkbox" id="checkall" onchange="$('#frm-reports').toggleCheckboxes(':not(#checkall)');"></th>
            <th><?php _e('ID','bxpress'); ?></th>
            <th><?php _e('Report','bxpress'); ?></th>
            <th><?php _e('User','bxpress'); ?></th>
            <th><?php _e('Date','bxpress'); ?></th>
            <?php if($show!=2): ?>
            <th><?php _e('Zapped','bxpress'); ?></th>
            <th><?php _e('Zapped by','bxpress'); ?></th>
            <th><?php _e('Zapped time','bxpress'); ?></th>
            <?php endif; ?>
	</tr>
    </thead>
    <tfoot>
	<tr align="center"> 
            <th><input type="checkbox" id="checkall2" onchange="$('#frm-reports').toggleCheckboxes(':not(#checkall2)');"></th>
            <th><?php _e('ID','bxpress'); ?></th>
            <th><?php _e('Report','bxpress'); ?></th>
            <th><?php _e('User','bxpress'); ?></th>
            <th><?php _e('Date','bxpress'); ?></th>
            <?php if($show!=2): ?>
            <th><?php _e('Zapped','bxpress'); ?></th>
            <th><?php _e('Zapped by','bxpress'); ?></th>
            <th><?php _e('Zapped time','bxpress'); ?></th>
            <?php endif; ?>
	</tr>
    </tfoot>

    <?php foreach($reports as $report): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
	<td align="center"><input type="checkbox" name="ids[]" id="item-<?php echo $report['id']; ?>" value="<?php echo $report['id']; ?>" /></td>
        <td align="center"><?php echo $report['id']; ?></td>
        <td>
            <?php echo $report['report']; ?>
            <span class="rmc_options">
            <?php if($show==2 || $report['zapped']==0): ?>
            <a href="./reports.php?op=review&show=<?php echo $show; ?>&report=<?php echo $report['id']; ?>"><?php _e('Review','bxpress'); ?></a> | <a href="#" onclick="return select_option(<?php echo $report['id']; ?>,'delete','frm-reports');"><?php _e('Delete','bxpress'); ?></a>
            <?php else: ?>
            <a href="#" onclick="return select_option(<?php echo $report['id']; ?>,'delete','frm-reports');"><?php _e('Delete','bxpress'); ?></a>
            <?php endif; ?>
            </span>
        </td>
        <td align="center"><?php echo $report['user']; ?></td>
        <td align="center"><?php echo $report['date']; ?></td>
        <?php if($show!=2): ?>
        <td align="center">
            <?php if($report['zapped']): ?><img src="../images/ok.png" border="0" /><?php else: ?><img src="../images/no.png" border="0" /><?php endif; ?></td>
        <td><?php echo $report['zappedname']; ?></td>
        <td align="center"><?php if($report['zappedtime']): ?><?php echo $report['zappedtime']; ?><?php else: ?>&nbsp;<?php endif; ?></td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>
    <div class="bxpress_options">
        <select name="actionb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','bxpress'); ?></option>
            <option value="review"><?php _e('Mark as reviewed','bxpress'); ?></option>
            <option value="notreview"><?php _e('Mark as not reviewed','bxpress'); ?></option>
            <option value="delete"><?php _e('Delete','bxpress'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-reports');" />
        &nbsp; &nbsp;
        <a href="resources.php"><?php _e('Show All','bxpress'); ?></a>
    </div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>

