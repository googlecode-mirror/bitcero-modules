<tr class="score_editor" id="score-editor-<?php echo $item->id(); ?>">
    <td class="empty">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="4" >
        <table>
            <tr>
                <td><?php echo $local->getVar('name'); ?></td>
                <td><?php echo $visitor->getVar('name'); ?></td>
                <td>&nbsp;</td>
                <td><?php _e('Comments','match'); ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><input type="text" size="5" maxlength="2" value="<?php echo $score->getVar('local'); ?>" name="local" /></td>
                <td><input type="text" size="5" maxlength="2" value="<?php echo $score->getVar('visitor'); ?>" name="visitor" /></td>
                <td>
                    <label><input type="radio" value="0" name="other"<?php echo $score->getVar('other')==0?' checked="checked"':''; ?> /> <?php _e('Played','match'); ?></label>
                    <label><input type="radio" value="1" name="other"<?php echo $score->getVar('other')==1?' checked="checked"':''; ?> /> <?php _e('Postponed','match'); ?></label>
                </td>
                <td><input type="text" size="30" maxlength="255" name="comments" value="<?php echo $score->getVar('comments','e'); ?>" /></td>
                <td>
                    <input type="button" value="<?php _e('Set Score','match'); ?>" onclick="set_score(<?php echo $item->id(); ?>);" />
                    <input type="button" value="<?php _e('Cancel','match'); ?>" class="cancel_score" />
                </td>
            </tr>
        </table>
    </td>
</tr>
