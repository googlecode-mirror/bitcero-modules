<label class="block"><?php _e('Upload image:','shop'); ?></label>
<?php 
include_once RMCPATH.'/class/rmuploader.php';
$uploader = new RMUploader('image', '', array('maxNumberOfFiles'=>1,'paramName'=>'image'));
echo $uploader->render(RMTemplate::get()->get_template('widgets/shop_w_updform.php', 'module', 'shop'));
?>