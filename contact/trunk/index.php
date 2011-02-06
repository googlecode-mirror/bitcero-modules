<?php
// $Id$
// --------------------------------------------------------------
// Contact
// A simple contact module for Xoops
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include '../../mainfile.php';


function show_form(){
    global $xoopsOption, $xoopsConfig, $xoopsUser, $xoopsModule, $xoopsModuleConfig;
    
    $xoopsOption['template_main'] = "contact_form.html";
    include '../../header.php';
    
    RMTemplate::get()->add_style('main.css', 'contact');
    $xoopsTpl->assign('contact_info', $xoopsModuleConfig['information']);
    $xoopsTpl->assign('lang_name', __('Your name:','contact'));
    $xoopsTpl->assign('lang_email', __('Your email address:','contact'));
    $xoopsTpl->assign('lang_business', __('Your company:','contact'));
    $xoopsTpl->assign('lang_phone', __('Your phone:','contact'));
    $xoopsTpl->assign('lang_subject', __('Asunto:','contact'));
    $xoopsTpl->assign('lang_message', __('Message:','contact'));
    $xoopsTpl->assign('lang_sendnow', __('Send Message','contact'));
    $xoopsTpl->assign('lang_required', __('Fields marked with * are required.','contact'));
    
    RMTemplate::get()->add_local_script('jquery.validate.min.js', 'rmcommon', 'include');
    
    $captcha = RMEvents::get()->run_event('rmcommon.recaptcha.field');
    if($captcha!=''){
        $xoopsTpl->assign('captcha', $captcha);
        $xoopsTpl->assign('lang_captcha', __('Write next words to verify that you are human','contact'));
    }

    include '../../footer.php';
}


$action = rmc_server_var($_POST, 'action', '');

switch($action){
    default:
        show_form();
        break;
}