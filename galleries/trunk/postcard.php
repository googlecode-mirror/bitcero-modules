<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$toget = 'id';
include 'include/parse.php';

define('GS_LOCATION','postcards');
include '../../mainfile.php';

if (!$xoopsModuleConfig['postcards']){
	redirect_header(XOOPS_URL.'/modules/galleries/', 1, __('The postcards service is currently disabled!','galleries'));
	die();
}

/**
* @desc Muestra el formulario para la creación de la postal
*/
function newPostcard(){
	global $xoopsUser, $xoopsModule, $xoopsModuleConfig, $mc, $tpl, $img, $xoopsOption, $xoopsConfig;
	
	if (!$xoopsUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, __('You must be a registered user in order to send postcards!','galleries'));
		die();
	}
	
	$image = new GSImage($img);
	if ($image->isNew()){
		redirect_header(GSFunctions::get_url(), 1, __('Specified image does not exists!','galleries'));
		die();
	}
	
	$xoopsOption['template_main'] = "gs_postcard_form.html";
	include 'header.php';
	
	//Eliminamos las postales que han cumplido si tiempo
	GSFunctions::deletePostcard();

	GSFunctions::makeHeader();
	$tpl->assign('xoops_pagetitle', sprintf(__('Send Postcard','galleries'), $image->title()));

	$postlink = GSFunctions::get_url().($mc['urlmode'] ? 'postcard/new/img/'.$image->id().'/' : '?postcard=new&amp;img='.$image->id());
	$sendlink = str_replace('/new/','/send/',$postlink);
	$form = new RMForm(__('Send Postcard','galleries'), 'frmNewPostcard', $sendlink);
	$form->addElement(new RMFormText(__('Your name','galleries'), 'fname', 50, 100, $xoopsUser->getVar('name')), true);
	$form->addElement(new RMFormText(__('Your email','galleries'), 'fmail', 50, 150, $xoopsUser->getVar('email')), true, 'email');
	$form->addElement(new RMFormText(__('Friend name','galleries'), 'tname', 50, 100, ''), true);
	$form->addElement(new RMFormText(__('Friend email','galleries'), 'tmail', 50, 150, ''), true, 'email');
	$form->addElement(new RMFormText(__('Postcard title','galleries'), 'title', 50, 150, $image->title(false)), true);
	$form->addElement(new RMFormTextArea(__('Postcard text','galleries'), 'msg', 0,0,'','90%','150px'), true);
	
    $form->addElement(new RMFormLabel(__('Please enter the captcha below','galleries'), RMEvents::get()->run_event('rmcommon.recaptcha.field')));
    
	$ele = new RMFormButtonGroup();
	$ele->addButton('sbt', _SUBMIT, 'submit','onclick="$(\'op\').value=\'send\';"');
	$previewlink = str_replace('/new/','/preview/',$postlink);
	$ele->addButton('preview', __('Preview Postcard','galleries'), 'button','onclick="$(\'#frmNewPostcard\').attr(\'action\', \''.$previewlink.'\'); $(\'#frmNewPostcard\').submit();"');
	$form->addElement($ele);
	$form->addElement(new RMFormHidden('op','send'));
	$form->addElement(new RMFormHidden('img',$image->id()));
	$form->addElement(new RMFormHidden('uid',$xoopsUser->uid()));
	$form->addElement(new RMFormHidden('return',base64_encode($postlink)));
	
	$tpl->assign('postcard_form', $form->render());
	
	include 'footer.php';
	
}

/**
* @desc Muestra una previsualización de la postal
*/
function previewPostcard(){
	global $tpl, $xoopsModule, $xoopsModuleConfig, $xoopsModuleConfig, $mc, $xoopsUser, $xoopsConfig;
	
    include_once XOOPS_ROOT_PATH.'/class/template.php';
	$tpl = new XoopsTpl();
	$mc =& $xoopsModuleConfig;
	
    include_once RMCPATH.'/class/form.class.php';
	
	foreach ($_POST as $k => $v){
		if ($k=='sbt'||$k=='op') continue;
		$$k = $v;
		$ele = new RMFormHidden($k,$v);
		$tpl->append('hiddens',$ele->render());
	}
	
	if (!$xoopsUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, __('You must be a registered user in order to send postcards!','galleries'));
		die();
	}
	
	$img = new GSImage($img);
	if ($img->isNew()){
		redirect_header(GSfunctions::get_url(), 1, __('Specified image does not exists!','galleries'));
		die();
	}
	
	$user = new GSUser($img->owner(), 1);
	$file = $user->filesPath().'/'.$img->image();
	list($ancho,$alto) = getimagesize($file);
	
	$tpl->assign('gs_url', GSFunctions::get_url());
	$tpl->assign('img', array('id'=>$img->id(),'width'=>$ancho,'height'=>$alto,
			'url'=>$user->filesURL().'/'.$img->image(),'link'=>$user->userURL().'img/'.$img->id().'/'));
	$tpl->assign('title', $title);
	$tpl->assign('message', $msg);
	$tpl->assign('lang_says', sprintf(__('%s Dice:','galleries'), $fname));
	$tpl->assign('xoops_pagetitle', sprintf(__('Preview "%s" postcard','galleries'), $title).' &raquo; '.$mc['section_title']);
	$tpl->assign('lang_see', __('View picture','galleries'));
	$tpl->assign('user_link', $user->userURL().'browse/'.$img->id().'/');
	$tpl->assign('lang_seeuser', __('View user pictures','galleries'));
	$tpl->assign('preview', 1);
	$tpl->assign('lang_submit', __('Send Postcard','galleries'));
	$tpl->assign('lang_edit', __('Edit Postcard','galleries'));
	$tpl->assign('postcard_link', base64_decode($return));
	$sendlink = GSfunctions::get_url().($mc['urlmode'] ? 'postcard/send/img/'.$img->id() : '?postcard=send&amp;img='.$img->id());
    
    $tpl->assign('captcha', RMEvents::get()->run_event('rmcommon.recaptcha.field'));
    
    RMTemplate::get()->add_xoops_style('postcard.css', 'galleries');
    
	$tpl->assign('send_link',$sendlink);
	
		
	echo $tpl->fetch("db:gs_postcard.html");
	
}

/**
* @desc Envia la postal
*/
function sendPostcard(){
	global $tpl, $xoopsModule, $xoopsModuleConfig, $rmc_config, $mc, $xoopsUser, $xoopsConfig,$util;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$xoopsUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, _MS_GS_ERRUSR);
		die();
	}
	
	$img = new GSImage($img);
	if ($img->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_ERRIMG);
		die();
	}
    
     // Recaptcha check
    if (!RMEvents::get()->run_event('rmcommon.captcha.check', true)){
        redirect_header(GSFunctions::get_url().($xoopsModuleConfig['urlmode'] ? 'postcard/new/img/'.$img->id().'/' : '?postcard=new&amp;img='.$img->id()), 1, __('Please check the security words and write it correctly!','contact'));
        die();
    }
	
	$post = new GSPostcard();
	$post->setTitle($title);
	$post->setMessage($msg);
	$post->setDate(time());
	$post->setToName($tname);
	$post->setToEmail($tmail);
	$post->setImage($img->id());
	$post->setName($fname);
	$post->setEmail($fmail);
	$post->setUid($uid);
	$post->setIp($_SERVER['REMOTE_ADDR']);
	$post->setViewed(0);

	//Generamos el código de la postal
	$post->setCode(RMUtilities::randomString(10,1,1,1,1));

	if (!$post->save()){
		redirect_header(base64_decode($return), 2, __('Unable to send e-card. Please try again!','galleries'));
		die();
	}
	
	$xoopsMailer =& getMailer();
	$xoopsMailer->useMail();
    $ectpl = is_file(XOOPS_ROOT_PATH.'/modules/galleries/lang/'.'postcard-'.$rmc_config['language'].'.tpl') ? $rmc_config['language'].'.tpl' : 'postcard-en_US.tpl';
	$xoopsMailer->setTemplate($ectpl);
	$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
	$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
	$xoopsMailer->assign('FNAME', $fname);
	$xoopsMailer->assign('FMAIL', $fmail);
	$xoopsMailer->assign('TNAME', $tname);
	$xoopsMailer->assign('MODULE_LINK', GSfunctions::get_url());
	$xoopsMailer->assign('POSTAL_LINK', GSfunctions::get_url().($mc['urlmode'] ? 'postcard/view/id/'.$post->code().'/' : '?postcard=view&amp;id='.$post->code()));
	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/galleries/lang/");
	$xoopsMailer->setFromEmail($fmail);
	$xoopsMailer->setFromName($fname);
	$xoopsMailer->setToEmails($tmail);
	$xoopsMailer->setSubject(sprintf(_MS_GS_SUBJECT, $tname));
	if (!$xoopsMailer->send(true)){
		redirect_header(base64_decode($return),2,$xoopsMailer->getErrors());
	} else {
		redirect_header($user->userURL().'img/'.$img->id().'/',1,__('E-card sent successfully!','galleries'));
	}
	
}


/**
* @desc Visualiza la postal
**/
function viewPostcard(){

	global $tpl, $xoopsModule, $xoopsModuleConfig, $xoopsModuleConfig, $mc, $xoopsUser, $id;
	
	include_once XOOPS_ROOT_PATH.'/class/template.php';
    $tpl = new XoopsTpl();
    $mc =& $xoopsModuleConfig;
		
	if (!$xoopsUser){
		redirect_header(XOOPS_URL.'/user.php#register', 1, _MS_GS_ERRUSR);
		die();
	}

	//Verificamos si la postal existe
	$post = new GSPostcard($id);
	if($post->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/',1,_MS_GS_ERRPOSTEXIST);
		die();
	}

	
	$img = new GSImage($post->image());
	if ($img->isNew()){
		redirect_header(XOOPS_URL.'/modules/galleries/', 1, _MS_GS_ERRIMG);
		die();
	}
	
	$user = new GSUser($img->owner(), 1);
	$file = $user->filesPath().'/'.$img->image();
	list($ancho,$alto) = getimagesize($file);
	
	$tpl->assign('gs_url', XOOPS_URL.'/modules/galleries');
	$tpl->assign('img', array('id'=>$img->id(),'width'=>$ancho,'height'=>$alto,
			'url'=>$user->filesURL().'/'.$img->image(),'link'=>$user->userURL().'img/'.$img->id().'/'));
	$tpl->assign('title', $post->title());
	$tpl->assign('message', $post->message());
	$tpl->assign('lang_says', sprintf(_MS_GS_SAYS, $post->name()));
	$tpl->assign('xoops_pagetitle', sprintf(_MS_GS_PTITLE, $post->title()).' &raquo; '.$mc['section_title']);
	$tpl->assign('lang_see', _MS_GS_SEE);
	$tpl->assign('user_link', $user->userURL().'browse/'.$img->id().'/');
	$tpl->assign('lang_seeuser', _MS_GS_SEEUSER);
	$tpl->assign('preview', 0);
    
    RMTemplate::get()->add_xoops_style('postcard.css', 'galleries');

	//Actualizar datos de postal
	$post->setViewed(1);
	$post->save();
			
	echo $tpl->fetch("db:gs_postcard.html");
}


$postcard = !isset($postcard) ? '' : $postcard;

switch($postcard){
	case 'preview':
		previewPostcard();
		break;
	case 'send':
		sendPostcard();
		break;
	case 'view':
		viewPostcard();
		break;
	default:
		newPostcard();
		break;
}

?>
