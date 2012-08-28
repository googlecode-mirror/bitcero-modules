<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if ($id==''){
	header('location: '.DT_URL);
    die();
}

$item = new DTSoftware($id);
if ($item->isNew() || !$item->getVar('approved')){
	redirect_header(DT_URL, 2, __('Specified item does not exists!','dtransport'));
	die();
}

if($item->getVar('delete'))
    redirect_header(DT_URL, 2, __('This item is not available for download at this moment!','dtransport'));

// Download default file
if($action=='download'){

    $file = $item->file();
    if(!$file)
        redirect_header($item->permalink(), 0, __('Internal Error! Please try again later','dtransport'));
    
    header("location: ".$file->permalink());
    die();
    
}

	$xoopsOption['template_main'] = 'dtrans_item.html';
	$xoopsOption['module_subpage'] = 'item';

	include 'header.php';
	$xoopsTpl->assign('dtrans_option','details');
	
	$dtfunc->makeHeader();

    $candownload = $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS);

	// Enlaces del elemento
	$data = array();
	$data['link'] = $item->permalink();
	$data['screens'] = $item->permalink(0, 'screens');
	$data['download'] = $candownload ? $item->permalink(0, 'download') : '';
	$data['features'] = $item->permalink(0, 'features');
	$data['logs'] = $item->permalink(0, 'logs');

    // Datos generales
	$data['name'] = $item->getVar('name');
	$data['version'] = $item->getVar('version');

    // Imagen por defecto
    $img = new RMImage();
    $img->load_from_params($item->getVar('image'));

	$data['image'] = $img->url();
    $data['thumbnail'] = $img->get_smallest();
	$data['rating'] = @number_format($item->getVar('rating')/$item->getVar('votes'), 1);
    $data['votes'] = $item->getVar('votes');

	// Licencias
	$data['licenses'] = array();
	foreach ($item->licences(true) as $lic){
		$data['licenses'][] = array(
            'url' => $lic->link(),
            'name' => $lic->name(),
            'link' => $lic->permalink()
        );
	}

	//  Plataformas
	$data['platforms'] = array();
	foreach ($item->platforms(true) as $os){
		$data['platforms'][] = array(
            'name' => $os->name(),
            'link' => $os->permalink()
        );
	}

    $tf = new RMTimeFormatter(0, '%T% %d%, %Y%'); // Time formatter

	$data['created'] = $tf->format($item->getVar('created'));
	$data['update'] = $item->getVar('created')>0 ? $tf->format($item->getVar('modified')) : '';
	$data['author'] = array(
        'name'      => $item->getVar('author_name'),
        'url'       => $item->getVar('author_url'),
        'email'     => $item->getVar('author_email'),
        'contact'   => $item->getVar('author_contact'),
    );
	$data['langs'] = $item->getVar('langs');
    $data['downs'] = $item->getVar('hits');
    $data['version'] = $item->getVar('version');
    $data['updated'] = $item->getVar('modified')>$item->getVar('created') && $item->getVar('modified')>(time()-($mc['update']*86400));
    $data['new'] = !$data['updated'] && $item->getVar('created')>(time()-($mc['new']*86400));
    $data['description'] = $item->getVar('desc');
    $data['shortdesc'] = $item->getVar('shortdesc');
    $data['siterate'] = $dtfunc->ratingStars($item->getVar('siterate'));

	$fg = $item->fileGroups(true);
    $data['filegroups'] = array();
    foreach($fg as $g){
        $files = $g->files(true);
        $data['filegroups'][$g->id()]['name'] = $g->name();
        foreach($files as $file){
            $data['filegroups'][$g->id()]['files'][] = array(
                'file' => $file->file(),
                'size' => $rmu->formatBytesSize($file->size()),
                'date' => $tf->format($file->date()),
                'title' => $file->title(),
                'remote' => $file->remote(),
                'hits' => $file->hits(),
                'link' => $file->permalink()
            );
        }
    }

    // Imágenes de la Descarga
    $imgs = $item->screens(true);
    $xoopsTpl->assign('screens_count', $item->getVar('screens'));
    $data['screens'] = array();
    foreach ($imgs as $img){
        $data['screens'][] = array(
            'id'=>$img->id(),
            'title'=>$img->title(),
            'image'=>$img->url(),
            'ths' => $img->url('ths')
        );
    }
    unset($imgs,$img);

    //Etiquetas
    $tags = $item->tags(false);
    $relatedTags = array();
    $data['tags'] = array();
    foreach ($tags as $tag){
        $otag=new DTTag();
        $otag->assignVars($tag);
        $data['tags'][] = array(
            'id' => $tag['id_tag'],
            'name' => $tag['tag'],
            'link' => $otag->permalink()
        );
        $relatedTags[] = $tag['id_tag'];
    }
    unset($tags,$otag,$tag);

    // Categories
    $cats = $item->categories(true);
    $data['categories'] = array();
    foreach($cats as $ocat){
        $data['categories'][] = array(
            'id' => $ocat->id(),
            'name' => $ocat->name(),
            'link' => $ocat->permalink()
        );
    }
    unset($ocat,$cats,$cat);

    // Características
    $chars = $item->features(true);
    $data['features'] = array();
    foreach ($chars as $feature){
        $updated = $feature->modified()>$feature->created() && $feature->modified()>(time()-($mc['update']*86400));
        $new = !$updated && $feature->created()>(time() - ($mc['new']*86400));
        $data['features'][] = array(
            'id'=>$feature->id(),
            'title'=>$feature->title(),
            'updated'=>$updated,
            'nameid'=>$feature->nameid(),
            'content' => $feature->content()
        );
    }
    unset($chars, $feature);

    // Logs
    $logs = $item->logs(true);
    $data['logs'] = array();
    foreach ($logs as $log){
        $data['logs'][] = array(
            'id'=>$log->id(),
            'title'=>$log->title(),
            'content' => $log->log(),
            'date'=>formatTimestamp($log->date(),'s')
        );
    }
    unset($logs, $log);

	$xoopsTpl->assign('item', $data);

	// Usuario
	$dtUser = new XoopsUser($item->getVar('uid'));
	$xoopsTpl->assign('dtUser', array('id'=>$dtUser->uid(),'uname'=>$dtUser->uname(),'avatar'=>$dtUser->getVar('user_avatar')));


    if($mc['daydownload']){
        $xoopsTpl->assign('daily_items', $dtfunc->get_items(0, 'daily', $mc['limit_daydownload']));
        $xoopsTpl->assign('daily_width', floor(100/($mc['limit_daydownload'])));
        $xoopsTpl->assign('lang_daydown', __('<strong>Day</strong> Downloads','dtransport'));
    }

    // Desargas relacionadas
    if($mc['active_relatsoft']){
        $xoopsTpl->assign('lang_related',__('<strong>Related</strong> Downloads','dtransport'));
        $xoopsTpl->assign('related_items', $dtfunc->items_by($relatedTags, 'tags', $item->id(), 'RAND()', 0, $mc['limit_relatsoft']));
    }

	// Lenguaje
    $xoopsTpl->assign('lang_new', __('New','dtransport'));
    $xoopsTpl->assign('lang_updated', __('Updated','dtransport'));
    $xoopsTpl->assign('lang_author', __('Author:','dtransport'));
    $xoopsTpl->assign('lang_version', __('Version:','dtransport'));
    $xoopsTpl->assign('lang_createdon', __('Created on:','dtransport'));
    $xoopsTpl->assign('lang_updatedon', __('Updated on:','dtransport'));
    $xoopsTpl->assign('lang_langs', __('Languages:','dtransport'));
    $xoopsTpl->assign('lang_platforms', __('Supported platforms:','dtransport'));
    $xoopsTpl->assign('lang_license',__('License:','dtransport'));
    $xoopsTpl->assign('lang_siterate', sprintf(__('%s rate','dtransport'), $xoopsConfig['sitename']));
    $xoopsTpl->assign('lang_rateuser', __('Users rating', 'dtransport'));
    $xoopsTpl->assign('lang_votes', __('%u votes', 'dtransport'));
    $xoopsTpl->assign('lang_downnow', __('Download Now!','dtransport'));
    $xoopsTpl->assign('lang_download', __('Download','dtransport'));
    $xoopsTpl->assign('lang_screenshots', __('Screenshots','dtransport'));
    $xoopsTpl->assign('lang_tags',__('Tags:','dtransport'));
    $xoopsTpl->assign('lang_published',__('Published on:','dtransport'));
    $xoopsTpl->assign('lang_downopts',__('Download Options','dtransport'));
    $xoopsTpl->assign('lang_details',__('Details','dtransport'));
    $xoopsTpl->assign('lang_logs', __('Logs','dtransport'));
    $xoopsTpl->assign('lang_features', __('Features','dtransport'));
    $xoopsTpl->assign('lang_choose', __('You can choose between next download options if you prefer another file type or another location.','dtransport'));

    // Download options labels
    $xoopsTpl->assign('lang_title', __('Title','dtransport'));
    $xoopsTpl->assign('lang_size', __('Size','dtransport'));
    $xoopsTpl->assign('lang_hits', __('Hits','dtransport'));

	$xoopsTpl->assign('xoops_pagetitle', $item->getVar('name').($item->getVar('version')!='' ? " ".$item->getVar('version') : '')." &raquo; ".$xoopsModule->name());

	// Ubicación Actual
	$location = "<strong>"._MS_DT_YOUREHERE."</strong> <a href='".DT_URL."'>".$xoopsModule->name()."</a> &raquo; ";

	$location .= " &raquo; <strong>".$item->getVar('name')."</strong>";
	$xoopsTpl->assign('dt_location', $location);

    $tpl->add_xoops_style('main.css','dtransport');
    $tpl->add_local_script('main.js','dtransport');

    // Lightbox plugins
    if($rmf->plugin_installed("lightbox")){
        $lightbox = RMLightbox::get();
        $lightbox->add_element("a.item-images");
        $lightbox->add_element("#dt-item-features a");
        $lightbox->render();
    }

	include 'footer.php';

