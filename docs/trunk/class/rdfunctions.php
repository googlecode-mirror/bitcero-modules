<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDFunctions
{
	public function toolbar(){
		RMTemplate::get()->add_tool(__('Dashboard','docs'), './index.php', '../images/dashboard.png', 'dashboard');
		RMTemplate::get()->add_tool(__('Resources','docs'), './resources.php', '../images/book.png', 'resources');
		RMTemplate::get()->add_tool(__('Sections','docs'), './sections.php', '../images/section.png', 'sections');
		RMTemplate::get()->add_tool(__('Notes','docs'), './notes.php', '../images/notes.png', 'notes');
		RMTemplate::get()->add_tool(__('Figures','docs'), './figures.php', '../images/figures.png', 'figures');
	}
    
    /**
    * @desc Envía correo de aprobación de publicación
    * @param Object $res Publicación
    **/
    function mail_approved(&$res){
        
        global $xoopsModuleConfig,$xoopsConfig;

        $errors='';
        $user=new XoopsUser($res->getVar('owner'));
        $member_handler =& xoops_gethandler('member');
        $xoopsMailer =& getMailer();
        $method=$user->getVar('notify_method');
        switch ($method){
            case '1':
                $xoopsMailer->usePM();
                $config_handler =& xoops_gethandler('config');
                $xoopsMailerConfig =& $config_handler->getConfigsByCat(XOOPS_CONF_MAILER);
                $xoopsMailer->setFromUser($member_handler->getUser($xoopsMailerConfig['fromuid']));
            
            break;
            case '2':
                $xoopsMailer->useMail();
            break;
        }
        $xoopsMailer->setTemplate('user_approv_resource.tpl');
        if ($xoopsModuleConfig['access']){
            $xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/docs/resource/".$res->id()."/".$res->getVar('nameid'));
        }else{
            $xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/docs/resources.php?id=".$res->id());
        }
                    
        $xoopsMailer->assign('NAME_RESOURCE',$res->getVar('title'));
        $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/docs/language/".$xoopsConfig['language']."/mail_template/");
        $xoopsMailer->setToUsers($user);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject(sprintf(__('Publication %s approved', 'docs'),$res->getVar('title')));
        if (!$xoopsMailer->send(true)){
            $errors.=$xoopsMailer->getErrors();
        }
            
        return $errors;


    }
    
}
