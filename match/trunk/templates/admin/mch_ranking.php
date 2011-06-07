<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Teams Ranking','match'); ?></h1>

<form action="ranking.php" method="get" name="frmFilter" id="frm-filter">
    <div class="descriptions">
        <?php _e('You must define the next filter paramenters in order to view current ranking.','match'); ?>
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
<div id="mch-rndata">
    <form name="frmItem" id="frm-items" method="post" accept="role.php">
    <table class="outer" style="width: auto;" align="center">
        <thead>
        <tr>
            <th align="left"><?php _e('Position','match'); ?></th>
            <th><?php _e('Team','match'); ?></th>
            <th align="left"><?php _e('Wons','match'); ?></th>
        </tr>
        </thead>
        <?php if(empty($ranking)): ?>
        <tr align="center" class="even">
            <td colspan="3"><?php _e('There are not positions in ranking currently!','match'); ?></td>
        </tr>
        <?php endif; ?>
        <?php 
            $day = 0;
            $pday = 0;
        ?>
        <?php foreach($ranking as $k => $team): ?>
        <tr valign="middle" class="<?php echo tpl_cycle("even,odd"); ?>" align="center">
            <td class="position">#<?php echo $k+1; ?></td>
            <td class="teamd">
                <img src="<?php echo MCH_UP_URL; ?>/<?php echo $team['logo']; ?>" alt="<?php echo $team['name']; ?>" />
                <strong><?php echo $team['name']; ?></strong>
            </td>
            <td align="center"><?php echo $team['wons']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    </form>
</div>
<?php endif; ?>