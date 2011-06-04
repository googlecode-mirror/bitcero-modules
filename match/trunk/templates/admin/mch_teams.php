<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Teams','match'); ?></h1>

<form name="frmTeams" id="frm-teams" method="POST" action="teams.php">
<div class="mch_options">
    <?php $nav->display(false); ?>
    <select name="action" id="bulk-top">
        <option value=""><?php _e('Bulk actions...','match'); ?></option>
        <option value="delete"><?php _e('Delete','match'); ?></option>
        <option value="active"><?php _e('Enable teams','match'); ?></option>
        <option value="desactive"><?php _e('Disable teams','match'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-teams');" />
    &nbsp; &nbsp; &nbsp;
    <strong><?php _e('Filter by:','match'); ?></strong>
        <select name="category" class="teams-category">
            <option value=""<?php if($category<=0): ?> selected="selected"<?php endif; ?>><?php _e('All Categories','match'); ?></option>
            <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"<?php if($category==$cat['id']): ?> selected="selected"<?php endif; ?>><?php echo str_repeat("&#151;", $cat['indent']).' '.$cat['name']; ?></option>
            <?php endforeach; ?>
        </select>

    <?php echo $match_extra_options; ?>
</div>
<table class="outer" cellspacing="1">
    <thead>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-teams").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Short name','match'); ?></th>
        <th><?php _e('Registered','match'); ?></th>
        <th align="center"><?php _e('Rank','match'); ?></th>
        <th><?php _e('Category','match'); ?></th>
        <th><?php _e('Active','match'); ?></th>
    </tr>
    </thead>
    
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-teams").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><?php _e('ID','match'); ?></th>
        <th align="left"><?php _e('Name','match'); ?></th>
        <th><?php _e('Short name','match'); ?></th>
        <th><?php _e('Registered','match'); ?></th>
        <th align="center"><?php _e('Rank','match'); ?></th>
        <th><?php _e('Category','match'); ?></th>
        <th><?php _e('Active','match'); ?></th>
    </tr>
    </tfoot>
    
    <tbody>
    <?php if(empty($teams)): ?>
    <tr align="center" class="even">
        <td colspan="7"><?php _e('There are not teams registered yet!','match'); ?></td>
    </tr>
    <?php endif; ?>
    <?php foreach($teams as $team): ?>
    <tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
        <td><input type="checkbox" name="ids[]" value="<?php echo $team['id']; ?>" id="item-<?php echo $team['id']; ?>" /></td>
        <td><strong><?php echo $team['id']; ?></strong></td>
        <td align="left"><a href="<?php echo $team['link']; ?>"><?php echo $team['name']; ?></a>
        <span class="rmc_options">
            <a href="roster.php?team=<?php echo $team['id']; ?>"><?php _e('Roster','match'); ?></a> | 
            <a href="./teams.php?action=edit&amp;id=<?php echo $team['id']; ?>"><?php _e('Edit','match'); ?></a> | 
            <a href="javascript:;" onclick="select_option(<?php echo $team['id']; ?>,'delete','frm-teams');"><?php _e('Delete','match'); ?></a>
        </span>
        </td>
        <td align="center"><?php echo $team['nameid']; ?></td>
        <td align="center"><?php echo $team['created']; ?></td>
        <td align="center"><?php echo $team['wins']; ?></td>
        <td>
            <?php if($category<=0): ?><a href="teams.php?category=<?php echo $team['category']['id']; ?>"><?php echo $team['category']['name']; ?></a><?php else: ?><?php echo $team['category']['name']; ?><?php endif; ?>
            <span class="rmc_options"><a href="categories.php"><?php _e('Categories','match'); ?></a></span>
        </td>
        <td><?php if($team['active']): ?><img src="../images/ok.png" /><?php else: ?><img src="../images/no.png" /><?php endif; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="mch_options">
    <?php $nav->display(false); ?>
    <select name="actionb" id="bulk-bottom">
        <option value=""><?php _e('Bulk actions...','match'); ?></option>
        <option value="delete"><?php _e('Delete','match'); ?></option>
        <option value="active"><?php _e('Enable categories','match'); ?></option>
        <option value="desactive"><?php _e('Disable categories','match'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-teams');" />
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="page" id="team-page" value="<?php echo $page; ?>" />
</form>
