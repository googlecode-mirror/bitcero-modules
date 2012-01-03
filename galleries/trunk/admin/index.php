<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION','dashboard');
include 'header.php';

function count_files($path){
    
    $file_count = 0;
    $dir = opendir($path);
    while (($file = readdir($dir)) !== false){
        if ($file == '.' || $file=='..') continue;
        if (is_dir($path . '/'.$file)){
            $file_count += count_files($path . '/'.$file);
        } else {
            $file_count++;
        }
    }
    closedir($dir);
    return $file_count;
    
}

function show_dashboard(){
    global $xoopsModuleConfig;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    // Sets count
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_sets");
    list($set_count) = $db->fetchRow($db->query($sql));
    // Pictures count
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_images");
    list($pic_count) = $db->fetchRow($db->query($sql));
    // Users count
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_users");
    list($user_count) = $db->fetchRow($db->query($sql));
    // Tags count
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_tags");
    list($tag_count) = $db->fetchRow($db->query($sql));
    // E-Cards count
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("gs_postcards");
    list($post_count) = $db->fetchRow($db->query($sql));
    // Used space
    $space = RMUtilities::formatBytesSize(GSFunctions::folderSize($xoopsModuleConfig['storedir']));
    // Number of files
    $file_count = count_files(rtrim($xoopsModuleConfig['storedir'], '/'));
    // First picture
    $sql = "SELECT * FROM ".$db->prefix("gs_images")." ORDER BY `created` ASC LIMIT 0,1";
    $result = $db->query($sql);
    if($db->getRowsNum($result)>0){
        $img = new GSImage();
        $img->assignVars($db->fetchArray($result));
        $user = new GSUser($img->owner(), 1);
        $tf = new RMTimeFormatter(0, '%M% %d%, %Y%');
        $first_pic['date'] = $tf->format($img->created());
        $first_pic['link'] = $user->userURL().($xoopsModuleConfig['urlmode'] ? 'img/'.$img->id().'/set/' : '&amp;img='.$img->id());
    }

    xoops_cp_header();
    
    GSFunctions::toolbar();
    RMTemplate::get()->add_style('dashboard.css','galleries');
    RMTemplate::get()->add_style('admin.css','galleries');
    RMTemplate::get()->add_head('<script type="text/javascript">var xurl = "'.XOOPS_URL.'";</script>');
    RMTemplate::get()->add_local_script('dashboard.js','galleries');
    include RMTemplate::get()->get_template('admin/gs_dashboard.php', 'module', 'galleries');

    xoops_cp_footer();

}

function send_pictures(){
    
    global $xoopsLogger;
    $xoopsLogger->renderingEnabled = false;
    error_reporting(0);
    $xoopsLogger->activated = false;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $limit = rmc_server_var($_GET, 'limit', 5);
    // recent pictures
    $sql = "SELECT * FROM ".$db->prefix("gs_images")." ORDER BY `created` DESC LIMIT 0,$limit";
    $result = $db->query($sql);
    $recents = GSFunctions::process_image_data($result);
    
    ob_start();
    ?>
    <?php foreach($recents as $pic): ?>
    <a href="<?php echo $pic['link']; ?>" target="_blank" title="<?php echo $pic['title']; ?>"><img src="<?php echo $pic['thumbnail']; ?>" alt="<?php echo $pic['title']; ?>" /></a>
    <?php endforeach; ?>
    <?php
    $recents = ob_get_clean();
    echo $recents; die();
    
}

function send_sets(){
    
    global $xoopsLogger, $xoopsModuleConfig;
    $xoopsLogger->renderingEnabled = false;
    error_reporting(0);
    $xoopsLogger->activated = false;
    
    $mc =& $xoopsModuleConfig;
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $limit = rmc_server_var($_GET, 'limit', 5);

    // recent pictures
    $sql = "SELECT * FROM ".$db->prefix("gs_sets")." ORDER BY `date` DESC LIMIT 0,$limit";
    $result = $db->query($sql);
    $sets = array();
    $users = array();
    while($row = $db->fetchArray($result)){
        $set = new GSSet();
        $set->assignVars($row);
        
        $pics = $set->getPics('RAND()');
        $img = new GSImage($pics[0]);
        if (!isset($users[$img->owner()])) $users[$img->owner()] = new GSUser($img->owner(), 1);
        
        if($img->isNew()){
            $image = array(
                'title' => __('Empty album','galleries'),
                'link' => '',
                'thumbnail' => GS_URL.'/images/empty.jpg'
            );
        } else {
            $image = array(
                'title' => $img->title(),
                'link' => $users[$img->owner()]->userURL().($mc['urlmode'] ? 'img/'.$img->id().'/' : '&amp;img='.$img->id()),
                'thumbnail'=>$users[$img->owner()]->filesURL().'/ths/'.$img->image()
            );
        }
        
        $sets[] = array(
            'title' => $set->title(),
            'link' => $set->url(),
            'image' => $image
        );
        
    }
    
    ob_start();
    ?>
    <?php foreach($sets as $set): ?>
    <a href="<?php echo $set['link']; ?>" target="_blank" title="<?php echo $set['title']; ?>"><img src="<?php echo $set['image']['thumbnail']; ?>" alt="<?php echo $set['title']; ?>" /></a>
    <?php endforeach; ?>
    <?php
    $recents = ob_get_clean();
    echo $recents; die();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'pictures':
        send_pictures();
        break;
    case 'sets':
        send_sets();
        break;
    default:
        show_dashboard();
        break;
    
}
