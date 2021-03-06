<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$xoopsOption['template_main'] = 'mywords_post.html';
$xoopsOption['module_subpage'] = 'post';
include 'header.php';

if ($post<=0){
    redirect_header(MWFunctions::get_url(), 2, '');
    die();
}

$post = new MWPost($post);
// Comprobamos que exista el post
if ($post->isNew()){
    redirect_header(mw_get_url(), 2, __('Document not found','mywords'));
    die();
}
// Comprobamos permisos de acceso al post
if (!$post->user_allowed()){
    redirect_header(MWFunctions::get_url(), 2, __('Sorry, you are not allowed to view this post','mywords'));
    die();
}

// Check if post belong to some category
if (count($post->get_categos())<=0){
    $post->update();
}

# Generamos los vínculos
$day = date('d', $post->getVar('pubdate'));
$month = date('m', $post->getVar('pubdate'));
$year = date('Y', $post->getVar('pubdate'));

// 
$page = isset($_REQUEST['page']) ? $_REQUEST['page']: 0;


# Generamos el vínculo para el autor
$editor = new MWEditor($post->getVar('author'));
# Texto de continuar leyendo

$xoopsTpl->assign('xoops_pagetitle', $post->getVar('title'));

# Cargamos los comentarios del Artículo    
if ($page<=0){
    $path = explode("/", $request);
    $srh = array_search('page', $path);
    if (isset($path[$srh]) && $path[$srh]=='page')    {
        if (!isset($path[$srh])){ 
            $page = 1; 
        } else { 
            $page = $path[$srh +1]; 
        }
    } else {
        $page = 1;
    }
}

$post->add_read();

// Navegación entre artículos
if($xoopsModuleConfig['shownav']){
    $sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE id_post<".$post->id()." AND status='publish' ORDER BY id_post DESC LIMIT 0, 1";
    $result = $db->query($sql);
    $pn = new MWPost();
    // Anterior
    if ($db->getRowsNum($result)>0){
        $pn->assignVars($db->fetchArray($result));
        $xoopsTpl->assign('prev_post', array('link'=>$pn->permalink(), 'title'=>$pn->getVar('title')));
    }

    // Siguiente
    $sql = "SELECT * FROM ".$db->prefix("mw_posts")." WHERE id_post>".$post->id()." AND status='publish' ORDER BY id_post ASC LIMIT 0, 1";
    $result = $db->query($sql);
    if ($db->getRowsNum($result)>0){
        $pn->assignVars($db->fetchArray($result));
        $xoopsTpl->assign('next_post', array('link'=>$pn->permalink(), 'title'=>$pn->getVar('title')));
    }
}
$xoopsTpl->assign('shownav', $xoopsModuleConfig['shownav']);

if($xoopsUser && ($xoopsUser->isAdmin() || $editor->getVar('uid')==$xoopsUser->uid())){
    $edit = '<a href="'.XOOPS_URL.'/modules/mywords/admin/posts.php?op=edit&amp;id='.$post->id().'">'.__('Edit Post','mywords').'</a>';
    $xoopsTpl->assign('edit_link', $edit);
    unset($edit);
}

$xoopsTpl->assign('lang_reads', sprintf(__('%u views','mywords'), $post->getVar('reads')));

// Tags
$tags = $post->tags(true);
$tags_list = '';
foreach($tags as $i => $tag){
    $tags_list .= ($tags_list==''?'':', ').'<a href="'.$tag->permalink().'">'.$tag->getVar('tag').'</a>';
}

// Post pages
$total_pages = $post->total_pages();
$nav = new RMPageNav($total_pages, 1, $page, 5);
$nav->target_url($post->permalink().($mc['permalinks']>1 ? 'page/{PAGE_NUM}/' : '&amp;page={PAGE_NUM}'));
$xoopsTpl->assign('post_navbar', $nav->render(true));

// Post data

$post_arr = array(
    'id'        => $post->id(),
    'title'     => $post->getVar('title'),
    'published' => sprintf(__('%s by %s','mywords'), MWFunctions::format_time($post->getVar('pubdate')), date('H:i',$post->getVar('pubdate')),'<a href="'.$editor->permalink().'">'.(isset($editor) ? $editor->getVar('name') : __('Anonymous','mywords'))."</a>"),
    'text'      => $post->content(false, $page),
    'cats'      => $post->get_categories_names(),
    'tags'      => $tags_list,
    'trackback' => $post->getVar('pingstatus') ? MWFunctions::get_url(true).$post->id() : '',
    'meta'      => $post->get_meta('', false),
    'time'      => $post->getVar('pubdate'),
    'image'     => $post->getImage($xoopsModuleConfig['post_imgs_size']),
    'author'    => $editor->getVar('name'),
    'alink'     => $editor->permalink()
);



// Plugins?
$post_arr = RMEvents::get()->run_event('mywords.view.post', $post_arr, $post);
$xoopsTpl->assign('post', $post_arr);

// Social sites
if($xoopsModuleConfig['showbookmarks']){
    foreach($socials as $site){
        $xoopsTpl->append('socials', array(
            'title' => $site->getVar('title'),
            'icon'    => $site->getVar('icon'),
            'url'    => $site->link($post->getVar('title'), $post->permalink(), TextCleaner::truncate($post->content(true), 60)),
            'alt'    => $site->getVar('alt')
        ));
    }
}

unset($tags_list);

// Comments
// When use the common utilities comments system you can choose between
// use of Common Utilities templates or use your own templates
// We will use MyWords included templates
if ($post->getVar('comstatus')){
    $comms = RMFunctions::get_comments('mywords','post='.$post->id(), 'module', 0, null, false);
    if (count($comms)!=$post->getVar('comments')){
        $post->setVar('comments', count($comms));
        $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("mw_posts")." SET `comments`=".count($comms)." WHERE id_post=".$post->id());
    }
    $xoopsTpl->assign('comments', $comms);
    // Comments form
    RMFunctions::comments_form('mywords', 'post='.$post->id(), 'module', MW_PATH.'/class/mywordscontroller.php');
}

// Load trackbacks
$trackbacks = $post->trackbacks();
foreach ($trackbacks as $tb){
    $xoopsTpl->append('trackbacks', array(
        'id'    => $tb->id(),
        'title' => $tb->getVar('title'),
        'blog'  => $tb->getVar('blog_name'),
        'url'  => $tb->getVar('url'),
        'text'  => $tb->getVar('excerpt'),
        'date'  => formatTimestamp($tb->getVar('date'), 'c')
    ));
}

// Language
$xoopsTpl->assign('lang_publish', __('Published in','mywords'));
$xoopsTpl->assign('lang_tagged',__('Tagged as','mywords'));
$xoopsTpl->assign('lang_numcoms', sprintf(__('%u Comments', 'mywords'), $post->getVar('comments')));
$xoopsTpl->assign('lang_numtracks', sprintf(__('%u trackbacks', 'mywords'), count($trackbacks)));
$xoopsTpl->assign('lang_trackback', __('Trackback','mywords'));
$xoopsTpl->assign('lang_homemw',__('Main Page','mywords'));
$xoopsTpl->assign('enable_images', $xoopsModuleConfig['post_imgs']);

//Trackback
if ($post->getVar('pingstatus')){
    $tb = new MWTrackback($xoopsConfig['sitename'], $editor->getVar('name'));
    RMTemplate::get()->add_head(
        $tb->rdf_autodiscover(date('r', $post->getVar('pubdate')), $post->getVar('title'), TextCleaner::getInstance()->truncate($post->content(true), 255), $post->permalink(), MWFunctions::get_url(true).$post->id(), $editor->getVar('name'))
    );
}

$rmf = RMFunctions::get();
$description = $post->get_meta('description', false);
$keywords = $post->get_meta('keywords', false);
$rmf->add_keywords_description($description!='' ? $description : $post->content(true), $keywords!='' ? $keywords : '');

// Send pings?
$pings = $post->getVar('toping');
$xoopsTpl->assign('pingnow', empty($pings));
 
include 'footer.php';
