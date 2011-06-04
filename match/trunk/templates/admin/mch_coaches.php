<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php if(!isset($team_data)) _e('Coaches','match'); else echo sprintf(__('Coaches for %s','match'), $team_data['name']); ?></h1>

<form name="frmCoaches" id="frm-coaches" method="POST" action="coaches.php">
<div class="mch_options">
    <?php $nav->display(false); ?>
    <select name="action" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','match'); ?></option>
        <option value="delete"><?php _e('Delete','match'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-coaches');" />
    &#160;&#160;&#160;&#160;&#160;
    <a href="#" class="mch_filters"><?php _e('Filter options','match'); ?></a>
    <div class="players_filters" style="<?php echo $filters?'display: block;':'display: none;'; ?>">
        <?php _e('Filter by Team:','match'); ?>
        <select name="team">
            <option value="0"<?php if($team<=0): ?> selected="selected"<?php endif; ?>><?php _e('Select team...','match'); ?></option>
            <?php foreach($teams as $t): ?>
                <option value="<?php echo $t['id_team']; ?>"<?php if($team==$t['id_team']): ?> selected="selected"<?php endif; ?>><?php echo '&nbsp;&nbsp;'.$t['name'].' ('.$t['category_object']['name'].')'; ?></option>
            <?php endforeach; ?>
        </select>
        &#160;&#160;&#160;
        <?php _e('Search coach:','match'); ?>
        <input type="text" size="15" value="<?php echo $search; ?>" name="search" />
        &#160;&#160;&#160;
        <?php echo $match_extra_options; ?>
        <input type="submit" value="<?php _e('Go now!','match'); ?>" />
    </div>
</div>
<table class="outer" cellspacing="1">
    <thead>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-coaches").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Team','match'); ?></th>
        <th><?php _e('Registered','match'); ?></th>
        <th><?php _e('Charge','match'); ?></th>
    </tr>
    </thead>
    
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-coaches").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Team','match'); ?></th>
        <th><?php _e('Registered','match'); ?></th>
        <th><?php _e('Charge','match'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
    <?php if(empty($coaches)): ?>
    <tr align="center" class="even">
        <td colspan="7"><?php _e('There are not coaches registered yet!','match'); ?></td>
    </tr>
    <?php endif; ?>
    <?php foreach($coaches as $coach): ?>
    <tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
        <td><input type="checkbox" name="ids[]" value="<?php echo $coach['id']; ?>" id="item-<?php echo $coach['id']; ?>" /></td>
        <td><strong><?php echo $coach['id']; ?></strong></td>
        <td align="left"><a href="<?php echo $coach['link']; ?>"><?php echo $coach['lastname'].' '.$coach['surname'].', '.$coach['name']; ?></a>
        <span class="rmc_options">
            <a href="./coaches.php?action=edit&amp;id=<?php echo $coach['id']; ?>"><?php _e('Edit','match'); ?></a> | 
            <a href="javascript:;" onclick="select_option(<?php echo $coach['id']; ?>,'delete','frm-coaches');"><?php _e('Delete','match'); ?></a>
        </span>
        </td>
        <td align="center">
            <?php if($team<=0): ?><a href="coaches.php?team=<?php echo $coach['team']['id']; ?>"><?php echo $coach['team']['name']; ?></a><?php else: ?><?php echo $coach['team']['name']; ?><?php endif; ?>
            <span class="rmc_options"><a href="teams.php"><?php _e('Teams','match'); ?></a></span>
        </td>
        <td align="center"><?php echo $coach['created']; ?></td>
        <td><?php echo $coach['charge']; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="mch_options">
    <?php $nav->display(false); ?>
    <select name="actionb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','match'); ?></option>
        <option value="delete"><?php _e('Delete','match'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-coaches');" />
    &#160;&#160;&#160;&#160;&#160;
    <a href="#" class="mch_filters"><?php _e('Filter options','match'); ?></a>
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="page" id="team-page" value="<?php echo $page; ?>" />
</form>
