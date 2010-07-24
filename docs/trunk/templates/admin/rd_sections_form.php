<h1 class="rmc_titles mw_titles"><span style="background-position: left -32px;">&nbsp;</span><?php $edit ? _e('Edit Section','docs') : _e('Create Section','docs'); ?></h1>
<form name="formSection" method="post" action="sections.php" id="frm-section">
<div id="rd-form-container" class="form">
    <input type="text" size="50" name="title" value="<?php $edit ? $sec->getVar('title') : ''; ?>" class="large" />
    <?php if($edit): ?>
    <div id="section-url"><strong>Permalink:</strong> <?php echo XOOPS_URL; ?>/<?php if($xoopsModuleConfig['permalinks']): ?><?php echo $xoopsModuleConfig['htpath']; ?><?php else: ?>modules/docs/<?php endif; ?></div>
    <?php else: ?>
    <div class="info"><?php _e('Remember to save this section in order to activate all options.','docs'); ?></div>
    <?php endif; ?>
    <?php echo $editor->render(); ?>
    <br />
    <table width="100%" cellspacing="0">
        <tr>
            <td style="padding: 4px; vertical-align: top;">
            <div class="outer">
                <div class="th"><?php _e('Section Author:','docs'); ?></div>
                <div class="even" style="text-align: center;">
                    <?php echo $usrfield->render(); ?>
                </div>
            </div>
            </td>
            <td style="padding: 4px; vertical-align: top;">
                <table class="outer">
                    <tr><th align="left" colspan="3"><?php _e('Sections Options','docs'); ?></th></tr>
                    <tr class="even section_options">
                        <td>
                            <label for="sec-parent"><?php _e('Parent section:','docs'); ?></label>
                            <select name="parent" id="sec-parent">
                                <option value=""><?php _e('Select...','docs'); ?></option>
                                <?php foreach($sections as $k): ?>
                                <option value="<?php echo $k['id_sec']; ?>"<?php if(isset($sec) && $sec->parent()==$k['id_sec']): ?> selected="selected"<?php endif; ?>><?php echo str_repeat('--', $k['saltos']).' '.$k['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                             <label for="sec-order"><?php _e('Display order:','docs'); ?></label>
                             <input type="text" size="5" id="sec-order" name="order" value="<?php echo isset($sec) ? $sec->getVar('order') : 0; ?>" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <div class="outer">
        <div class="th"><?php _e('Custom Fields','docs'); ?></div>
        <div class="even">
        <table id="metas-container" class="outer<?php echo !$edit || (!isset($post) && !$post->fields()) ? ' mw_hidden' : ''; ?>" cellspacing="0" width="100%" />
            <tr class="head">
                <td width="30%"><?php _e('Name','docs'); ?></td>
                <td><?php _e('Value','docs'); ?></td>
            </tr>
            <?php if($edit || (isset($sec) && $sec->metas())): ?>
            <?php foreach($sec->metas() as $field): ?>
                <tr class="<?php echo tpl_cycle("even,odd"); ?>">
                    <td valign="top"><input type="text" name="meta[<?php echo $field['id']; ?>][key]" id="meta-key-<?php echo $field['id']; ?>" value="<?php echo $field['name']; ?>" /></td>
                    <td><textarea name="meta[<?php echo $field['id']; ?>][value]" id="meta[<?php echo $field['id']; ?>][value]"><?php echo $field['value']; ?></textarea></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </table><br />
        <label><strong><?php _e('Add new field:','docs'); ?></strong></label>
        <table class="outer" cellspacing="0" />
            <tr class="head" align="center">
                <td width="30%"><?php _e('Name','docs'); ?></td>
                <td><?php _e('Value','docs'); ?></td>
            </tr>
            <tr class="even">
                <td valign="top">
                    <label class="error" style="display: none;" id="error-metaname">Please, select or specify a new meta name</label>
                    <?php if(!empty($meta_names)): ?>
                    <select name="meta_name_sel" id="meta-name-sel">
                        <option value="" selected="selected"><?php _e('- Select -','docs'); ?></option>
                        <?php foreach ($meta_names as $name): ?>
                        <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="meta_name" id="meta-name" value="" class="rd_large" style="display: none; width: 95%;" />
                    <a href="javascript:;" class="rd_show_metaname"><?php _e('Enter New','docs'); ?></a>
                    <a href="javascript:;" class="rd_hide_metaname" style="display: none;"><?php _e('Cancel','docs'); ?></a>
                    <?php else: ?>
                    <input type="text" name="meta_name" id="meta-name" value="" class="rd_large" style="width: 95%;" />
                    <?php endif; ?>
                </td>
                <td valign="top">
                    <label class="error" style="display: none;" id="error-metavalue"><?php _e('Please provide a value for this meta field','docs'); ?></label>
                    <textarea name="meta_value" id="meta-value" class="rd_large"></textarea>
                </td>
            </tr>
            <tr class="odd">
                <td colspan="2">
                    <input type="button" id="rd-addmeta" value="<?php _e('Add custom field','docs'); ?>" />
                </td>
            </tr>
        </table>
        <label><?php _e('Custom fields can be used to add extra metadata to a post that you can use in your theme.','docs'); ?></label>
        </div>
    </div>
    
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>