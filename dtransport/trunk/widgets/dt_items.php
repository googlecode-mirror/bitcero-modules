<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Este widget muestra las categorias disponibles y permite
* agregar la descarga a una o varias de ellas
*/
function dt_widget_information($edit=0){
    
    $widget['title'] = __('Download Information','dtransport');
    $widget['icon'] = '../images/item.png';
    
    if($edit)
        $sw = new DTSoftware(rmc_server_var($_GET, 'id', 0));
    else
        $sw = new DTSoftware();
        
    // Featured download
    $field = new RMFormYesNo('','mark',$edit ? $sw->getVar('featured') : 1);
    $featured = $field->render();
    
    // Descarga segura
    $field = new RMFormYesno('','secure',$edit ? $sw->getVar('secure') : 0);
    $secure = $field->render();
    
    // Approved
    $field = new RMFormYesNo('','approved',$edit ? $sw->getVar('approved') : 1);
    $approved = $field->render();
    
    unset($field);
    
    ob_start();
    ?>
    <div class="widgets_forms">
    <form name="frmInfo" id="frm-information" method="post" action="items.php">
        <div class="item">
            <label for="version"><?php _e('Current Version','dtransport'); ?></label><br />
            <input type="text" name="version" id="version" value="<?php echo $edit ? $sw->getVar('version') : ''; ?>" size="20" class="required fullwidth" />
            <span class="description"><?php _e('Indicate the current version of this item.','dtransport'); ?></span>
        </div>
        <div class="item">
            <label for="limits"><?php _e('Downloads limit per user','dtransport'); ?></label><br />
            <input type="text" name="limits" id="limits" value="<?php echo $edit ? $sw->getVar('limits') : '0'; ?>" size="20" class="required fullwidth" />
            <span class="description"><?php _e('Users could download this item only this times. Leave 0 for unlimited times.','dtransport'); ?></span>
        </div>
        <div class="item">
            <label for="langs"><?php _e('Available languages','dtransport'); ?></label><br />
            <input type="text" name="langs" id="langs" value="<?php echo $edit ? $sw->getVar('langs') : 'English'; ?>" size="20" class="required fullwidth" />
            <span class="description"><?php _e('Specify every language separated by comma.','dtransport'); ?></span>
        </div>
        <div class="item">
            <div class="dt_table">
                <div class="dt_row">
                    <div class="dt_cell">
                        <label for="siterate"><?php echo _e('Site Rating:','dtransport'); ?></label>
                    </div>
                    <div class="dt_cell">
                        <select name="siterate" id="siterate" class="required fullwidth">
                            <?php for($i=0;$i<=10;$i++): ?>
                            <option value="<?php echo $i; ?>"<?php echo $sw->getVar('siterate')==$i?' selected="selected"':''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="dt_row">
                    <div class="dt_cell">
                        <label for="approved"><?php _e('Approved:','dtransport'); ?></label>
                    </div>
                    <div class="dt_cell">
                        <?php echo $approved; ?>
                    </div>
                </div>
                <div class="dt_row">
                    <div class="dt_cell">
                        <label for="mark"><?php _e('Featured:','dtransport'); ?></label>
                    </div>
                    <div class="dt_cell">
                        <?php echo $featured; ?>
                    </div>
                </div>
                <div class="dt_row">
                    <div class="dt_cell">
                        <label for="secure"><?php _e('Protected:','dtransport'); ?></label>
                    </div>
                    <div class="dt_cell">
                        <?php echo $secure; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <label for="password"><?php _e('Download password','dtransport'); ?></label><br />
            <input type="password" name="password" id="password" value="<?php echo $edit ? $sw->getVar('password') : ''; ?>" size="20" class="required fullwidth" />
            <span class="description"><?php _e('If you specify a password for this item, users must know it in order to download files belonging to it.','dtransport'); ?></span>
            <span class="description"><?php _e('If a password is provided for this item, the protected status will set to on automatically.','dtransport'); ?></span>
        </div>
    </form>
    </div>
    <?php
    $widget['content'] = ob_get_clean();
    return $widget;
    
}

/**
 * Show fields for default image
 */
function dt_widget_defimg($edit = 0){

    $id     = intval(rmc_server_var($_REQUEST, 'id', 0));
    $type   = rmc_server_var($_REQUEST, 'type', '');
    $action   = rmc_server_var($_REQUEST, 'action', '');

    $widget = array();
    $widget['title'] = __('Default Image','dtransport');
    $widget['icon'] = '../images/shots.png';
    $util = new RMUtilities();

    if ($edit){
        //Verificamos que el software sea válido
        if ($id<=0)
            $params = '';

        //Verificamos que el software exista
        if ($type=='edit')
            $sw = new DTSoftwareEdited($id);
        else
            $sw=new DTSoftware($id);

        if ($sw->isNew())
            $params = '';
        else
            $params = $sw->getVar('image');

    } else {
        $params = '';
    }

    $widget['content'] = '<form name="frmDefimage" id="frm-defimage" method="post">';
    $widget['content'] .= $util->image_manager('image', $params);
    $widget['content'] .= '</form>';
    return $widget;

}

/**
 * Muestra las opciones adicionales para la descarga creada
 */
function dt_widget_options($edit = 0){
    
    $widget['title'] = __('Download Options','dtransport');
    $widget['icon'] = '../images/options.png';
    
    ob_start();
    ?>
    <div id="dt-down-opts">
        <ul>
            <li><a href="#tab-alert" class="selected" style="background-image: url(../images/alert.png);">Alerts</a></li>
            <li><a href="#tab-credits" style="background-image: url(../images/author.png);">Author</a></li>
        </ul>
        <?php echo dt_widget_alert($edit); ?>
        <?php echo dt_widget_credits($edit); ?>
    </div>
    <?php
    $widget['content'] = ob_get_clean();
    
    return $widget;
    
}

/**
* Presenta las opciones para configurar la alerta
*/
function dt_widget_alert($edit=0){
    
    //$widget['title'] = __('Inactivity Alert','dtransport');
    //$widget['icon'] = '../images/alert.png';
    if($edit)
        $sw = new DTSoftware(rmc_server_var($_GET, 'id', 0));
    else
        $sw = new DTSoftware();
    
    // Alerta
    $field = new RMFormYesNo('','alert',$edit ? ($type=='edit' ? ($fields['alert']['limit'] ? 1 : 0) : ($sw->alert() ? 1 : 0)) : 0);
    $enable_alert = $field->render();
    
    unset($field);
    ob_start();
    ?>
    <div id="tab-alert" class="widgets_forms">
    <form name="frmAlert" id="frm-alert" method="post" action="items.php">
        <div class="item">
        <div class="dt_table">
            <div class="dt_row">
                <div class="dt_cell">
                    <label for="alert"><?php echo _e('Enable alerts:','dtransport'); ?></label>
                </div>
                <div class="dt_cell">
                    <?php echo $enable_alert; ?>
                </div>
            </div>
        </div>
        </div>
        <div class="item">
            <label for="limitalert"><?php _e('Limit of days','dtransport'); ?></label>
            <input type="text" name="limitalert" id="limitalert" value="<?php echo $edit?$sw->alert()->limit():''; ?>" class="fullwidth" />
            <span class="description"><?php _e('Maximum number of days that an item can be without downloads before to send an alert to author.','dtransport'); ?></span>
        </div>
        <div class="item">
            <label for="mode"><?php _e('Alert mode','dtransport'); ?></label><br />
            <input type="radio" name="mode" id="mode" value="0"<?php echo $edit ? ($sw->alert()->mode()==0?' checked="checked"' : '') : ''; ?> /><?php _e('Private message','dtransport'); ?>
            <input type="radio" name="mode" id="mode1" value="1"<?php echo $edit ? ($sw->alert()->mode()==1?' checked="checked"' : '') : ''; ?> /><?php _e('Email message','dtransport'); ?>
        </div>
    </form>
    </div>
    <?php
    //$widget['content'] = ob_get_clean();
    $content = ob_get_clean();
    return $content;
    
}

/**
* Author information
*/
function dt_widget_credits($edit=0){
    global $xoopsUser;
    
    //$widget['title'] = __('Author Information','dtransport');
    //$widget['icon'] = '../images/author.png';
    if($edit)
        $sw = new DTSoftware(rmc_server_var($_GET, 'id', 0));
    else
        $sw = new DTSoftware();

    
    $field = new RMFormUser('', 'user', 0,$edit?array($sw->getVar('uid')):$xoopsUser->uid(), 50);
    $user = $field->render();
    unset($field);
    
    ob_start();
    ?>
    <form name="frmCredits" id="frm-credits" method="post">
    <div id="tab-credits" class="widgets_forms">
        <div class="item">
            <label><?php _e('Published by','dtransport'); ?></label>
            <?php echo $user; ?>
        </div>
        <div class="item">
            <label for="author"><?php _e('Author name','dtransport'); ?></label>
            <input type="text" name="author" id="author" value="<?php echo $edit ? $sw->getVar('author_name') : ''; ?>" class="fullwidth" />
        </div>
        <div class="item">
            <label for="url"><?php _e('Author URL','dtransport'); ?></label>
            <input type="text" name="url" id="url" value="<?php echo $edit ? $sw->getVar('author_url') : ''; ?>" class="fullwidth" />
        </div>
        <div class="item">
            <label for="email"><?php _e('Author Email','dtransport'); ?></label>
            <input type="text" name="email" id="email" value="<?php echo $edit ? $sw->getVar('author_email') : $xoopsUser->email(); ?>" class="fullwidth" />
            <span class="description"><?php _e('This email will not be visible for users','dtransport'); ?></span>
        </div>
        <div class="item">
            <input type="checkbox" name="contact" id="contact" value="1"<?php echo $sw->getVar('author_contact')?' checked="checked"':''; ?> /> <?php _e('Author can be contacted','dtransport'); ?>
        </div>
    </div>
    </form>
    <?php
    $content = ob_get_clean();
    return $content;
    
}