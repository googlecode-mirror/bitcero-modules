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
    $xoopsTpl->assign('lang_subject', __('Subject:','contact'));
    $xoopsTpl->assign('lang_message', __('Message:','contact'));
    $xoopsTpl->assign('lang_sendnow', __('Send Message','contact'));
    $xoopsTpl->assign('lang_required', __('Fields marked with * are required.','contact'));
    
    RMTemplate::get()->add_local_script('jquery.validate.min.js', 'rmcommon', 'include');
    RMTemplate::get()->add_local_script('contact.js', 'contact');
    
    $captcha = RMEvents::get()->run_event('rmcommon.recaptcha.field');
    if($captcha!=''){
        $xoopsTpl->assign('captcha', $captcha);
        $xoopsTpl->assign('lang_captcha', __('Write next words to verify that you are human','contact'));
    }

    include '../../footer.php';
}


function send_message(){
	global $xoopsModule, $xoopsModuleConfig, $xoopsUser;
	
	$name = rmc_server_var($_POST, 'name', '');
    $email = rmc_server_var($_POST, 'email', '');
    $company = rmc_server_var($_POST, 'company', '');
    $phone = rmc_server_var($_POST, 'phone', '');
    $subject = rmc_server_var($_POST, 'subject', '');
    $message = rmc_server_var($_POST, 'message', '');
    
    if($name=='' || $email=='' || !checkEmail($email) || $subject=='' || $message==''){
        redirect_header($xoopsModuleConfig['url'], 1, __('Please fill all required fileds before to send this message!', 'contact'));
        die();
    }
	
    // Recaptcha check
    if (!RMEvents::get()->run_event('rmcommon.captcha.check', true)){
        redirect_header($xoopsModuleConfig['url'], 1, __('Please check the security words and write it correctly!','contact'));
        die();
    }
    
    $xoopsMailer =& getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setBody($message."\n--------------\n".__('Message sent with ContactMe!','contact')."\n".$xoopsModuleConfig['url']);
    $xoopsMailer->setToEmails($xoopsModuleConfig['mail']);
    $xoopsMailer->setFromEmail($email);
    $xoopsMailer->setFromName($name);
    $xoopsMailer->setSubject($subject);
    if (!$xoopsMailer->send(true)){
        redirect_header($xoopsModuleConfig['url'], 1, __('Message could not be delivered. Please try again.','contact'));
        die();
    }
    
    // Save message on database for further use
    $msg = new CTMessage();
    $msg->setVar('subject', $subject);
    $msg->setVar('ip', $_SERVER['REMOTE_ADDR']);
    $msg->setVar('email', $email);
    $msg->setVar('name', $name);
    $msg->setVar('org', $company);
    $msg->setVar('body', $message);
    $msg->setVar('phone', $phone);
    $msg->setVar('register', $xoopsUser ? 1 : 0);
    if($xoopsUser) $msg->setVar('xuid', $xoopsUser->uid());
    $msg->setVar('date', time());
    
    $msg->save();
        
    redirect_header(XOOPS_URL, 1, __('Your message has been sent successfully!','contact'));
    
}


$action = rmc_server_var($_POST, 'action', '');

switch($action){
    case 'send':
        send_message();
        break;
    default:
        show_form();
        break;
}