<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Provides a widget to specify the default image for posts
 */
function mw_widget_image(){
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $rm_config;

    $w['title'] = __('Default Image','mywords');

    if($xoopsModuleConfig['defimg']<=0){
        $w['content'] = '<div class="mw_permainfo">'.__('You have not specified an image category for defaults images.','mywords').'</div>';
        return $w;
    }

    $id = rmc_server_var($_REQUEST,'id',0);
    if($id<=0){
        $w['content'] = '<div class="mw_permainfo">'.__('You must save this post before to assign to it a default image..','mywords').'</div>';
        return $w;
    }

    $post = new MWPost($id);
    if($post->isNew()){
        $w['content'] = '<div class="mw_permainfo">'.__('You must save this post before to assign to it a default image..','mywords').'</div>';
        return $w;
    }

    if($xoopsModuleConfig['defimg']<=0){
        $w['content'] = '<div class="mw_permainfo">'.__('You have not specified an image category for defaults images.','mywords').'</div>';
        return $w;
    }

    $uploader = new RMFlashUploader('mywd-default-image', RMCURL.'/include/upload.php');
    $uploader->add_setting('scriptData', array(
        'action'=>'savedefimage',
        'category'=>$xoopsModuleConfig['defimg'],
        'rmsecurity'=>TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.RMCURL.'/images.php'.'|'.$xoopsSecurity->createToken(), true)
        // Need better code
    ));
    $uploader->add_setting('multi', false);
    $uploader->add_setting('fileExt', '*.jpg;*.png;*.gif;*.JPG');
    $uploader->add_setting('fileDesc', __('All Images (*.jpg, *.png, *.gif)','galleries'));
    $uploader->add_setting('sizeLimit', $rm_config['size_image']*1024);
    $uploader->add_setting('buttonText', __('Browse Images...','galleries'));
    $uploader->add_setting('queueSizeLimit', 1);
    $uploader->add_setting('auto', true);
    $uploader->add_setting('onComplete',"function(event, id, file, resp, data){
                eval('ret = '+resp);
                if (ret.error){
                    \$('#upload-errors').append('<span class=\"failed\"><strong>'+file.name+'</strong>: '+ret.message+'</span>');
                } else {
                    \$('#upload-errors').append('<span class=\"done\"><strong>'+file.name+'</strong>: ".__('Uploaded successfully!','rmcommon')."</span>');
                }

                defimg = ret.id;

                return true;
            }");
    $uploader->add_setting('onAllComplete', "function(event, data){

            defurl = '".RMCURL."/images.php';

            params = '".TextCleaner::getInstance()->encrypt($xoopsUser->uid().'|'.RMCURL.'/images.php'.'|'.$xoopsSecurity->createToken(), true)."';
            resize_image(params);

        }");
    $uploader->add_setting('onError', 'function (event,ID,fileObj,errorObj) {
          alert(errorObj.type + \' Error: \' + errorObj.info);
        }');
    RMTemplate::get()->add_head($uploader->render());

    // Template
    ob_start();
    ?>

    <div id="mywd-default-image"></div>
    <div id="mywd-default-thumb" style="display: block;">
        <?php if($post->getVar('image')>=0): ?>
            <img src="<?php echo $post->getImage('thumbnail'); ?>" alt="<?php $post->getVar('title'); ?>" />
        <?php endif; ?>
    </div>
    <div id="mywd-default-text">
        <span><?php _e('Creating image...','mywords'); ?></span>
    </div>

<?php
    $w['content'] = ob_get_clean();

    return $w;

}