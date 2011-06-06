<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Championships','match'); ?></h1>

<div id="mch-champform">
<form action="champ.php" method="post" name="frmNChamp">
    <h3><?php _e('Add Championship','match'); ?></h3>
    <label for="name"><?php _e('Championship Name:','match'); ?></label>
    <input type="text" name="name" id="name" value="" size="30" />
    <?php echo $editor->render(); ?>
    <label for="start"><?php _e('Start Date','match'); ?></label>
    <?php echo $start->render(); ?>
    <label for="end"><?php _e('End Date','match'); ?></label>
    <?php echo $end->render(); ?>
    <input type="submit" value="<?php _e('Add Championship','match'); ?>" />
    <input type="hidden" name="action" value="save" />
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
</div>

<div id="mch-champtable">
    <form name="frmChamps" id="frm-champs" method="POST" action="champ.php">
    <div class="mch_options">
        <?php $nav->display(false); ?>
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','match'); ?></option>
            <option value="delete"><?php _e('Delete','match'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-champs');" />
    </div>
    <table class="outer" cellspacing="1">
        <thead>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-champs").toggleCheckboxes(":not(#checkall)");' /></th>
            <th width="30"><?php _e('ID','match'); ?></th>
            <th align="left"><?php _e('Name','match'); ?></th>
            <th><?php _e('Short name','match'); ?></th>
            <th><?php _e('Start','match'); ?></th>
            <th align="center"><?php _e('End','match'); ?></th>
        </tr>
        </thead>
        
        <tfoot>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-champs").toggleCheckboxes(":not(#checkall2)");' /></th>
            <th width="30"><?php _e('ID','match'); ?></th>
            <th align="left"><?php _e('Name','match'); ?></th>
            <th><?php _e('Short name','match'); ?></th>
            <th><?php _e('Start','match'); ?></th>
            <th align="center"><?php _e('End','match'); ?></th>
        </tr>
        </tfoot>
        
        <tbody>
        <?php if(empty($champs)): ?>
        <tr align="center" class="even">
            <td colspan="6"><?php _e('There are not championships created yet!','match'); ?></td>
        </tr>
        <?php endif; ?>
        <?php foreach($champs as $champ): ?>
        <tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
            <td><input type="checkbox" name="ids[]" value="<?php echo $champ['id']; ?>" id="item-<?php echo $champ['id']; ?>" /></td>
            <td><strong><?php echo $champ['id']; ?></strong></td>
            <td align="left"><a href="<?php echo $champ['link']; ?>"><?php echo $champ['name']; ?></a>
            <span class="rmc_options">
                <a href="./champ.php?action=edit&amp;id=<?php echo $champ['id']; ?>"><?php _e('Edit','match'); ?></a> | 
                <a href="javascript:;" onclick="select_option(<?php echo $champ['id']; ?>,'delete','frm-champs');"><?php _e('Delete','match'); ?></a>
            </span>
            </td>
            <td align="center"><?php echo $champ['nameid']; ?></td>
            <td align="center"><?php echo $champ['start']; ?></td>
            <td align="center"><?php echo $champ['end']; ?></td>
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
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-champs');" />
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    </form>
</div>