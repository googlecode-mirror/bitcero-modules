<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!$mc['send_download']){
	redirect_header(DT_URL, 2, __('Operation not allowed!','dtransport'));
	die();
}

$item = new DTSoftware();
//Verificamos si el usuario pertenece a un grupo con permisos de envío de descargas
if (!$item->isAllowedDowns($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS,$mc['groups_send'])){
	redirect_header(DT_URL , 2, __('You have not authorization to create download items','dtransport'));
	die();
}

switch ($action){
    case 'saveedit':
	case 'save':
		
        $edit = $action=='saveedit'? 1 : 0;

        foreach ($_POST as $k=>$v){
            $$k=$v;
        }

        $app=0;
        if ($edit){
            //Verificamos si el elemento es válido
            if ($id<=0)
                redirect_header(DT_URL,2,__('Item not found. Please try again!','dtransport'));

            //Verificamos si el elemento existe
            $item = new DTSoftware($id);
            if ($item->isNew())
                redirect_header(DT_URL,2,__('Item not found. Please try again!','dtransport'));
                      
            //Verificamos si se aprueba la edicion
            if (!$mc['aprove_edit'] && $item->getVar('approved')){
                // Si no se aprueba almacenaremos los datos en
                // la tabla para elementos editados
                $item = new DTSoftwareEdited($id);
                $item->setSoftware($id);
                
            }
            
            if($item->getVar('uid')!=$xoopsUser->uid())
                redirect_header(DT_URL, 1, __('You can not edit this download item!','dtransport'));

        }else{

            $item = new DTSoftware();
        }
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        
        // Check if exists another download with same name
        $sql = "SELECT COUNT(*) FROM ".$db->prefix("dtrans_software")." WHERE name='$name'";
        if($edit) $sql .= " AND id_soft!=".$item->id();
        
        list($num) = $db->fetchRow($db->query($sql));
        if($num>0)
            redirect_header(DT_URL.($mc['permalinks'] ? '/submit/'.($edit ? 'edit/'.$item->id().'/' : '') : '/p=submit'.($edit ? '?action=edit&id='.$id : '')), 1, __('Another item with same name already exists!','dtransport'));
        
        //Genera $nameid Nombre identificador
        $found=false; 
        $i = 0;
        $tc = TextCleaner::getInstance(); // Manejo de texto
        
        if ($name!=$item->getVar('name')){
            do{
                $nameid = $tc->sweetstring($name).($found ? $i : '');
                $sql = "SELECT COUNT(*) FROM ".$db->prefix('dtrans_software'). " WHERE nameid = '$nameid'".($edit?' AND id_soft!='.$id:'');
                list ($num) =$db->fetchRow($db->queryF($sql));
                if ($num>0){
                    $found =true;
                    $i++;
                }else{
                    $found=false;
                }
            }while ($found==true);
            $item->setVar('nameid', $nameid);
        }
            
        $item->setVar('name', $name);
        $item->setVar('shortdesc', $shortdesc);
        $item->setVar('desc', $description);
        $item->setVar('limits', 0);

        if ($xoopsUser){
            $item->setVar('uid', $xoopsUser->uid());
            $item->setVar('approved', $mc['approve_register']);
        }else{
            //Usuarios Anonimos
            $item->setVar('uid', 0);
            $item->setVar('approved', $mc['approve_anonymous']);    
        }
        if ($edit){
            $item->setVar('modified', time());
        }else{
            $item->setVar('created', time());
            $item->setVar('modified', time());
        }
        if ($edit && !$mc['aprove_edit']){
            $item->setVar('created', time());
        }
        
        $item->setVar('secure', $mc['secure_public'] ? $secure : 0);
        $item->setVar('groups', $groups);        
        $item->setCategories($category);
        $item->setVar('version', $version);
        $item->setVar('author_name', $author_name);
        $item->setVar('author_url', formatURL($author_url));
        $item->setVar('author_email', $author_email);
        $item->setVar('author_contact', $author_contact);
        $item->setVar('image', $image);
        $item->setVar('password', $mc['pass_public'] ? $password : '');
        $item->setVar('langs', $langs);
        $item->setTags(explode(',', $tags));        

        //Licencias
        $item->setLicences($licences);
        
        //Plataformas
        $item->setPlatforms($platforms);
        
        if (!$item->save(true)){
            redirect_header(DT_URL.($mc['permalinks'] ? '/submit/'.($edit ? 'edit/'.$item->id().'/' : '') : '/p=submit'.($edit ? '?action=edit&id='.$id : '')),1, __('Download item could not be saved! Please try again.','dtransport'));
            die();
            
        }else{
            
            if (!$edit){
                //Notificamos el envío de descargas
                $xoopsMailer =& getMailer();
                $xoopsMailer->usePM();
                if ($item->getVar('approved')){
                    $xoopsMailer->setTemplate('send_downloadapp.tpl');
                }else{
                    $xoopsMailer->setTemplate('send_downloadnoapp.tpl');
                }
                $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
                $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
                $xoopsMailer->assign('SITEURL', XOOPS_URL."/");
                $xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/dtransport/admin/items.php?op=edit&id=".$item->id());
                $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
                foreach  ($mc['groups_notif'] as $k){
                    $g[]=new XoopsGroup($k);
                }
                $xoopsMailer->setToGroups($g);
                $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                $xoopsMailer->setFromName($xoopsConfig['sitename']);
                $xoopsMailer->setSubject(sprintf(_MS_DT_SUBJECT,$item->getVar('name')));
                if (!$xoopsMailer->send(true)){
                    redirect_header(XOOPS_URL.'/modules/dtransport/mydownloads.php',2,$xoopsMailer->getErrors());
                }
            }
            else{
                if (!$mc['aprove_edit'] && $mc['edit_notif'] && $app){
                    $xoopsMailer =& getMailer();
                    $xoopsMailer->usePM();
                    $xoopsMailer->setTemplate('edit_download.tpl');
                    $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
                    $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
                    $xoopsMailer->assign('SITEURL', XOOPS_URL."/");
                    $xoopsMailer->assign('LINK_RESOURCE',XOOPS_URL."/modules/dtransport/admin/items.php?op=edit&type=edit&id=".$item->software());
                    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
                    foreach  ($mc['groups_notif'] as $k){
                        $g[]=new XoopsGroup($k);
                    }
                    $xoopsMailer->setToGroups($g);
                    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
                    $xoopsMailer->setFromName($xoopsConfig['sitename']);
                    $xoopsMailer->setSubject(sprintf(_MS_DT_SUBJECTEDIT,$item->name()));
                    if (!$xoopsMailer->send(true)){
                        redirect_header(XOOPS_URL.'/modules/dtransport/mydownloads.php',2,$xoopsMailer->getErrors());
                    }
            
                }
            
            
            }    
                
            redirect_header(DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cp'),2,$edit ? __('Changes saved successfully!','dtransport') : __('Download item created successfully'));
        }
        
        
	    break;

    case 'edit':
	default:
        
        $edit = $action=='edit' && $id>0 ? 1 : 0;
        
		// MOSTRAR FORMULARIO
        $xoopsOption['template_main'] = 'dtrans_submit.html';
        $xoopsOption['module_subpage'] = 'submit';
        
        if ($edit){
            //Verificamos si el elemento es válido
            if ($id<=0)
                redirect_header(DT_URL,2,__('Item not found. Please try again!','dtransport'));

            //Verificamos si el elemento existe
            $item = new DTSoftware($id);
            if ($item->isNew())
                redirect_header(DT_URL,2,__('Item not found. Please try again!','dtransport'));
                      
            //Verificamos si se aprueba la edicion
            if (!$mc['aprove_edit'] && $item->getVar('approved')){
                // Si no se aprueba almacenaremos los datos en
                // la tabla para elementos editados
                $item = new DTSoftwareEdited($id);
                $item->setSoftware($id);
                
            }
            
            if($item->getVar('uid')!=$xoopsUser->uid())
                redirect_header(DT_URL, 1, __('You can not edit this download item!','dtransport'));
                    
        }

        include ('header.php');
        $dtfunc->makeHeader();        

        $form=new RMForm($edit ? __('Editing Download Item', 'dtransport') : __('Create New Download Item','dtransport'),'frmsw',$mc['permalinks'] ? DT_URL.'/submit/' : DT_URL.'/?p=submit');
        $form->setExtra("enctype='multipart/form-data'");

        $form->addElement(new RMFormText(__('Download name','dtransport'),'name',50,150,$edit ? $item->getVar('name') : ''),true);
        $form->addElement(new RMFormText(__('Download version','dtransport'), 'version', 10, 50, $edit ? $item->getVar('version') : ''), true);

        //Lista de categorías
        $ele=new RMFormSelect(__('Categories','dtransport'),'category[]', 1, $edit ? $item->categories() : array());
        $ele->addOption(0, __('Select...','dtransport'), $edit ? 0 : 1);
        $categos = array();
        $dtfunc->getCategos($categos, 0, 0, array(), true,1);
        foreach ($categos as $k){
            $cat =& $k['object'];
            $ele->addOption($cat->id(),str_repeat('--', $k['jumps']).' '.$cat->name(),$edit ? (in_array($cat->id(),$item->categories()) ? 1 : 0) : 0);        
        }

        $form->addElement($ele,true,'noselect:0');

        
        $form->addElement(new RMFormEditor(__('Short description','dtransport'),'shortdesc','auto','50px',$edit ? $item->getVar('shortdesc') : '','simple'),true);
        $form->addElement(new RMFormEditor(__('Full description','dtransport'),'description','auto','350px',$edit ? $item->getVar('desc') : ''),true);
        $form->addElement(new RMFormImage(__('Default image','dtransport'),'image', $edit ? $item->getVar('image') : ''));

        if ($edit){
            $tags='';
            foreach ($item->tags(true) as $tag){            
                $tags .= $tags=='' ? $tag->tag() : ", ".$tag->tag();
            }
        }

        $form->addElement(new RMFormText(__('Tags','dtransport'),'tags',50,255,$edit ? $tags : ''))->setDescription(__('Separate each tag with comma (,).','dtransport'));
        
        //Licencias
        $ele=new RMFormSelect(__('Licenses','dtransport'),'licences[]',1,$edit ? ($item->licences() ? $item->licences() : array(0)) : array(0));
        $ele->addOption('0', __('Other','dtransport'));
        $sql="SELECT * FROM ".$db->prefix('dtrans_licences');
        $result=$db->queryF($sql);
        while ($rows=$db->fetchArray($result)){
            $ele->addOption($rows['id_lic'],$rows['name']);
        }

        $form->addElement($ele,true);

        //Plataformas
        $ele=new RMFormSelect(__('Platforms','dtransport'),'platforms[]',1,$edit ? ($item->platforms()  ? $item->platforms() : array(0)): array(0));    
        $ele->addOption('0', __('Other','dtransport'));
        $sql="SELECT * FROM ".$db->prefix('dtrans_platforms');
        $result=$db->queryF($sql);
        while ($rows=$db->fetchArray($result)){
            $ele->addOption($rows['id_platform'],$rows['name']);
        }

        $form->addElement($ele,true);
        
        // Grupos autorizados
        $form->addElement(new RMFormGroups(__('Allowed groups','dtransport'), 'groups', 1, 1, 1, $edit ? $item->getVar('groups') : array(0)));
        
        // Descarga segura
        if($mc['secure_public'])
            $form->addElement(new RMFormYesNo(__('Protected download','dtransport'), 'secure', $edit ? $item->getVar('secure') : 0))->setDescription(__('Protected downloads will be stored in a secure directory and only can not be download directly.','dtransport'));
            
        // Descarga con contraseña
        if($mc['pass_public'])
            $form->addElement(new RMFormText(__('Password for this download','dtransport'), 'password', $edit ? $item->getVar('password') : 0))->setDescription(__('Only users than knows the password will download this item.','dtransport'));

        // Autor e idioma
        $form->addElement(new RMFormSubTitle(__('Author Information','dtransport')));
        $form->addElement(new RMFormText(__('Author name','dtransport'), 'author_name', 50, 150, $edit ? $item->getVar('author_name') : ''), true);
        $form->addElement(new RMFormText(__('Author URL','dtransport'), 'author_url', 50, 255, $edit ? $item->getVar('author_url') : ''), true);
        $form->addElement(new RMFormText(__('Author email','dtransport'), 'author_email', 50, 255, $edit ? $item->getVar('author_email') : ''), true);
        $form->addElement(new RMFormYesNo(__('Author can be contacted','dtransport'), 'author_contact', $edit ? $item->getVar('author_contact') : 1));
        $form->addElement(new RMFormText(__('Available languages','dtransport'), 'langs', 50, 255, $edit ? $item->getVar('langs') : ''));

        $form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
        $form->addElement(new RMFormHidden('id',$id));

        $buttons =new RMFormButtonGroup();
        $buttons->addButton('sbt',_SUBMIT,'submit');
        $buttons->addButton('cancel',_CANCEL,'button', 'onclick="window.location=\''.XOOPS_URL."/modules/dtransport/mydownloads.php".'\';"');

        $form->addElement($buttons);
        echo $form->render();

        // Ubicación Actual
        $location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";
        $location .= _MS_DT_SEND;
        $tpl->assign('dt_location', $location);
        
        include('footer.php');
        
}

