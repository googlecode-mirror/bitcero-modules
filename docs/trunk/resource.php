<?php
// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (isset($special) && ($special=='references' || $special=='figures')){
	$xoopsOption['module_subpage'] = 'content';
} else {
	$xoopsOption['module_subpage'] = 'resource';
}

// Check if Document exist
$res= new RDResource($id);
if ($res->isNew()){
    // Error 404 - When resrouce does not exists
	RDFunctions::error_404();
}

if($res->getVar('single'))
    define('RD_LOCATION','resource_content');

include ('header.php');

//Verificamos si la publicacion esta aprobada
if (!$res->getVar('approved')){
	redirect_header(RDURL, 1, __('Sorry, this Document does not exists!','docs'));
	die();
}

//Verifica si el usuario cuenta con permisos para ver la publicación
$allowed = $res->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS);
if (!$allowed && !$res->getVar('show_index')){
	redirect_header(RDURL, 2, __('Sorry, you are not authorized to view this Document','docs'));
	die();
}

if (!$allowed && !$res->getVar('quick')){
	redirect_header(RDURL, 2, __('Sorry, you are not authorized to view this Document','docs'));
	die();
}

RDFunctions::breadcrumb();
RMBreadCrumb::get()->add_crumb($res->getVar('title'), $res->permalink());

// Check if we must show all content for Document
if($res->getVar('single')){
    
    if(!$allowed)
        RDfunctions::error_404();
        
    // Show all content
    $toc = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $toc, true);
    
    array_walk($toc, 'rd_insert_edit');
    $last_modification = 0;
    foreach($toc as $sec){
        if($sec['modified']>$last_modification){
            $last_modification = $sec['modified'];
            $last_author = array(
                'id' => $sec['author'],
                'name' => $sec['author_name']
            );
        }
    }
    
    RMTemplate::get()->add_jquery();
    RMTemplate::get()->add_script(XOOPS_URL.'/modules/docs/include/js/docs.js');
    
    // Comments
    RMFunctions::get_comments('docs', 'res='.$res->id(), 'module', 0);
    RMFunctions::comments_form('docs', 'res='.$res->id(), 'module', RDPATH.'/class/docscontroller.php');
    
    $res->add_read();
    
    // URLs
    if ($xoopsModuleConfig['permalinks']){
        $config = array();
        $config =& $xoopsModuleConfig;
        /**
        * @todo Generate friendly links
        */
        if (RMFunctions::plugin_installed('topdf')){
            $pdf_book_url = ($xoopsModuleConfig['subdomain']!='' ? $xoopsModuleConfig['subdomain'] : XOOPS_URL).$xoopsModuleConfig['htpath'].'/pdfbook/'.$toc[0]['id'].'/';
        }
        $print_book_url = ($xoopsModuleConfig['subdomain']!='' ? $xoopsModuleConfig['subdomain'] : XOOPS_URL).$xoopsModuleConfig['htpath'].'/printbook/'.$toc[0]['id'].'/';
        if (RDFunctions::new_resource_allowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS))
            $publish_url = RDFunctions::url().'/publish/';
    } else {
        if (RMFunctions::plugin_installed('topdf')){
            $pdf_book_url = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$res->id().'&amp;action=pdfbook';
        }
        $print_book_url = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$res->id().'&amp;action=printbook';
        if (RDFunctions::new_resource_allowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS))
            $publish_url = RDFunctions::url().'/?action=publish';
    }
    
    include RMTemplate::get()->get_template('rd_resall.php','module','docs');
    
} elseif ($res->getVar('quick')){
    // Show Quick Index to User
    
	$content=false;
	//Obtiene índice
    $db = XoopsDatabaseFactory::getDatabaseConnection();
	$sql="SELECT * FROM ".$db->prefix('rd_sections')." WHERE id_res='".$res->id()."' AND parent=0 ORDER BY `order`";
	$result=$db->queryF($sql);
    
    // Quick index array
    $qindex_sections = array();
    
	while ($rows=$db->fetchArray($result)){
		$sec=new RDSection();
		$sec->assignVars($rows);
		
		$qindex_sections[] = array(
            'id'=> $id,
            'title'=> $sec->getVar('title'),
		    'desc'=> TextCleaner::getInstance()->clean_disabled_tags(TextCleaner::truncate($sec->getVar('content'), 255)),
		    'link'=> $sec->permalink()
        );
	}	
    
    include RMTemplate::get()->get_template('rd_quickindex.php','module','docs');
	
	
} else {
	
	if (!$allowed){
		RDFunctions::error_404();
	}
	
    $toc = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $toc);
    
    include RMTemplate::get()->get_template('rd_resindextoc.php','module','docs');
	
}

RMTemplate::get()->add_style('docs.css', 'docs');
RMTemplate::get()->add_jquery();
RMTemplate::get()->add_script(RDURL.'/include/js/docs.js');

if($standalone)
    RDFunctions::standalone();

include ('footer.php');
