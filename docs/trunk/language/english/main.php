<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2


//Encabezado
define('_MS_AH_RESOURCE','Select Resource');
define('_MS_AH_FIND','Search');
define('_MS_AH_FINDLABEL','Search');
define('_MS_AH_NEWRES','Create Resource');
define('_MS_AH_VOTED','Best Voted');
define('_MS_AH_POPULAR','Populars');
define('_MS_AH_RECENT','Recents');

define('_MS_AH_LOADING','Loading...');
define('_MS_AH_CLOSE','Close');

define('_MS_AH_HOME','Home');
define('_MS_AH_INDEXPUBLIC','Resource Index');
define('_MS_AH_NOTPERM','You don\'t have rigths to access this resource');
define('_MS_AH_SESSINVALID','Session ID not valid');
define('_MS_AH_DBOK','Database updates successfully');
define('_MS_AH_DBERROR','Error while doing this operation');
define('_MS_AH_ERRORS','Errors while doing this operation: <br />');
define('_AS_AH_REFERENCES','Insert Reference');
define('_AS_AH_FIGURES','Insert Figure');
define('_MS_AH_SHOWING', 'Showing elements <strong>%u</strong> to <strong>%u</strong> from <strong>%u</strong>.');
define('_MS_AH_NOTAPPROVED','Unable to access, resource waiting to be approved');

switch (AH_LOCATION){
	case 'index':
		
		define('_MS_AH_INDEXPUB','Resources Index');
		define('_MS_AH_READINGS','Featured');
		define('_MS_AH_MODIFIEDS','Latest editions');
		define('_MS_AH_VOTES','Best Voted');
		define('_MS_AH_ALL','See All');
		define('_MS_AH_VOTE','%s Votes');
		define('_MS_AH_READS','%s Reads');
		define('_MS_AH_BY','By %s');
		define('_MS_AH_IN','In %s');
		define('_MS_AH_RATING','Rating:');
   		
   		break;
   		
	case 'resources':
	
		define('_MS_AH_INDEX','Index');
		define('_MS_AH_QINDEX','Quick Index');
		define('_MS_AH_SPECIALS','Spacial Pages');
		define('_MS_AH_FIGS','Figures');
		define('_MS_AH_REFS','References');
	
		//Errores
		define('_MS_AH_NOTRESOURCE','The specified resource is not valid');
		define('_MS_AH_NOTEXIST','The specified resource does not exists');
			
		break;
		
	case 'page':

		define('_MS_AH_BACK','Previous');
		define('_MS_AH_INDEX','Index');
		define('_MS_AH_NEXT','Next');

		//Errores
		define('_MS_AH_NOTTEXT','The specified content is not valid');
		define('_MS_AH_NOTEXIST','The specified content does not exists');
		
		break;
	
	case 'content':
		
		define('_MS_AH_LASTEDITED','last edition on %s by %s.');
		define('_MS_AH_CONTENTS','Content');
		define('_MS_AH_CONTENTSSEC','Content of this Section');
		define('_MS_AH_CONTENTSFULL','Full Content');
		define('_MS_AH_PRINT','Print');
		define('_MS_AH_EDIT','Edit');
		define('_MS_AH_RATING', 'Rating:');
		define('_MS_AH_RATE', 'Rate:');
		define('_MS_AH_READS', '<strong>%u</strong> Reads');
		define('_MS_AH_VOTES', '<strong>%u</strong> Votes');
		define('_MS_AH_INDEX', 'Index');
		define('_MS_AH_INDEXMORE','%u more...');
		define('_MS_AH_MORE','More');
		define('_MS_AH_LESS','Less');
		define('_MS_AH_REFS','References');
		define('_MS_AD_PRINTINFO','Document printed from <strong>%s</strong>.<br />(%s)');
		define('_MS_AH_TOP','Top');
		define('_MS_AH_FIGSIN','Figures in %s');
		define('_MS_AH_REFSIN','References in %s');
		
		// ERRORES
		define('_MS_AH_NOID','You have not specified a valid element');
		define('_MS_AH_NOCONTENT','There is not content for this resource.');
		define('_MS_AH_NOALLOWED','You don\'t have autorization to see this resource content.');
		define('_MS_AH_NOPRINT','Operation disabled temporary');
		define('_MS_AH_NOCONTENT_LEGEND','Content for this section has not been created yet, we are working on it.');
		
		break;
		
	case 'references':
		
		// ERRORES
		define('_MS_AH_NOID','Reference not valid');
		define('_MS_AH_NOEXISTS','The specified reference does not exists');
		
		break;
	
	case 'rate':
		
		define('_MS_AH_VOTEOK','Your vote has been submitted.<br />Thanks for Vote!');
		
		// ERRORES
		define('_MS_AH_NOID','The specified resource is not valid');
		define('_MS_AH_NORATE','The provided rate is not valid');
		define('_MS_AH_NODAY','You can vote once a day for every resource.<br />Please try again tomorrow.');
		define('_MS_AH_VOTEFAIL','An error ocurrs while tryng to register your vote. Please try again later.');
		
		break;
		
	case 'publish':
	
		define('_MS_AH_NEWRESOURCE','Create Resource');
		define('_MS_AH_TITLE','Title');
		define('_MS_AH_DESC','Description');
		define('_MS_AH_IMAGE','Image');
		define('_MS_AH_IMAGEACT','Current Image');
		define('_MS_AH_EDITORS','Editors');
		define('_MS_AH_GROUPS','Allowed groups');
		define('_MS_AH_PUBLIC','Public');
		define('_MS_AH_QUICK','Quick Index');
		define('_MS_AH_SHOWINDEX','Show index to not allowed users');
		define('_MS_AH_APPROVED','Approve Resource');
		define('_MS_AH_AUTOAPPR','Automatic approval');
		define('_MS_AH_NOAPPR','The resource will be approved later according to the administrator');
		define('_MS_AH_SUBJECT','New resource %s not approved yet');

		//Errores
		define('_MS_AH_ERRTITLE','A resource with same title already exists');
		define('_MS_AH_ERRIMAGE','Error while saving image');
		define('_MS_AH_ERRPERMGROUP','You don\'t have permissions to create a new resource');
		define('_MS_AH_ERRPERM','The resource creation has been disabled');
		define('_MS_AH_ERRNOTIFY','');

		break;
		
	case 'edit':
	
		define('_MS_AH_ID','ID');
		define('_AS_AH_NEWPAGE','New Página');

		//Formulario de seccion
		define('_MS_AH_NEWSECTION','New Section');
		define('_MS_AH_PUBLISH','Resource');
		define('_MS_AH_TITLE','Title');
		define('_MS_AH_CONTENT','Content');
		define('_MS_AH_PARENT','Root Section');
		define('_MS_AH_ORDER','Order');
		define('_MS_AH_REFFIG','The references and figures only will be available after save the section.');
		define('_MS_AH_FUSER','Author');
		define('_MS_AH_EDIT','Edit Section');
		define('_MS_AH_SAVE','Save');
		define('_MS_AH_SAVERET','Save and Continue Editing');
		define('_MS_AH_EXIST','Existing Sections');
		define('_MS_AH_LIST','Sections List');
		define('_MS_AH_GOTOSEC','Back to Section Content');
		define('_MS_AD_APPROVETIP','The modifications are approved automatically');
		define('_MS_AD_NOAPPROVETIP','The modifications must be approved by the Administrator');

		//Errores
		define('_MS_AH_ERRTITLE','A section with same title already exists');
		define('_MS_AH_ERRRESOURCE','The specified resource is not valid');
		define('_MS_AH_ERRNOTEXIST','The resource does not exists');
		define('_MS_AH_ERRSECTION','The section is not valid');
		define('_MS_AH_ERRNOTSEC','The section does not exists');
		define('_MS_AH_NOTSECTION','You must provide at least one section');
		define('_MS_AH_NOTVALID','The section %s is not valid<br />');
		define('_MS_AH_NOTEXISTSECT','The section %s does not exists<br />');
		define('_MS_AH_NOTSAVEORDER','The order of section "%s" has not been updated<br />');
		define('_MS_AH_NOTPERMEDIT','You do not have rigths to edit');
		
		break;
		
	case 'ref':
		define('_MS_AH_ID','ID');
		define('_MS_AH_TITLE','Title');
		define('_MS_AH_INSERT','Insert Reference');
		define('_MS_AH_NEW','Create Reference');
		define('_MS_AH_EXIST','Existing References');
		define('_MS_AH_RESULT','Results');
		define('_MS_AH_SEARCH','Search');
		define('_MS_AH_REFERENCE','Reference');
		define('_MS_AH_EDIT','Edit Reference');
		define('_MS_AH_REFEREN','References');
		define('_MS_AH_CONFIRM','Do you really wish to delete the selected references? \nThis action will delete all data.');


		//errores
		define('_MS_AH_NOTREF','Invalid reference');
		define('_MS_AH_NOTREFEXIST','Not existing reference');
		define('_MS_AH_ERRRESOURCE','Error resource not valid');
		define('_MS_AH_ERRNOTEXIST','Not existing resource');
		define('_MS_AH_ERRSECTION','Invalid section');
		define('_MS_AH_ERRNOTSEC','Not existing section');
		define('_MS_AH_TITLEEXIST','Existing refernce title');
		define('_MS_AH_NOTPERMEDIT','You are not allowed to edit this section.');
		
		break;
		
	case 'figures':
		define('_MS_AH_ID','ID');
		define('_MS_AH_DESC','Description');
		define('_MS_AH_EXIST','Exisitng Figures');
		define('_MS_AH_INSERT','Insert');
		define('_MS_AH_RES','Invalid resource, The figure can not be inserted');
		define('_MS_AH_RESNOEXIST','Not existing resource, The figure can not be inserted');
		define('_MS_AH_TEXTNOVALID','Invalid content, The figure can not be inserted');
		define('_MS_AH_TEXTNOEXIST','Not exisitng content, The figure can not be inserted');
		define('_MS_AH_CONFIRM','Do you really wish to delete the selected figures? \n This action will delete the data permanently.');
		define('_MS_AH_SEARCH','Search');

		//Formulario
		define('_MS_AH_NEWF','Create Figure');
		define('_MS_AH_EDITF','Edit Figure');
		define('_MS_AH_FIG','Figure');	
		define('_MS_AH_CLASS','Class');
		define('_MS_AH_STYLE','Style');


		//Errores
		define('_MS_AH_NOTFIG','Invalid figure');
		define('_MS_AH_NOTFIGEXIST','Not existing figure');
		define('_MS_AH_FIGS','You must select at least a figure');
		define('_MS_AH_NOTVALID','Invalid figure %s <br />');
		define('_MS_AH_NOTEXIST','Not existing figure %s <br />');
		define('_MS_AH_NOTPERMEDIT','You are not allowed to edit this resource');

		break;
		
	case 'search':
		define('_MS_AH_RESULT', 'Results <strong>%u</strong> to <strong>%u</strong> from <strong>%u</strong>.');
		define('_MS_AH_RESULTED','Results');
		define('_MS_AH_VOTES','Votes');
		define('_MS_AH_READS','Reads');
		define('_MS_AH_NOTWORDSEARCH','None provided any words for search');
		define('_MS_AH_SEARCHS','Search');
		define('_MS_AH_OWNER','By');
		define('_MS_AH_CREATED','Published');
		define('_MS_AH_RESULTS','Results for  "%s" ');
		define('_MS_AH_RESULTS1','Best voted resources');
		define('_MS_AH_RESULTS2','Most popular resources');
		define('_MS_AH_RESULTS3','Recent Resources');
		define('_MS_AH_SEARCH','Search');
		define('_MS_AH_TYPE','Search Type');
		define('_MS_AH_ALLWORDS','All words');
		define('_MS_AH_ANYWORDS','Any word');
		define('_MS_AH_PHRASE','Exact Phrase');
		define('_MS_AH_IN','In');
		define('_MS_AH_RESOURCES','Resources');
		define('_MS_AH_SECTIONS','Contents');
		
		break;
		
}

/**
* @desc Obtiene la fecha formateada correctamente		
*/
function ahFormatDate($time){
	
	return date('M j Y', $time);
	
}
