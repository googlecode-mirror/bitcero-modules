<?php
// $Id: widget_publish.php 50 2009-09-17 20:36:31Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Publish widget
* @return array
*/
function mw_widget_publish(){
	global $xoopsUser;
	
	RMTemplate::get()->add_style('publish_widget.css','mywords');
	RMTemplate::get()->add_style('forms.css','rmcommon');
	RMTemplate::get()->add_style('jquery.css','rmcommon');
	RMTemplate::get()->add_script('../include/js/scripts.php?file=posts.js');
	RMTemplate::get()->add_script('../include/js/mktime.js');
	RMTemplate::get()->add_script(RMCURL.'/include/js/forms.js');
	$widget['title'] = __('Publish','admin_mywords');
	$widget['icon']	 = '';
    
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
    $edit = false;
    if ($id>0){
        $post = new MWPost($id);
        if ($post->isNew()){
            unset($post);
        } else {
            $edit = true;
        }
    }
    
    if ($edit){
        
        switch($post->getVar('status')){
			case 'draft':
				$status = __('Draft','admin_mywords');
				break;
			case 'pending':
				$status =  __('Pending review','admin_mywords');
				break;
			case 'publish':
				$status =  __('Published','admin_mywords');
				break;
			case 'scheduled':
				$status =  __('Scheduled','admin_mywords');
				break;
        }
        $visibility = $post->getVar('visibility')=='public' ? 'Public' : ($post->getVar('visibility')=='password' ? 'Password Protected' : 'Private');
        
    } else {
        $status = 'Draft';
        $visibility = 'Public';
    }
    
	ob_start();
?>
<div class="rmc_widget_content_reduced publish_container">
<form id="mw-post-publish-form">
<div style="overflow: hidden;">
<?php if (!$edit || $post->getVar('status')=='draft'): ?><div class="save_how"><input type="submit" id="saveas" name="saveas" value="<?php _e('Save as draft','adin_mywords'); ?>" class="button bold" /></div><?php endif; ?>
<div class="preview_button"><input type="button" id="saveas" value="<?php _e($edit ? 'Preview Changes' : 'Preview','admin_mywords'); ?>" class="button" /></div>
</div>
<br />
<!-- Opciones de Publicación -->
<div class="publish_options">
<?php _e('Status:','admin_mywords'); ?> <strong id="publish-status-legend"><?php _e($status,'admin_mywords'); ?></strong> &nbsp; <a href="#" id="edit-publish"><?php _e('Edit','admin_mywords'); ?></a>
	<div id="publish-options" style="display: none;">
		<select name="status" id="status">
            <?php if($edit): ?>
            <option value="publish"<?php echo $edit && $post->getVar('status')=='publish' ? 'selected="selected"' : ''?>><?php _e('Published','admin_mywords') ?></option>
            <?php endif; ?>
			<option value="draft"<?php echo $edit && $post->getVar('status')=='draft' ? 'selected="selected"' : ''?>><?php _e('Draft','admin_mywords') ?></option>
			<option value="pending"<?php echo $edit && $post->getVar('status')=='pending' ? 'selected="selected"' : ''?>><?php _e('Pending Review','admin_mywords') ?></option>
		</select>
		<input type="button" name="publish-ok" id="publish-ok" class="button" value="<?php _e('Apply','admin_mywords') ?>" /><br />
		<a href="#" onclick="$('#publish-options').slideUp('slow'); $('#edit-publish').show();"><?php _e('Cancel','admin_mywords') ?></a>
	</div>
</div>
<!-- //Opciones de Publicación -->
<!-- Visibilidad -->
<div class="publish_options">
<?php _e('Visibility:','admin_mywords'); ?> <strong id="visibility-caption"><?php _e($visibility,'admin_mywords'); ?></strong> &nbsp; <a href="#" id="visibility-edit"><?php _e('Edit','admin_mywords'); ?></a>
<?php
    if (!$edit){
        $visibility = 'public';
    } else {
        $visibility = $post->getVar('status')=='private' ? 'private' : ($post->getVar('status')=='publish' && $post->getVar('password')!='' ? 'password' : 'public');
    }
?>
    <div id="visibility-options">
        <input type="radio" name="visibility" value="public" id="visibility-public"<?php echo $visibility=='public' ? ' checked="checked"' : ''; ?> /> <label for="visibility-public"><?php _e('Public','admin_mywords'); ?></label><br />
        <input type="radio" name="visibility" value="password" id="visibility-password"<?php echo $visibility=='password' ? ' checked="checked"' : ''; ?> /> <label for="visibility-password"><?php _e('Password protected','admin_mywords'); ?></label><br />
        <span id="vis-password-text" style="<?php _e($visibility=='password' ? '' : 'display: none') ?>">
            <label>
            <?php _e('Password:','admin_mywords') ?>
            <input type="text" name="vis_password" id="vis-password" value="" class="options_input" />
            </label>
        <br /></span>
        <input type="radio" name="visibility" value="private" id="visibility-private"<?php echo $visibility=='private' ? ' checked="checked"' : ''; ?> /> <label for="visibility-private"><?php _e('Private','admin_mywords') ?></label><br /><br />
        <input type="button" name="vis-button" id="vis-button" value="<?php _e('Apply','admin_mywords') ?>" class="button" />
        <a href="#" id="vis-cancel"><?php _e('Cancel','admin_mywords') ?></a>
    </div>
</div>
<!-- /Visibilidad -->
<!-- Schedule -->
<div class="publish_options">
<?php _e('Publish','admin_mywords'); ?> <strong id="schedule-caption"><?php echo $edit ? ($post->getVar('pubdate')>0?__('Inmediatly','admin_mywords'):date("d, M Y \@ H:i", $post->getVar('schedule'))) : __('Inmediatly','admin_mywords'); ?></strong> &nbsp; <a href="#" class="edit-schedule"><?php _e('Edit','admin_mywords'); ?></a>
    <div class="schedule-options" style="display: none;">
        <?php
            // Determinamos la fecha correcta
            $time = $edit ? ($post->getVar('pubdate')>0?$post->getVar('pubdate'):$post->getVar('schedule')) : time();
            $day = date("d", $time);
            $month = date("n", $time);
            $year = date("Y", $time);
            $hour = date("H", $time);
            $minute = date("i", $time);
            $months = array(
            	__('Jan','admin_mywords'),
            	__('Feb','admin_mywords'),
            	__('Mar','admin_mywords'),
            	__('Apr','admin_mywords'),
            	__('May','admin_mywords'),
            	__('Jun','admin_mywords'),
            	__('Jul','admin_mywords'),
            	__('Aug','admin_mywords'),
            	__('Sep','admin_mywords'),
            	__('Oct','admin_mywords'),
            	__('Nov','admin_mywords'),
            	__('Dec','admin_mywords'),
            	__('admin_mywords')
            );
        ?>
        <input type="text" name="schedule_day" id="schedule-day" size="2" maxlength="2" value="<?php _e($day) ?>" />
        <select name="schedule_month" id="schedule-month">
            <?php for($i=1;$i<=12;$i++){ ?>
                <option value="<?php _e($i) ?>" "<?php if ($month==$i) _e('selected="selected"') ?>"><?php _e($months[$i-1]) ?></option>
            <?php } ?>
        </select>
        <input type="text" name="schedule_year" id="schedule-year" size="2" maxlength="4" value="<?php _e($year) ?>" /> @
        <input type="text" name="schedule_hour" id="schedule-hour" size="2" maxlength="2" value="<?php _e($hour) ?>" /> :
        <input type="text" name="schedule_minute" id="schedule-minute" size="2" maxlength="2" value="<?php _e($minute) ?>" /><br /><br />
        <input type="button" class="button" name="schedule-ok" id="schedule-ok" value="<?php _e('Apply','admin_mywords') ?>" />
        <a href="#" class="schedule-cancel"><?php _e('Cancel','admin_mywords') ?></a>
        <input type="hidden" name="schedule" id="schedule" value="<?php _e("$day-$month-$year-$hour-$minute") ?>" />
    </div>
</div>
<!-- /Shedule -->
<div class="publish_options no_border">
<?php _e('Author:','admin_mywords'); ?>
<?php 
	$user = new RMFormUser('', 'author', 0, $edit ? array($post->getVar('author')) : array($xoopsUser->uid()));
	echo $user->render();
?>
</div>
<div class="widget_button">


<input type="submit" value="<?php _e($edit ? 'Update Post' : 'Publish','admin_mywords'); ?>" class="button default" id="publish-submit" />
</div>


</form>
</div>
<?php
	$widget['content'] = ob_get_clean();
	return $widget;
}
