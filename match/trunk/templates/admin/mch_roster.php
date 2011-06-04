<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php if(!isset($team_data)) _e('Roster','match'); else echo sprintf(__('Roster for %s','match'), $team_data['name']); ?></h1>

<form name="frmPlayers" id="frm-players" method="POST" action="roster.php">
<div class="mch_options">
    <?php $nav->display(false); ?>
    <select name="action" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','match'); ?></option>
        <option value="delete"><?php _e('Delete','match'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-players');" />
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
        <?php _e('Search player:','match'); ?>
        <input type="text" size="15" value="<?php echo $search; ?>" name="search" />
        &#160;&#160;&#160;
        <?php _e('Age:','match'); ?>
        <input type="text" name="age" value="<?php echo $age; ?>" size="5" /> <?php _e('years','match'); ?>
        <label><input type="radio" name="direction" value="1"<?php echo $direction==1?' checked="checked"':''; ?> /> <?php _e('or more','match'); ?></label>
        <label><input type="radio" name="direction" value="0"<?php echo $direction==0?' checked="checked"':''; ?> /> <?php _e('or less','match'); ?></label>
        <?php echo $match_extra_options; ?>
        <input type="submit" value="<?php _e('Go now!','match'); ?>" />
    </div>
</div>
<table class="outer" cellspacing="1">
    <thead>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-players").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Team','match'); ?></th>
        <th><?php _e('Registered','match'); ?></th>
        <th><?php _e('Age','match'); ?></th>
        <th><?php _e('Position','match'); ?></th>
    </tr>
    </thead>
    
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-players").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Team','match'); ?></th>
        <th><?php _e('Registered','match'); ?></th>
        <th><?php _e('Age','match'); ?></th>
        <th><?php _e('Position','match'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
    <?php if(empty($players)): ?>
    <tr align="center" class="even">
        <td colspan="7"><?php _e('There are not players registered yet!','match'); ?></td>
    </tr>
    <?php endif; ?>
    <?php foreach($players as $player): ?>
    <tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
        <td><input type="checkbox" name="ids[]" value="<?php echo $player['id']; ?>" id="item-<?php echo $player['id']; ?>" /></td>
        <td><strong><?php echo $player['id']; ?></strong></td>
        <td align="left"><a href="<?php echo $player['link']; ?>"><?php echo $player['lastname'].' '.$player['surname'].', '.$player['name']; ?></a>
        <span class="rmc_options">
            <a href="./roster.php?action=edit&amp;id=<?php echo $player['id']; ?>"><?php _e('Edit','match'); ?></a> | 
            <a href="javascript:;" onclick="select_option(<?php echo $player['id']; ?>,'delete','frm-players');"><?php _e('Delete','match'); ?></a>
        </span>
        </td>
        <td align="center">
            <?php if($team<=0): ?><a href="roster.php?team=<?php echo $player['team']['id']; ?>"><?php echo $player['team']['name']; ?></a><?php else: ?><?php echo $player['team']['name']; ?><?php endif; ?>
            <span class="rmc_options"><a href="teams.php"><?php _e('Teams','match'); ?></a></span>
        </td>
        <td align="center"><?php echo $player['created']; ?></td>
        <td>
            <?php echo $player['age']; ?>
            <span class="mch_pbirth" title="<?php _e('Next birthday','match'); ?>"><?php echo $player['birth']; ?></span>
        </td>
        <td><?php echo $player['position']; ?></td>
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
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-players');" />
    &#160;&#160;&#160;&#160;&#160;
    <a href="#" class="mch_filters"><?php _e('Filter options','match'); ?></a>
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="page" id="team-page" value="<?php echo $page; ?>" />
</form>
