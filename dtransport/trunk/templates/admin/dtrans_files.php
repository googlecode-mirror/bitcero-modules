<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo $title; ?></h1>
<div class="even">
<a href="javascript:;" onclick="centerWindow(openWithSelfMain('<?php echo XOOPS_URL; ?>/modules/dtransport/include/dtlistsoft.php?parent=<?php echo $parent; ?>','listsoft',500,300,true),500,300);"><{$lang_listsoft}></a>

<?php if($item>0): ?>

<div class="dt_table">
    <div class="dt_row">
        <div class="dt_cell dt_group_form">
            <form name="frmNewGroup" id="form-new-group" method="post" action="../ajax/files-ajax.php">
                <h3><?php _e('Add group of files','dtransport'); ?></h3>
                <label for="group-name"><?php _e('Group name:','dtransport'); ?></label>
                <input type="text" name="name" id="group-name" value="" />
                <input type="button" class="buttonBlue" id="create-group" value="<?php _e('Create Group','dtransport'); ?>" />
                <div class="descriptions">
                    <?php _e('Groups allows to organize different files according to specific features.'); ?>
                </div>
                <input type="hidden" name="action" value="save-group" />
                <input type="hidden" name="item" value="<?php echo $item; ?>" />
            </form>
        </div>
        <div class="dt_cell">

            <form name="frmfiles" id="frm-files" method="POST" action="files.php">
            <table class="outer notsortable" width="100%" cellspacing="1" id="table-files">
                <thead>
                <tr class="head" align="center">
                    <th width="20"><?php _e('ID','dtransport'); ?></th>
                    <th><?php _e('File','dtransport'); ?></th>
                    <th><?php _e('Hits','dtransport'); ?></th>
                    <th><?php _e('External','dtransport'); ?></th>
                    <th><?php _e('Group','dtransport'); ?></th>
                    <th><?php _e('Default','dtransport'); ?></th>
                    <th><?php _e('Options','dtransport'); ?></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="head" align="center">
                    <th width="20"><?php _e('ID','dtransport'); ?></th>
                    <th><?php _e('File','dtransport'); ?></th>
                    <th><?php _e('Hits','dtransport'); ?></th>
                    <th><?php _e('External','dtransport'); ?></th>
                    <th><?php _e('Group','dtransport'); ?></th>
                    <th><?php _e('Default','dtransport'); ?></th>
                    <th><?php _e('Options','dtransport'); ?></th>
                </tr>
                </tfoot>
                <tbody>
                <?php if(empty($files)): ?>
                <tr class="even">
                    <td colspan="8"><?php _e('There are not files with specified parameters currently!','dtransport'); ?></td>
                </tr>
                <?php endif; ?>
                <?php foreach($files as $file): ?>
                <?php if($file['type']=='group'): ?>
                    <tr class="head" id="group-<?php echo $file['id']; ?>">
                        <td colspan="6"><?php echo $file['file']; ?></td>
                        <td align="center">
                            <a href="#" class="editgroup"><?php _e('Edit','dtransport'); ?></a> |
                            <a href="files.php?item=<?php echo $item; ?>&amp;id=<?php echo $file['id']; ?>&amp;action=deletegroup" class="deletegroup"><?php _e('Delete','dtransport'); ?></a>
                        </td>
                    </tr>
                <?php else: ?>
                <tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center">
                    <td style="display: none;"><input type="checkbox" name="id[]" id="item-<?php echo $file['id']; ?>" value="<?php echo $file['id']; ?>" /></td>
                    <td><strong><?php echo $file['id']; ?></strong></td>
                    <td align="left"><?php echo $file['title']; ?></td>
                    <td><?php echo $file['downs']; ?></td>
                    <td><?php if($file['remote']): ?><img src="../images/ok.png"/><?php else: ?><img src="../images/no.png"/><?php endif; ?></td>
                    <td>
                        <select name="groups[<?php echo $file['id']; ?>]" class="group-selector">
                            <option value="0"><?php _e('Select group...','dtransport'); ?></option>
                            <?php foreach($groups as $group): ?>
                                <option value="<?php echo $group['id']; ?>" <?php if($group['id']==$file['group']): ?>selected<?php endif; ?>><?php echo $group['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><a href="./files.php?item=<?php echo $item; ?>&amp;id=<?php echo $file['id']; ?>&amp;action=default"><?php if($file['default']): ?><img src="../images/ok.png"/><?php else: ?><img src="../images/no.png" /><?php endif; ?></a></td>
                    <td>
                        <a href="./files.php?action=edit&amp;id=<?php echo $file['id']; ?>&amp;item=<?php echo $item; ?>"><?php _e('Edit','dtransport'); ?></a> |
                        <a href="#" class="delete-file"><?php _e('Delete','dtransport'); ?></a>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
            <input type="hidden" name="item" id="item" value="<?php echo $item; ?>" />
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div id="status-bar">
    <?php _e('Applying changes, please wait a second...','dtransport'); ?>
</div>