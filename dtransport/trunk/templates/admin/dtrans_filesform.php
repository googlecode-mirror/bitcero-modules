<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('New file in %s','dtransport'), $sw->getVar('name')); ?></h1>

<div class="dt_table">
    <div class="dt_row">
        <div class="dt_cell dt_preview">
            <div id="dtfiles-preview"<?php echo $edit&&$file_exists&&!$fl->remote() ? ' style="display: block"' : ''; ?>>
                <div class="image"></div>
                <span class="delete button buttonGray"><?php _e('Delete File','dtransport'); ?></span>
                <span class="name"><?php echo $edit ? $fl->file() : ''; ?></span>
                <span class="size"><?php echo $edit ? $rmu->formatBytesSize($fl->size()) : ''; ?></span>
                <span class="type"><?php echo $edit ? $fl->mime() : ''; ?></span>
                <span class="secure"><?php $sw->getVar('secure')?_e('Protected Download','dtransport'):_e('Normal Download','dtransport'); ?></span>
            </div>
            <div id="dtfiles-uploader"<?php echo $edit ? ' style="display: none;"' : ''; ?>>

            </div>
            <div class="dt-errors"></div>
        </div>

        <div id="files-editor" class="dt_cell">
            <h3><?php _e('File Details','dtransport'); ?></h3>
            <div class="descriptions"><?php _e('Fill the next fields to create the new file.','dtransport'); ?></div>
            <label><input type="checkbox" name="remote" value="1" id="remote"<?php echo $edit&&$fl->remote()?' checked="checked"':''; ?> /> <?php _e('Remote file','dtransport'); ?></label>
            <label><?php _e('Title:','dtransport'); ?></label>
            <input type="text" id="title" name="title" value="<?php echo $edit ? $fl->title() : ''; ?>" size="50" />
            <label><?php _e('Group:','dtransport'); ?></label>
            <select name="group" id="group">
                <option value="0"<?php if($edit): ?><?php echo $fl->group()==0?' selected="selected"':''; ?><?php else: ?> selected='selected'<?php endif; ?>><?php _e('Select group...','dtransport'); ?></option>
                <?php foreach($groups as $g): ?>
                <option value="<?php echo $g['id']; ?>"<?php echo $edit&&$fl->group()==$g['id']?' selected="selected"':''; ?>><?php echo $g['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="url-container"<?php echo $edit&&$fl->remote() ? ' style="display: block;"': ''; ?>>
                <label><?php _e('File URL:','dtransport'); ?></label>
                <input type="text" name="url" id="url" value="<?php echo $edit&&$fl->remote() ? $fl->file():''; ?>" size="50" />
            </div>
            <label><input type="checkbox" name="default" id="default" value="1"<?php echo $edit&&$fl->isDefault()?' checked="checked"':''; ?> /> <?php _e('This is the default file','dtransport'); ?></label>
            <input type="button" id="save-data" value="<?php $edit ? _e('Save Changes','dtransport') : _e('Save File','dtransport'); ?>" class="buttonGreen" />
            <input type="button" id="cancel-data" value="<?php _e('Cancel','dtransport'); ?>" />
            <input type="hidden" name="XOOPS_TOKEN_REQUEST" id="XOOPS_TOKEN_REQUEST" value="<?php echo $token; ?>" />
            <input type="hidden" name="secure" value="<?php echo $sw->getVar('secure'); ?>" id="secure" />
            <input type="hidden" name="item" value="<?php echo $sw->id(); ?>" id="item" />
            <input type="hidden" name="size" value="<?php echo $edit ? $fl->size() : ''; ?>" id="size" />
            <input type="hidden" name="action" value="<?php echo $edit?'save-edit':'save-file'; ?>" id="action" />
            <?php if($edit): ?>
            <input type="hidden" name="id" value="<?php echo $fl->id(); ?>" id="id" />
            <?php endif; ?>
            <input type="hidden" name="identifier" id="identifier" value="<?php echo $tc->encrypt(session_id().'|'.$xoopsUser->uid()); ?>"
        </div>
    </div>
</div>
<div id="status-bar">
    <?php _e('Applying changes, please wait a second...','dtransport'); ?>
</div>