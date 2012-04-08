<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
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
    
    $action = rmc_server_var($_GET, 'action', '');
    if($action=='edit')
        $sw = new DTSoftware(rmc_server_var($_GET, 'id', 0));
    else
        $sw = new DTSoftware();
        
    // Featured download
    $field = new RMFormYesNo('','mark',$edit ? $sw->mark() : 1);
    $featured = $field->render();
    
    // Descarga segura
    $field = new RMFormYesno('','secure',$edit ? $sw->secure() : 0);
    $secure = $field->render();
    
    // Approved
    $field = new RMFormYesNo('','approved',$edit ? $sw->approved() : 1);
    $approved = $field->render();
    
    unset($field);
    
    ob_start();
    ?>
    <div class="widgets_forms">
    <form name="frmInfo" id="frm-information" method="post" action="items.php">
        <div class="item">
            <label for="version"><?php _e('Current Version','dtransport'); ?></label><br />
            <input type="text" name="version" id="version" value="<?php echo $edit ? $sw->version() : ''; ?>" size="20" class="required fullwidth" />
            <span class="description"><?php _e('Indicate the current version of this item.','dtransport'); ?></span>
        </div>
        <div class="item">
            <label for="limits"><?php _e('Downloads limit per user','dtransport'); ?></label><br />
            <input type="text" name="limits" id="limits" value="<?php echo $edit ? $sw->limits() : '0'; ?>" size="20" class="required fullwidth" />
            <span class="description"><?php _e('Users could download this item only this times. Leave 0 for unlimited times.','dtransport'); ?></span>
        </div>
        <div class="item">
            <label for="langs"><?php _e('Available languages','dtransport'); ?></label><br />
            <input type="text" name="langs" id="langs" value="<?php echo $edit ? $sw->langs() : 'English'; ?>" size="20" class="required fullwidth" />
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
                            <option value="<?php echo $i; ?>"<?php echo $sw->rate()==$i?' selected="selected"':''; ?>><?php echo $i; ?></option>
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
                        <label for="secure"><?php _e('Secure:','dtransport'); ?></label>
                    </div>
                    <div class="dt_cell">
                        <?php echo $secure; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    <?php
    $widget['content'] = ob_get_clean();
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
    
    // Alerta
    $field = new RMFormYesNo('','alert',$edit ? ($type=='edit' ? ($fields['alert']['limit'] ? 1 : 0) : ($sw->alert() ? 1 : 0)) : 0);
    $enable_alert = $field->render();
    
    unset($field);
    ob_start();
    ?>
    <div id="tab-alert" class="widgets_forms">
    <form name="frmInfo" id="frm-information" method="post" action="items.php">
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
    
    $field = new RMFormUser('', 'user', 0,$edit?array($sw->uid()):$xoopsUser->uid(), 50);
    $user = $field->render();
    unset($field);
    
    ob_start();
    ?>
    <div id="tab-credits" class="widgets_forms">
        <div class="item">
            <label><?php _e('Published by','dtransport'); ?></label>
            <?php echo $user; ?>
        </div>
        <div class="item">
            <label for="author"><?php _e('Author name','dtransport'); ?></label>
            <input type="text" name="author" id="author" value="<?php echo $edit ? $sw->author() : ''; ?>" class="fullwidth" />
        </div>
        <div class="item">
            <label for="url"><?php _e('Author URL','dtransport'); ?></label>
            <input type="text" name="url" id="url" value="<?php echo $edit ? $sw->url() : ''; ?>" class="fullwidth" />
        </div>
        <div class="item">
            <label for="email"><?php _e('Author Email','dtransport'); ?></label>
            <input type="text" name="email" id="email" value="<?php echo $edit ? $sw->email() : $xoopsUser->email(); ?>" class="fullwidth" />
            <span class="description"><?php _e('This email will not be visible for users','dtransport'); ?></span>
        </div>
        <div class="item">
            <input type="checkbox" name="contact" id="contact" value="1" /> <?php _e('Author can be contacted','dtransport'); ?>
        </div>
    </div>
    <?php
    $content = ob_get_clean();
    return $content;
    
}