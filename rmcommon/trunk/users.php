<?php
// $Id: rss.php 825 2011-12-09 00:06:11Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file allow to manage users registered.
* plugins can extend this file functionallity
*/

// Constant to specify the internal location
// Could be useful for themes, plugins and modules
define('RMCLOCATION', 'users');

include '../../include/cp_header.php';

/**
* Add some menu options
*/
function menu_options(){
	global $exmTpl;
	
    RMTemplate::get()->add_menu_option(__('New user','rmcommon'), 'users.php?action=new');
    RMTemplate::get()->add_menu_option(__('All users','rmcommon'), 'users.php?show=all');
    RMTemplate::get()->add_menu_option(__('Only actives','rmcommon'), 'users.php?show=actives');
    RMTemplate::get()->add_menu_option(__('Only inactives','rmcommon'), 'users.php?show=inactives');
    RMTemplate::get()->add_menu_option(__('Additional data','rmcommon'), 'users.php?action=meta');
}

/**
 * Get the formated SQL to query the database
 */
function formatSQL(){
	global $op;

	$keyw = '';
	$email = '';
	$url = '';
	$srhmethod = '';
	$from = '';
    $login1 = ''; $login2 = ''; $register1 = ''; $register2 = '';
	$posts1 = ''; $posts2 = ''; $mailok = -1; $actives = -1;
    $show = '';

    $tpl = RMTemplate::get();
    $sql = '';
    $tcleaner = TextCleaner::getInstance();
	
	foreach ($_REQUEST as $k => $v){
		$$k = $tcleaner->addslashes($v);
	}
    
    $tpl->assign('srhkeyw', $keyw);
    $tpl->assign('srhemail', $email);
    $tpl->assign('srhurl', $url);
    $tpl->assign('srhsrhmethod', $srhmethod);
    $tpl->assign('srhfrom', $from);
	
	if ($show=='inactives'){
		$sql = "level<=0 AND ";
	} elseif ($show=='actives'){
		$sql = "level>0 AND ";
	}
	
	if ($keyw == '' && $email == '' && $url == '' && $from == '' 
		&& $login1 == '' && $login2 == '' && $register1 == '' && $register2 == '' && $posts1 == ''
		&& $posts2 == '' && $mailok == -1 && $actives == -1){
		
		if ($show=='inactives'){
			$sql = " level<=0";
		} elseif ($show=='actives'){
			$sql = " level>0";
		}
		
		$tpl->assign('display_adv', 'display: none;');
		// Extend SQL with plugins
		// API:
		$sql = RMEvents::get()->run_event('rmcommon.users.getsql', $sql);
		
		return $sql!='' ? "WHERE $sql": '';
	
	}
	
	$or = false;
	$ao = $srhmethod;
	$show = false;
		
	if ($keyw!=''){
		$sql .= "uname LIKE '%$keyw%' $ao name LIKE '%$keyw%'";
		$or = true;
	}
	
	if ($email!=''){
		$sql .= ($or ? " $ao " : '')."email LIKE '%$email%'";
		$or = true;
		$show = true;
	}
	
	if ($url!=''){
		$sql .= ($or ? " $ao " : '')."url LIKE '%$url%'";
		$or = true;
		$show = true;
	}
	
	if ($from!=''){
		$sql .= ($or ? " $ao " : '')."user_from LIKE '%$from%'";
		$or = true;
		$show = true;
	}	

	if ($login1!=''){
		$sql .= ($or ? " $ao " : '').($login2!='' ? '(' : '')."last_login>='$login1'";
		$or = true;
		$show = true;
	}
	
	if ($login2!=''){
		$sql .= ($or ? ($login1!='' ? ' AND ' : " $ao ") : '')."last_login<='$login2'".($login1!='' ? ')' : '');
		$or = true;
		$show = true;
	}
	
	if ($register1!=''){
		list($year, $month, $day) = explode("-", $register1);
		$time = mktime(0,0,0,$month,$day,$year);
		$sql .= ($or ? " $ao " : '').($register2!='' ? '(' : '')."last_login>='$time'";
		$or = true;
		$show = true;
	}
	
	if ($register2!=''){
		list($year, $month, $day) = explode("-", $register2);
		$time = mktime(0,0,0,$month,$day,$year);
		$sql .= ($or ? ($register1!='' ? ' AND ' : " $ao ") : '')."last_login<='$time'".($register1!='' ? ')' : '');
		$or = true;
		$show = true;
	}
	
	if ($posts1>0){
		$sql .= ($or ? " $ao " : '').($posts2!='' ? '(' : '')."posts>='$posts1'";
		$or = true;
		$show = true;
	}
	
	if ($posts2>0){
		$sql .= ($or ? ($posts1!='' ? ' AND ' : " $ao ") : '')."posts<='$posts2'".($posts1!='' ? ')' : '');
		$or = true;
		$show = true;
	}
	
	if ($mailok>-1){
		$sql .= ($or ? " $ao " : '')."user_mailok='$mailok'";
		$or = true;
	}
	
	if ($actives>-1){
		$sql .= ($or ? " $ao " : '')."level".($actives>0 ? ">'0'" : "<='0'");
		$or = true;
	}
	
	if ($show){ $tpl->assign('display_adv', ''); } else { $tpl->assign('display_adv', 'display: none;'); }
	
	$rtsql = $sql!='' ? "WHERE $sql" : '';
	// ** API **
	// Event to modify, if it is neccesary, the sql string to query de database
	$rtsql = RMEvents::get()->run_event('rmcommon.users.getsql', $rtsql);
    return $rtsql;
	
}

/**
* Shows all registered users in a list with filter and manage options
*/
function show_users(){
    global $exmApp, $exmTpl;
    
    RMTemplate::get()->add_style('users.css','rmcommon');
    RMTemplate::get()->add_style('js-widgets.css');

    //Scripts
    RMTemplate::get()->add_local_script('users.js','rmcommon','include');
    RMTemplate::get()->add_local_script('jquery.checkboxes.js','rmcommon','include');
    
    $form = new RMForm('', '', '');
    // Date Field
    $login1 = new RMFormDate('','login1', '');
    $login2 = new RMFormDate('','login2', '') ;

    // Registered Field
    $register1 = new RMFormDate('','registered1', '');
    $register2 = new RMFormDate('','registered2', '');
    
    xoops_cp_location(__('Users Management','rmcommon'));
    
    menu_options();
        
    // Show the theme
    xoops_cp_header();

    $db = XoopsDatabaseFactory::getDatabaseConnection();            
            
    $sql = "SELECT COUNT(*) FROM ".$db->prefix("users")." ".formatSQL();
    
    $page = rmc_server_var($_REQUEST, 'pag', 1);
    $limit = rmc_server_var($_REQUEST, 'limit', 15);
    $order = rmc_server_var($_GET,'order','uid');
    list($num) = $db->fetchRow($db->query($sql));
    
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;
    
    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('users.php?limit='.$limit.'&order='.$order.'&pag={PAGE_NUM}');
    
    $sql = str_replace("COUNT(*)",'*', $sql);
    $sql .= "ORDER BY $order LIMIT $start, $limit";
    $result = $db->query($sql);
    
    $users = array();
    $user = new XoopsUser();
    $t = array(); // Temporary
    while ($row=$db->fetchArray($result)){
    	$user->assignVars($row);
        $t = $user->getValues();
        $t['groups'] = $user->groups();
        $t = RMEvents::get()->run_event('rmcommon.loading.users.list', $t);
        $users[] = $t;
        $t = array();
    }
    
    $xgh = new XoopsGroupHandler($db);
    $users = RMEvents::get()->run_event('rmcommon.users.list.loaded', $users);
    
    // Users template
    include RMTemplate::get()->get_template('rmc_users.php','module','rmcommon');
    
    xoops_cp_footer();
}

/*
* Show the form to create or edit a user
*/
function user_form($edit = false){
	
    $query = rmc_server_var($_GET, 'query', '');
    $query = $query=='' ? '' : base64_decode($query);
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    if ($edit){
        $uid = rmc_server_var($_GET, 'uid', 0);
        if ($uid<=0)
            redirectMsg('users.php?'.$query, __('The specified user is not valid!','rmcommon'), 1);
        
        $uh = new XoopsUserHandler($db);
        $user = $uh->get($uid);
        if ($user->isNew())
            redirectMsg('users.php?'.$query, __('The specified user does not exists!','rmcommon'), 1);
    }
    
    RMFunctions::create_toolbar();
    
    xoops_cp_location("<a href='users.php'>".__('Users Management','rmcommon')."</a> &raquo; ".__($edit ? 'Editing User' : 'Adding new user','rmcommon'));
    xoops_cp_header();
	
    $form = new RMForm(__($edit ? 'Editing User' : 'Add new user','rmcommon'), 'user_form', 'users.php');
	
	// Uname
	$form->addElement(new RMFormText(__('Username','rmcommon'), 'uname', 50, 50, $edit ? $user->uname() : ''), true);
	$form->element('uname')->setDescription(__("This field also will be the user login name.",'rmcommon'));
	
	// Full Name
	$form->addElement(new RMFormText(__('Full name','rmcommon'), 'name', 50, 150, $edit ? $user->name() : ''));
	$form->element('name')->setDescription(__("This field must contain firstname and lastname.",'rmcommon'));
	
	// Email
	$form->addElement(new RMFormText(__('Email address','rmcommon'), 'email', 50, 150, $edit ? $user->email() : ''), true, 'email');
	
	// Password
	$form->addElement(new RMFormText(__($edit ? 'New password' : 'Password','rmcommon'), 'password', 50, 50, '', true), $edit ? false : true);
	$form->element('password')->setDescription(__('The password should be at least eight characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &','rmcommon'));
	$form->addElement(new RMFormText(__('Repeat Password','rmcommon'), 'passwordc', 50, 50, '', true), $edit ? false : true);
	$form->element('passwordc')->setDescription(__('Type password again.','rmcommon'));
	
	// Web
	$form->addElement(new RMFormText(__('URL (Blog or personal website)','rmcommon'), 'url', 50, 250, $edit ? $user->getVar('url') : ''));
	
	// Timezone
	$form->addElement(new RMFormTimeZoneField(__('Time zone','rmcommon'), 'timezone', 0, 0, $edit ? $user->getVar('timezone_offset') : ''));
	
	// Groups
	$form->addElement(new RMFormGroups(__('Assign to groups','rmcommon'), 'groups', 1, 1, 3, $edit ? $user->groups() : ''));
	
	// Other options by API
	$form = RMEvents::get()->run_event('rmcommon.user.form', $form, $edit, isset($user) ? $user : null);
	
	// Action
	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
    if ($edit)
        $form->addElement(new RMFormHidden('uid',$user->uid()));
	
	// Submit and cancel buttons
	$ele = new RMFormButtonGroup('');
	$ele->addButton('sbt', __($edit ? 'Edit User' : 'Add user','rmcommon'), 'submit');
	$ele->addButton('cancel', __('Cancel','global'), 'button', 'onclick="history.go(-1);"');
	
	$form->addElement($ele);
	
	$form->display();
	
	xoops_cp_footer();
}

/**
* Save user data
* 
* @param bool Indicates when is a edit
*/
function save_data($edit = false){
    global $xoopsSecurity;
	
    $q = ''; // Query String
    foreach ($_POST as $k => $v){
        $$k = $v;
	if ($k=='XOOPS_TOKEN_REQUEST' || $k=='sbt' || $k=='action' || $k=='password' || $k=='passwordc') continue;
	$q .= $q=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
	
    if (!$xoopsSecurity->check()){
	redirectMsg('users.php?action='.($edit ? 'edit' : 'new').'&'.$q, __('Sorry, you don\'t have permission to add users.','rmcommon'), 1);
	die();
    }
	
    if ($edit){
	if ($uid<=0){
            redirectMsg('users.php', __('The specified user is not valid!','rmcommon'), 1);
            die();
	}
		
        $user = new RMUser($uid);
	if ($user->isNew()){
            redirectMsg('users.php', __('The specified user does not exists!','rmcommon'), 1);
            die();
	}
    } else {
	$user = new RMUser();
    }
	
    // Check uname, password and passwordc
    if ($uname=='' || $email=='' || (!$edit && ($password=='' || $passwordc==''))){
        redirectMsg('users.php?action='.($edit ? 'edit' : 'new').'&'.$q, __('Please fill all required fields and try again!','rmcommon'), 1);
	die();
    }
	
    // Check passwords
    if ($password!=$passwordc){
	redirectMsg('users.php?action='.($edit ? 'edit' : 'new').'&'.$q, __('Passwords doesn\'t match. Please chek them.','rmcommon'), 1);
	die();
    }
	
    // Save user data
    $user->setVar('name', $name);
    $user->setVar('uname', $uname);
    $user->setVar('display_name', $display_name);
    $user->setVar('email', $email);
    if (!$edit) $user->assignVar('user_regdate', time());
    if ($password!='') $user->assignVar('pass', sha1($password));
    $user->setVar('level', 1);
    $user->setVar('timezone_offset', $timezone);
    $user->setVar('url', $url);
    $user->setGroups($groups);
	
    // Plugins and modules can save metadata.
    // Metadata are generated by other dynamical fields
    $user = RMEvents::get()->run_event('rmcommon.add.usermeta.4save', $user);
	
    if ($user->save()){
	$user = RMEvents::get()->run_event($edit ? 'rmcommon.user.edited' : 'rmcommon.user.created', $user);
	redirectMsg('users.php', __('Database updated successfully!','rmcommon'), 0);
    } else {
	redirectMsg('users.php?action='.($edit ? 'edit' : 'new').'&'.$q, __('The users could not be saved. Please try again!','rmcommon').'<br />'.$user->errors(), 1);
    }
	
}

/**
* This function shows a form to send email to single or multiple users
*/
function show_mailer(){
	global $exmTpl, $db, $cp, $exmConfig;
	
	$uid = rmc_server_var($_GET, 'uid', array());
	$query = rmc_server_var($_GET, 'query', '');
	
	if (!is_array($uid) && $uid<=0 || empty($uid)){
		// In admin control panel (side) add_message always must to be called before
		// ExmGUI::show_header()
		RMTemplate::get()->add_message(__('You must select one user at least. Please click on "Add Users" and select as many users as you wish.'), 0);
	}
	
	$uid = !is_array($uid) ? array($uid) : $uid;
	
	xoops_cp_location(__('Sending email to users','rmcommon'));
	
	$form = new EXMForm(__('Send Email to Users','rmcommon'), 'frm_mailer', 'users.php');
	
	$form->addElement(new EXMFormUser(__('Users','global'), 'mailer_users', 1, $uid, 30, 600, 400));
	$form->element('mailer_users')->setDescription(__('Please note that the maximun users number that you can select depends of the limit of emails that you can send accourding to your email server policies (or hosting account policies).','rmcommon'));
	
	$form->addElement(new RMFormText(__('Message subject','rmcommon'), 'subject', 50, 255), true);
	$form->element('subject')->setDescription(__('Subject must be descriptive.','rmcommon'));
	$form->addElement(new EXMRadio(__('Message type','rmcommon'), 'type', ' ', 1, 2));
	$form->element('type')->addOption(__('HTML','global'), 'html', 1, $exmConfig->get_option('editor_type')=='tiny' ? 'onclick="switchEditors.go(\'message\', \'tinymce\');"' : '');
	$form->element('type')->addOption(__('Plain Text','global'), 'text', 0, $exmConfig->get_option('editor_type')=='tiny' ? 'onclick="switchEditors.go(\'message\', \'html\');"': '');
	$form->addElement(new EXMEditor(__('Message content','rmcommon'), 'message', '99%', '300px', ''), true);
	
	$ele = new EXMButtonGroup();
	$ele->addButton('sbt', __('Send E-Mail','rmcommon'), 'submit');
	$ele->addButton('cancel', __('Cancel','rmcommon'), 'button', 'onclick="history.go(-1);"');
	$form->addElement($ele);
	
	$form->addElement(new RMFormHidden('action','sendmail'));
	$form->addElement(new RMFormHidden('query',$query));
	
	$form->display();
	
	$cp->show();
}

/**
* Send mail to selected users using Swift
*/
function send_mail(){
	global $exmConfig;
	extract($_POST);

	// Creating a message
	$mailer = new EXMMailer($type=='html' ? 'text/html' : 'text/plain');
    $mailer->add_exm_users($mailer_users);
    $mailer->set_subject($subject);
    
    $message = $type=='html' ? TextCleaner::getInstance()->to_display($message) : $message;
    
    $mailer->set_body($message);
    
    if (!$mailer->batchSend()){
    	echo "<h3>".__('There was errors while sending this emails','rmcommon')."</h3>";
    	foreach ($mailer->errors() as $error){
			echo "<div class='even'>".$error."</div>";
    	}
    	ExmGUI::show();
		exit();
    }
    
    redirectMsg('users.php?'.base64_decode($query), __('Message sent successfully!','rmcommon'), 0);
	
}

/**
* Deactivate selected users
*/
function activate_users($activate){
    
    foreach($_GET as $k => $v){
        if ($k=='EXM_TOKEN_REQUEST' || $k=='action') continue;
        $q .= $q=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
    }
    
    $uid = rmc_server_var($_GET, 'uid', array());
    
    if (empty($uid))
        redirectMsg('users.php?'.$q, __('No users has been selected','rmcommon'), 1);
    
    $in = '';
    foreach($uid as $id){
        $in .= $in=='' ? $id : ','.$id;
    }
    
    $db = EXMDatabase::get();
    $sql = "UPDATE ".$db->prefix("users")." SET level='$activate' WHERE uid IN($in)";
    
    if ($db->queryF($sql)){
        redirectMsg('users.php?'.$q, __('Users '.($activate ? 'activated' : 'deactivated').' successfully!','rmcommon'), 0);
    } else {
        redirectMsg('users.php?'.$q, __('Users could not be '.($activate ? 'activated' : 'deactivated').'!','rmcommon'), 1);
    }
    
}


// get the action
$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
    case 'new':
	user_form();
	break;
    case 'edit':
        user_form(true);
        break;
    case 'save':
        save_data();
	break;
    case 'saveedit':
        save_data(true);
        break;
    case 'mailer':
    	show_mailer();
    	break;
    case 'sendmail':
    	send_mail();
    	break;
    case 'deactivate':
        activate_users(0);
        break;
    case 'activate':
        activate_users(1);
        break;
    default:
        show_users();
        break;
}