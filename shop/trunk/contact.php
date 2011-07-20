<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$product = new ShopProduct($contact);

if($product->isNew()){
    ShopFunctions::error404();
}

$action = rmc_server_var($_POST, 'action', '');

if($action=='send'){
    
    $url = ShopFunctions::get_url();
    $url .= $xoopsModuleConfig['urlmode'] ? 'contact/'.$product->getVar('nameid').'/' : '?contact='.$product->id();
    
    $name = rmc_server_var($_POST, 'name', '');
    $email = rmc_server_var($_POST, 'email', '');
    $message = rmc_server_var($_POST, 'message', '');
    
    if($name=='' || $message=='' || $email==''){
        redirect_header($url, 1, __('Please fill al required data!','shop'));
        die();
    }
    
    // Recaptcha check
    if (!RMEvents::get()->run_event('rmcommon.captcha.check', true)){
        redirect_header($url, 1, __('Please check the security words and write it correctly!','shop'));
        die();
    }
    
    $xoopsMailer =& getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setBody(sprintf(__('A user has requested more information about the product %s. User message below.', 'shop'), $product->getVar('name'))."\n---------------\n".$message."\n--------------\n".__('Message sent with MiniShop','shop')."\n".ShopFunctions::get_url());
    $xoopsMailer->setToEmails($xoopsModuleConfig['email']);
    $xoopsMailer->setFromEmail($email);
    $xoopsMailer->setFromName($name);
    $xoopsMailer->setSubject(sprintf(__('Information request about product %s','shop'), $product->getVar('name')));
    if (!$xoopsMailer->send(true)){
        redirect_header($url, 1, __('Message could not be delivered. Please try again.','shop'));
        die();
    }
        
    redirect_header(ShopFunctions::get_url(), 1, __('Your message has been sent successfully!','shop'));
    
    
} else {
    
    $xoopsOption['template_main'] = 'shop_contact.html';
    include 'header.php';

    $tf = new RMTimeFormatter(0, '%d%/%M%/%Y%');

    // Product data
    $xoopsTpl->assign('product', array(
        'name' => $product->getVar('name'),
        'description' => $product->getVar('description'),
        'price' => sprintf(__('Price: <strong>%s</strong>','shop'), sprintf($xoopsModuleConfig['format'], number_format($product->getVar('price'), 2))),
        'type' => $product->getVar('type'),
        'stock' => $product->getVar('available'),
        'image' => $product->getVar('image'),
        'created' => sprintf(__('Since: <strong>%s</strong>','shop'), $tf->format($product->getVar('created'))),
        'updated' => $product->getVar('modified')>0?sprintf(__("Updated: <strong>%s</strong>",'shop'), $tf->format($product->getVar('modified'))):'',
        'link' => $product->permalink(),
        'metas' => $product->get_meta(),
        'images' => $product->get_images()
    ));

    $categories = array();
    ShopFunctions::categos_list($categories);

    array_walk($categories, 'shop_dashed');

    $xoopsTpl->assign('categories_list', $categories);
    $xoopsTpl->assign('lang_selcat', __('Select category...','shop'));
    $xoopsTpl->assign('xoops_pagetitle', sprintf(__('Requesting information about %s', 'shop'), $product->getVar('name')).' &raquo; '.$xoopsModuleConfig['modtitle']);
    $xoopsTpl->assign('lang_requesting_info', sprintf(__('Request Information for %s', 'shop'), $product->getVar('name')));

    $xoopsTpl->assign('lang_yname', __('Your Name:','shop'));
    $xoopsTpl->assign('lang_ymail', __('Your Email:','shop'));
    $xoopsTpl->assign('lang_prod', __('Product:','shop'));
    $xoopsTpl->assign('lang_message', __('Message:','shop'));
    $xoopsTpl->assign('lang_send', __('Send Message','shop'));
    $xoopsTpl->assign('form_action', RMFunctions::current_url());

    if($xoopsUser){
        $xoopsTpl->assign('shop_name', $xoopsUser->name());
        $xoopsTpl->assign('shop_email', $xoopsUser->email());
    }

    $captcha = RMEvents::get()->run_event('rmcommon.recaptcha.field');
    $xoopsTpl->assign('captcha', $captcha);

    RMTemplate::get()->add_style('main.css', 'shop');

    include 'footer.php';
}
