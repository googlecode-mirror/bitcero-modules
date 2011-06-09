<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Role Play','match'); ?></h1>

<form action="role.php" method="get" name="frmFilter" id="frm-filter">
    <div class="descriptions">
        <?php _e('You must define the next filter paramenters in order to view, create or modify role plays.','match'); ?>
    </div>
    <div class="mch_options players_filters">
        
        <select name="champ" id="champ">
            <option value=""<?php echo $champ<=0?' selected="selected"':''; ?>><?php _e('Select Championship...','match'); ?></option>
            <?php foreach($champs as $ch): ?>
            <option value="<?php echo $ch['id']; ?>"<?php echo $champ==$ch['id']?' selected="selected"':''; ?><?php echo $ch['current']?' style="font-weight: bold;"':''; ?>><?php echo $ch['name']; ?><?php echo $ch['current']?' '.__('(Current)','match'):''; ?></option>
            <?php endforeach; ?>
        </select>
        &#160;
        <select name="category" id="category">
            <option value=""<?php echo $category<=0?' selected="selected"':''; ?>><?php _e('Select Category...','match'); ?></option>
            <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['id_cat']; ?>"<?php echo $category==$cat['id_cat']?' selected="selected"':''; ?>><?php echo str_repeat('&#151;', $cat['indent']).' '.$cat['name']; ?></option>
            <?php endforeach; ?>
        </select>
        &#160;
        <select name="team" id="team">
            <option value=""><?php _e('All Teams...','match'); ?></option>
            <?php if($champ && $category): ?>
            <?php foreach($teams as $t): ?>
            <option value="<?php echo $t['id_team']; ?>"<?php echo $team==$t['id_team']?' selected="selected"':''; ?>><?php echo $t['name']; ?></option>
            <?php endforeach; ?>
            <?php endif; ?>
        </select>
        &#160;
        <select name="sday" id="sday">
            <option value=""><?php _e('All Days...','match'); ?></option>
            <?php if($champ && $category): ?>
            <?php foreach($days as $d): ?>
            <option value="<?php echo $d; ?>"<?php echo $sday==$d?' selected="selected"':''; ?>><?php echo $tf->format($d,'%M% %d%, %Y%'); ?></option>
            <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="mch_bc_role">
        &raquo;
        <?php if($champ<=0): ?>
        <span class="msg"><?php _e('Select the championship','match'); ?></span>
        <?php else: ?>
            <strong><?php echo $champs[$champ]['name']; ?></strong> ::
            <?php echo $categories[$category]['name']; ?>
        <?php endif; ?>
        
    </div>

</form>

<?php if($champ>0): ?>
<div id="mch-rnform">
<form name="frmRole" id="frm-role" method="post" action="role.php">
    <table class="outer">
        <tr>
            <th colspan="5"><?php _e('Add Match to Role Play','match'); ?></th>
        </tr>
        <tr class="role_form_tr" align="left">
            <td><?php _e('Local Team:','match'); ?></td>
            <td><?php _e('Visitor Team:','match'); ?></td>
            <td><?php _e('Field:','match'); ?></td>
            <td><?php _e('Date:','match'); ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr class="role_form_tr" align="left">
            <td>
                <select name="local" id="local-team">
                    <option value="0" selected="selected"><?php _e('Select team...','match'); ?></option>
                    <?php foreach($teams as $t): ?>
                    <option value="<?php echo $t['id_team']; ?>"><?php echo $t['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="visitor" id="visitor-team">
                    <option value="0"><?php _e('Select team...','match'); ?></option>
                    <?php foreach($teams as $t): ?>
                    <option value="<?php echo $t['id_team']; ?>"><?php echo $t['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select name="field">
                    <option value="0" selected="selected"><?php _e('Select field...','match'); ?></option>
                    <?php foreach($fields as $f): ?>
                    <option value="<?php echo $f['id_field']; ?>"><?php echo $f['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <?php echo $datetime->render(); ?>
            </td>
            <td><input type="submit" value="<?php _e('Add Match','match'); ?>" /></td>
        </tr>
    </table>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="action" value="saverole" />
    <input type="hidden" name="champ" value="<?php echo $champ; ?>" />
    <input type="hidden" name="category" value="<?php echo $category; ?>" />
</form>
</div>

<div id="mch-rndata">
    <form name="frmItem" id="frm-items" method="post" accept="role.php">
    <table class="outer">
        <thead>
        <tr>
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-items").toggleCheckboxes(":not(#checkall)");' /></th>
            <th align="left"><?php _e('Field','match'); ?></th>
            <th><?php _e('Time','match'); ?></th>
            <th align="left"><?php _e('Local Team','match'); ?></th>
            <th align="left"><?php _e('Visitor Team','match'); ?></th>
            <th><?php _e('Pending','match'); ?></th>
            <th><?php _e('Options','match'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-items").toggleCheckboxes(":not(#checkall2)");' /></th>
            <th align="left"><?php _e('Field','match'); ?></th>
            <th><?php _e('Time','match'); ?></th>
             <th align="left"><?php _e('Local Team','match'); ?></th>
             <th align="left"><?php _e('Visitor Team','match'); ?></th>
             <th><?php _e('Pending','match'); ?></th>
            <th><?php _e('Options','match'); ?></th>
        </tr>
        </tfoot>
        <?php if(empty($role)): ?>
        <tr align="center" class="even">
            <td colspan="8"><?php _e('There are not matches registered for this championship and category yet!','match'); ?></td>
        </tr>
        <?php endif; ?>
        <?php 
            $day = 0;
            $pday = 0;
        ?>
        <?php foreach($role as $item): ?>
        <?php 
            $title = false;
            if($day<=0){
                $title = true;
                $day = mktime(0, 0, 1, date("m",$item['time']), date("d", $item['time']), date('Y', $item['time']));
            }
            
            $now = mktime(23, 59, 0, date("m",$item['time']), date("d", $item['time']), date('Y', $item['time']));
            if($now>$day+(86400)){
                $pday = $day;
                $day = $now;
                $title = true;
            }
            
        ?>
        <?php if($title): ?>
        <tr class="title<?php echo $item['past']?' tplayed':''; ?>" align="left">
            <td colspan="8"><?php echo $tf->format($day, __('%M% %d%, %Y%','match')); ?></td>
        </tr>
        <?php endif; ?>
        <tr valign="top" class="<?php echo tpl_cycle("even,odd"); ?><?php echo $item['past']?' played':''; ?>" id="role-<?php echo $item['id']; ?>">
            <td><input type="checkbox" name="ids[]" id="item-<?php echo $item['id']; ?>" /></td>
            <td align="left"><?php echo $item['field']['name']; ?></td>
            <td align="center"><?php echo $item['hour']; ?></td>
            <td><strong><?php echo $item['local']['name']; ?></strong>
            <?php if($item['past']): ?><span class="score_leg">(<?php echo $item['local']['score']; ?>)</span><?php endif;?></td>
            <td align="left"><strong><?php echo $item['visitor']['name']; ?></strong>
            <?php if($item['past']): ?><span class="score_leg">(<?php echo $item['visitor']['score']; ?>)</span><?php endif;?></td>
            <td align="center"><?php echo $item['past']?__('Played','match'):'<span class="pending">'.__('Pending','match').'</span>'; ?></td>
            <td align="center">
                <span class="rmc_options">
                    <a href="role.php?op=delete&amp;id=<?php echo $item['id']; ?>&amp;champ=<?php echo $champ; ?>&amp;category=<?php echo $category; ?>"><?php _e('Delete','match'); ?></a>
                    <?php if($item['past']): ?>| <a href="#" class="set_score" id="score-<?php echo $item['id']; ?>"><?php _e('Score','match'); ?></a><?php endif; ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </form>
</div>
<?php endif; ?>