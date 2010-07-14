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
    
    /**
    * @desc Genera el arbol de categorías en un array
    * @param array Referencia del Array que se rellenará
    * @param int Id de la Sección padre
    * @param int Contador de sangría
    */
    function getSectionTree(&$array, $parent = 0, $saltos = 0, $resource = 0, $fields='*', $exclude=0){
        global $db;
        $sql = "SELECT $fields FROM ".$db->prefix("pa_sections")." WHERE ".($resource>0 ? "id_res='$resource' AND" : '')."
                parent='$parent' ".($exclude>0 ? "AND id_sec<>'$exclude'" : '')." ORDER BY `order`";
        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            $ret = array();
            $ret = $row;
            $ret['saltos'] = $saltos;
            $array[] = $ret;
            self::getSectionTree($array, $row['id_sec'], $saltos + 1, $resource, $fields, $exclude);
        }
        
        return true;
        
    }
    
    /**
    * Get all references list according to given parameters
    * @param int Resource ID
    * @param string Search keyword
    * @param int Start results
    * @param int Results number limit
    * @return array
    */
    public function references($res=0, $search='', $start=0, $limit=15){
        
        $db = Database::getInstance();
        
        $sql="SELECT COUNT(*) FROM ".$db->prefix('pa_references').($res>0 ? " WHERE id_res='$res'" : '');
        
        if ($search!='')
            $sql .= ($res>0 ? " AND " : " WHERE ")." (title LIKE '%$k%' OR text LIKE '%$k%')";
            
        if ($res>0) $reso = new RDResource($res);
        
        list($num) = $db->fetchRow($db->query($sql));
        $limit = $limit<=0 ? 15 : $limit;

        //Fin de navegador de páginas    
        $sql = str_replace("COUNT(*)","*", $sql);
        $sql .= " LIMIT $start,$limit";
        
        $result=$db->query($sql);
        $references = array();
        while ($rows=$db->fetchArray($result)){
            $ref= new RDResource();
            $ref->assignVars($rows);

            if($res<=0) $reso=new RDResource($ref->resource());
        
            $references[] = array('id'=>$ref->id(),'title'=>$ref->title(),'text'=>substr($util->filterTags($ref->reference()),0,50)."...",
                    'resource'=>$res->title());
        
        }
        
        return $references;

    }
    
}
