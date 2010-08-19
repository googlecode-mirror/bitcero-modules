<h1><?php $edit ? _e('Edit Page','docs') : _e('New Page','docs'); ?></h1>

<div class="form_container">
<form name="frmPage" id="frm-page" method="post" action="<?php echo RMFunctions::current_url(); ?>">
<label><?php _e('Resource','docs'); ?></label>
<span><?php echo $res->getVar('title'); ?></span>
<label for="sec-title"><?php _e('Title','docs'); ?></label>
<input type="text" name="title" id="sec-title" value="<?php echo $edit ? $section->getVar('title') : ''; ?>" />
<?php echo $editor->render(); ?>
<label for="sec-parent"><?php _e('Parent','docs'); ?></label>
<select name="parent" id="sec-parent">
    <option value=""<?php echo $edit && $section->getVar('parent')<=0 ? ' selected="selected"' : ''; ?>><?php _e('Select parent...','docs'); ?></option>
    <?php foreach($sections as $sec): ?>
    <option value="<?php echo $sec['id']; ?>"<?php echo $edit && $section->getVar('parent')==$sec['id'] ? ' selected="selected"' : ''; ?>><?php echo str_repeat('&#151;', $sec['jump']); echo $sec['title']; ?></option>
    <?php endforeach; ?>
</select>
<label for="sec-order"><?php _e('Display order','docs'); ?></label>
<input type="text" name="order" value="<?php echo $edit ? $section->getVar('order') : ''; ?>" />
<input type="submit" value="<?php $edit ? _e('Save Changes','docs') : _e('Save Page','docs'); ?>" />
<input type="button" value="<?php _e('Cancel','docs'); ?>" onclick="history.go(-1);" />
</form>
</div>