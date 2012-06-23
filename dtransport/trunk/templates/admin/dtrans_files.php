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

            <form name="frmfiles" method="POST" action="files.php">
            <table class="outer" width="100%" cellspacing="1">
                <tr class="head" align="center">
                    <th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmfiles','checkAll')" /></th>
                    <th width="20"><?php _e('ID','dtransport'); ?></th>
                    <th><?php _e('File','dtransport'); ?></th>
                    <th><?php _e('Hits','dtransport'); ?></th>
                    <th><?php _e('External','dtransport'); ?></th>
                    <th><?php _e('Group','dtransport'); ?></th>
                    <th><?php _e('Main file','dtransport'); ?></th>
                    <th><?php _e('Options','dtransport'); ?></th>
                </tr>
                <?php if(empty($files)): ?>
                <tr class="even">
                    <td colspan="8"><?php _e('There are not files with specified parameters currently!','dtransport'); ?></td>
                </tr>
                <?php endif; ?>
                <?php foreach($files as $file): ?>
                <?php if($file['type']=='group'): ?>
                    <tr class="head">
                        <td colspan="7"><{$file.file}></td>
                        <td align="center"><a href="./files.php?item=<{$item}>&amp;edit=1&amp;id=<{$file.id}>"><{$lang_edit}></a> &bull; <a href="./files.php?item=<{$item}>&amp;id=<{$file.id}>&amp;op=deletegroup"><{$lang_del}></a></td>
                    </tr>
                <?php else: ?>
                <tr class="<{cycle values='even,odd'}>" align="center">
                    <td><input type="checkbox" name="id[]" value="<{$file.id}>" /></td>
                    <td><strong><{$file.id}></strong></td>
                    <td align="left"><{$file.title}></td>
                    <td><{$file.downs}></td>
                    <td><{if $file.remote}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png"/><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png"/><{/if}></td>
                    <td>
                        <select name="groups[<{$file.id}>]">
                            <option value="0"><{$lang_select}></option>
                            <{foreach item=group from=$groups}>
                                <option value="<{$group.id}>" <{if $group.id==$file.group}>selected<{/if}>><{$group.name}></option>
                            <{/foreach}>
                        </select>
                    </td>
                    <td><a href="./files.php?item=<{$item}>&amp;id=<{$file.id}>&amp;op=default"><{if $file.default}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png"/><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png"/><{/if}></a></td>
                    <td><a href="./files.php?op=edit&amp;id=<{$file.id}>&amp;item=<{$item}>"><{$lang_edit}></a> &bull; <a href="./files.php?op=delete&amp;id=<{$file.id}>&item=<{$item}>"><{$lang_del}></a></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <tr class="foot">
                    <td width="20" align="right"><img src="<{$xoops_url}>/images/root.gif" border="0" /></td>
                    <td colspan="7"><input type="submit" value="<{$lang_save}>" class="formButtonOk" onclick="document.forms['frmfiles'].elements['op'].value='updategroup';" />
                    <input type="submit" value="<{$lang_del}>" class="formButton" onclick="document.forms['frmfiles'].elements['op'].value='delete'" />
            </td>
                </tr>
            </table>
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
            <input type="hidden" name="op" />
            <input type="hidden" name="item" value="<{$item}>" />
            </form>
        </div>
    </div>
</div>
<?php else: ?>
	<strong><{$lang_selectitem}></strong>
<?php endif; ?>
