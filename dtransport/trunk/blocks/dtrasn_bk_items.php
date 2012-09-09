<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function dt_block_items($options){
	global $db, $xoopsModule;
	
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtsoftware.class.php';
	include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
    
    $tpl = RMTemplate::get();
    $tpl->add_xoops_style('blocks.css','dtransport');
    
    $dtfunc = new DTFunctions();
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $tbls = $db->prefix("dtrans_software");
    $tblc = $db->prefix("dtrans_catsoft");
    
    if($options[1]>0){
        
        $sql = "SELECT s.* FROM $tbls as s, $tblc as c WHERE c.cat='".$options[1]."' AND s.id_soft=c.soft AND s.approved=1 AND s.`delete`=0";
        
    } else {
        
        $sql = "SELECT s.* FROM $tbls as s WHERE s.`approved`=1 AND s.`delete`=0 ";
        
    }
	
	if (trim($options[10])>0){
        $user = new RMUser(trim($options[10]));
        if($user->isNew()) return;
		$sql .= " AND s.uid='".$user->id()."' ";
	}
    
    if ($options[11]>0) $sql .= "AND id_cat='$options[11]'";
	
	switch ($options[0]){
        case 'all':
            $sql .= ' ORDER BY RAND() ';
            break;
		case 'recent':
			$sql .= " ORDER BY s.modified DESC, created DESC ";
			break;
		case 'popular':
			$sql .= " ORDER BY s.hits DESC ";
			break;
		case 'rated':
			$sql .= " ORDER BY s.`rating`/s.`votes` DESC ";
			break;
        case 'featured':
            $sql .= " AND featured=1 ORDER BY RAND() ";
            break;
        case 'daily':
            $sql = " AND daily=1 ORDER BY RAND() ";
            break;
	}
	
    $options[2] = $options[2]>0 ? $options[2] : 5;
	$sql .= " LIMIT 0, $options[2]";
	
	$result = $db->query($sql);
	$block = array();
	while($row = $db->fetchArray($result)){
		$item = new DTSoftware();
		$item->assignVars($row);
		$rtn = array();
		$rtn['name'] = $item->getVar('name');
        $rtn['version'] = $item->getVar('version');
        
        if($options[3]){
            $img = new RMImage();
            $img->load_from_params($item->getVar('image'));
            $rtn['image'] = $img->get_version($options[11]);
        }
        
		if ($options[4]) $rtn['description'] = $item->getVar('shortdesc');
		if ($options[5]) $rtn['hits'] = sprintf(__('Downloaded %s times.','dtransport'), '<strong>'.$item->getVar('hits').'</strong>');
		if ($options[6]) $rtn['urate'] = @number_format($item->getVar('rate')/$item->getVar('votes'), 1);
		if ($options[7]){
			$rtn['siterate'] = DTFunctions::ratingStars($item->getVar('siterate'));
		}
        $rtn['link'] = $item->permalink();
        $rtn['metas'] = $dtfunc->get_metas('down', $item->id());
		if($options[9]) $rtn['author'] = array('name'=>$item->getVar('author_name'),'url'=>$item->getVar('author_url'));
		$block['downs'][] = $rtn;
	}
	
	$block['showbutton'] = $options[8];
	$block['downlang'] = __('Download','dtransport');
    $block['lang_urate'] = __('User rating: %s','dtransport');
	$block['lang_author'] = __('Author: %s','dtransport');
	$block['langhits'] = _BK_DT_HITSTEXT;
	$block['langurate'] = _BK_DT_URATETEXT;
	$block['languser'] = _BK_DT_USERBY;
	
	return $block;

}

function dt_block_items_edit($options){
	
	include_once RMCPATH.'/class/form.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtcategory.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
    
    $dtfunc = new DTFunctions();
    $elements = array();
	
	$ele = new RMFormSelect(__('Donwloads type','dtransport'), 'options[0]', 0, array($options[0]));
    $ele->addOption('all', __('All downloads','dtransport'));
	$ele->addOption('recent', __('Recent downloads','dtransport'));
	$ele->addOption('popular', __('Popular downloads','dtransport'));
    $ele->addOption('rated', __('Best rated downloads','dtransport'));
    $ele->addOption('featured', __('Featured download','dtransport'));
	$ele->addOption('daily', __('Daily downloads','dtransport'));
	
    $elements[] = array('title' => $ele->getCaption(), 'content' =>$ele->render());
    
    // Categoría
    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
    $categos = array();
    $dtfunc->getCategos($categos, 0, 0, array(), false, 1);
    $ele = new RMFormSelect(__('Downloads from category','dtransport'), 'options[1]', false, $options[1]);
    $ele->addOption(0, __('All categories','dtransport'));
    foreach ($categos as $cat){
        $ele->addOption($cat['id_cat'], str_repeat("&#151;", $cat['jumps']).' '.$cat['name']);
    }
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
    
	// Numero de Descargas
	$ele = new RMFormText(__('Items limit','dtransport'), 'options[2]', 5, 2, $options[2]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar imágen
    $ele = new RMFormYesNo(__('Show image','dtransport'), 'options[3]', $options[3]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
    // Tamaño de imágen utilizado
	$ele = new RMFormText(__('Image size','dtransport'), 'options[11]', 20, 100, $options[11]);
    $ele->setDescription(__('This name must match with a size configured previously in image manager.','dtransport'));
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar Descripción
	$ele = new RMFormYesNo(__('Show description','dtransport'), 'options[4]', $options[4]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar Hits
	$ele = new RMFormYesNo(__('Show hits','dtransport'), 'options[5]', $options[5]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar Ratig de Usuarios
	$ele = new RMFormYesNo(__('Show user rating','dtransport'), 'options[6]', $options[6]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar Rating del Sitio
	$ele = new RMFormYesNo(__('Show site rating','dtransport'), 'options[7]', $options[7]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar Enlace de descarga
	$ele = new RMFormYesNo(__('Show download link','dtransport'), 'options[8]', $options[8]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
	// Mostrar Nombre de Usuario
	$ele = new RMFormYesNo(__('Show author','dtransport'), 'options[9]', $options[9]);
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render());
    // Descargas de usuario
	$ele = new RMFormText(__('Show downloads from a single user','dtransport'), 'options[10]', 10, 100, $options[10]);
    $ele->setDescription(__('You can specify a user name or a integer id of the user.','dtransport'));
    $elements[] = array('title' => $ele->getCaption(), 'content' => $ele->render(), 'description' => $ele->getDescription());
    
    $form = '<table class="outer">';
    foreach($elements as $ele){
        
        $form .= '<tr><td>'.$ele['title'].($ele['description']!='' ? '<span class="description">'.$ele['description'].'</span>':'').'</td><td>'.$ele['content'].'</td></tr>';
        
    }
    $form .= '</table>';
	
	return $form;
	
}

