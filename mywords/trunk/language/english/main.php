<?php
// $Id: main.php 13 2009-08-31 00:45:24Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('_MS_MW_PERMALINK','Permalink to %s');
define('_MS_MW_PUBLISH','Published on %s at %s by %s');
define('_MS_MW_COMMENTON','Comment about %s');
define('_MS_MW_NUMCOMS','<strong>%u</strong> Comments &raquo;');
define('_MS_MW_NUMCOMSL','%u Comments');
define('_MS_MW_COMS','Comments');
define('_MS_MW_NUMTRACKSL','%u Trackbacks');
define('_MS_MW_CONTINUE','Keep on reading %s &raquo;');
define('_MS_MW_CATEGOS','Published in:');
define('_MS_MW_SENDCOMMENT','Send a comment');
define('_MS_MW_NAME','Name');
define('_MS_MW_MAIL','Mail');
define('_MS_MW_NOPUBLISH','It will not be published');
define('_MS_MW_YOUCANUSE','You can use the following tags:<br />%s');
define('_MS_MW_USERSAY','<strong>%s</strong> say:');
define('_MS_MW_WRITECOM','Write Comment &raquo;');
define('_MS_MW_COMMOK','You comment has been added. Thanks');
define('_MS_MW_NEXTPAGE','Next Page');
define('_MS_MW_PREVPAGE','Previous Page');
define('_MS_MW_NOAUTHCOM','Sorry, you are not authorized to send commnets');
define('_MS_MW_COMPENDING','You comment has been sent and it is waiting to be approve for the administrator. Thanks');
define('_MS_MW_BLOGNAME','Blog:');
define('_MS_MW_URL','URL:');
define('_MS_MW_SECCODE','Security Code');
define('_MS_MW_ANONYMOUS','Anonymous');
define('_MS_MW_READS','<strong>%s</strong> Reads');
define('_MS_MW_ALLOWEDTAGS','HTML alloed tags: <code>%s</code>');

// Categorías (24/02/2009)
define('_MS_MW_POSTSINCAT','Posts in &#8216;%s&#8217; Category');

// Authors
define('_MS_MW_POSTSFROMAUTHOR','&#8216;%s&#8217; Posts');

# FORMULARIO DE ENVIO DE ARTÍCULOS
define('_MS_MW_FORMTITLE','Send Article');
define('_MS_MW_EDITINGTITLE','Editing "%s"');
define('_MS_MW_POSTTITLE','Article Title');
define('_MS_MW_CONTENT','Article Content');
define('_MS_MW_POSTCATS','Categories');
define('_MS_MW_SENDTRACKS','Send Trackbacks to:');
define('_MS_MW_TRACKSDESC','Devide multiple URIs with a space.<br /><a href="http://es.wikipedia.org/wiki/TrackBack" target="_blank">Trackbacks</a>');
define('_MS_MW_EXCERPT','Coment for the trackbacks');
define('_MS_MW_EXCERPTDESC','This text is sent as comment while doing  a ping to the linked article. If it is blank the module will send the first 50 words to the body article');
define('_MS_MW_SAVEANDRETURN','Guardar y continuar editando');
define('_MS_MW_SAVE','Save');
define('_MS_MW_BUTPUBLISH','Publish');
define('_MS_MW_DBOK','Data Base succesfuly updated');
define('_MS_MW_SECURITYCODE','Security Code');
define('_MS_MW_OPTIONALDATA','Optional Data');
define('_MS_MW_TRACKSPINGED','Trackbacks Sent');
define('_MS_MW_POSTFRIEND','Title for the friendly URL');
define('_MS_MW_ONLYADVANCE','Show only advance onlyin the home page');
define('_MS_MW_ONLYADVANCE_DESC','Show only a determine number of words when the article is listed in the home page o within the categories.');
define('_MS_MW_STATUS','Article Status');
define('_MS_MW_PUBLIC','Publico');
define('_MS_MW_PRIVATE','Private');

# ERRORES
define('_MS_MW_ERRNOPOST','The specify article is not valid');
define('_MS_MW_ERRNAME','Please write your name');
define('_MS_MW_ERRMAIL','Please write your email');
define('_MS_MW_ERRTEXT','You had not written in the comment');
define('_MS_MW_ERRMAILINV','The mail you provided it is not valid');
define('_MS_MW_ERRCOMM','While sending your comment an error happened. Please try it again');
define('_MS_MW_ERRCATFOUND','We could not find the specified category');
define('_MS_MW_ERRCODE','The security code entered is not valid');

define('_MS_MW_URLMISSING','It is necessary you specify the URL you link us');
define('_MS_MW_NOTITLE','Please, provide a title for your comment');
define('_MS_MW_NOEXCERPT','You must provide the text for the comment');
define('_MS_MW_NOBLOGNAME','You has not specified your blog name');
define('_MS_MW_NOPINGS','Trackbacks disabled');
define('_MS_MW_EXISTSTRACK','Already exist a reference to a trackback with the same title from your blog');
define('_MS_MW_ERRDB','An error happened, please try it later');

define('_MS_MW_ERRTOKEN','Sesion Id not valid');
define('_MS_MW_UNABLESEND','The article post is disabled.');
define('_MS_MW_YOUNOTSEND','You are not authorized to sen articles');
define('_MS_MW_ERRTITLE','Please provide a tittle for this article');
define('_MS_MW_ERRFRIENDTITLE','Please provide a title for the friendly url');
define('_MS_MW_ERRSUBMITTEXT','You had not written the content for this article');
define('_MS_MW_ERRCATS', 'You must select at least a category to this article');
define('_MS_MW_ERREXISTS','Alreday exist an article with the same name for today');
define('_MS_MW_DBERROR','While doing this operation an error happened. Please try it again.');
define('_MS_MW_ERRFRIENDNAME','Error in the friendly title');
define('_MS_MW_ERRNOACCESS','Sorry, you do not have enough privileges to read articles within this category');

?>