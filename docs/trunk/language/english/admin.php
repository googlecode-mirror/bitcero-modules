<?php
// $Id$
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('_AS_AH_ID','ID');
define('_AS_AH_REFERENCES','Insert References');
define('_AS_AH_FIGURES','Insert Figures');
define('_AS_AH_RECOMMEND','Highlight');
define('_AS_AH_NORECOMMEND','Normal');

//Errores globales
define('_AS_AH_SESSINVALID','Sorry, your ID sesion is not longer valid');
define('_AS_AH_DBOK','Database succesfully updated!');
define('_AS_AH_DBERROR','Error while doing this action');
define('_AS_AH_ERRORS','Errors while doing this action: <br />');
define('_AS_AH_ALLPERM','This action will delete the data permanently.');
define('_AS_AH_SHOWING', 'Showing elements <strong>%u</strong> to <strong>%u</strong> from <strong>%u</strong>.');

switch (AH_LOCATION){
    
    case 'index':
        
        define('_AS_AH_GOMOD','View Module');
        define('_AS_AH_CLICK','Click Here');
        define('_AS_AH_RES','Resources');
        define('_AS_AH_RESNUM','%u total');
        define('_AS_AH_RESAPPROVE','%u Approved');
        define('_AS_AH_RESNOAPPROVE','%u not Approved');
        define('_AS_AH_SECS','Sections');
        define('_AS_AH_SECSNUM','%u Sections');
        define('_AS_AH_EDITS','Modified');
        define('_AS_AH_EDITSNUM','%u Waiting');
        define('_AS_AH_REFS','References');
        define('_AS_AH_REFSNUM','%u References');
        define('_AS_AH_FIGS','Figures');
        define('_AS_AH_FIGSNUM','%u Figures');
        define('_AS_AH_HELP','Help');
        
        define('_AS_AH_ACCESS','The module is configured to use the capabilities of <strong>mod_rewrite</strong> for management of
        			the urls, however you must edit the file .htaccess in order to activate the redirection from other directory. Please include in
        			htaccess file the next code. Remember, you must replace the folder names. (eg. wiky by docs).');
        define('_AS_AH_SHOWCODE','Show Code');
        
        break;

	case 'resources':
		
		define('_AS_AH_RESOURCES','Resources');
		define('_AS_AH_NEWRESOURCE','New Resource');
		
		define('_AS_AH_RESEXIST','Existing Resources');
		define('_AS_AH_TITLE','Title');
		define('_AS_AH_DATE','Creation Date');
		define('_AS_AH_APPROVE','Approved');
		define('_AS_AH_PUBLIC','Public');
		define('_AS_AH_QUICK','Quick Index');
		define('_AS_AH_QUICKNO','This option does not apply when item is an article');
		define('_AS_AH_PUB','Publish');
		define('_AS_AH_NOPUB','Not publish');
		define('_AS_AH_NOQUICK','Wthout quick index');
		define('_AS_AH_RESULT','Results');
		define('_AS_AH_SECTIONS','Sections');
		define('_AS_AH_APPROVRES','Approve');
		define('_AS_AH_NOAPPROV','Not approved');
		define('_AS_AH_SUBJECT','Publication %s approved');
		
		//Formulario
		define('_AS_AH_EDITRESOURCE','Edit resource');
		define('_AS_AH_NAMEID','Short name');
		define('_AS_AH_DESC','Description');		
		define('_AS_AH_IMAGE','Image');
		define('_AS_AH_IMAGEACT','Current Image');
		define('_AS_AH_EDITORS','Editors');
		define('_AS_AH_APPROV','Automatically approve editors content');
		define('_AS_AH_GROUPS','Groups with access rights');
		define('_AS_AH_SHOWINDEX','Show index to restricted users');
		define('_AS_AH_OWNER','Resource owner');
		define('_AS_AH_APPROVEDRES','Approve resource');
		define('_AS_AH_FEATURED','Highlight resource');

		define('_AS_AH_DELETECONF','Do you really wish to delete the resource <strong>%s</strong>');
		
		define('_AS_AH_ADV','Note: sections, contents, references and figures belonging to this publication will be delete. <br />');

		//Errores
		define('_AS_AH_IDNOTVALID','Resource not valid');
		define('_AS_AH_NOTEXIST','Resource not existing');
		define('_AS_AH_ERRTITLE','Existing resource title');
		define('_AS_AH_ERRIMAGE','Error while saving the image');
		define('_AS_AH_NOTRESOURCE','You must select at least a resource');		
		define('_AS_AH_IDNOT','Resource %s not valid <br />');
		define('_AS_AH_NOEXIST','Resource %s not existing <br />');
		define('_AS_AH_NOSAVE','Error while saving %s <br />');
		
		break;
		
	case 'sections':
		define('_AS_AH_SECTIONS','Sections');
		define('_AS_AH_NEWSECTIONS','Create Section');
		define('_AS_AH_EDITSECTION','Edit Section');

		define('_AS_AH_TITLE','Title');
		define('_AS_AH_ORDER','Order');
		define('_AS_AH_RESOURCES','Resources');
		define('_AS_AH_RESOURCE','Resource');
		define('_AS_AH_EXIST','Existing Sections');
		define('_AS_AH_NOTRES','Select a resource to see its sections');
		define('_AS_AH_SAVE','Save Changes');

		//Formulario
		define('_AS_AH_EDITSECTIONS','Edit section');
		define('_AS_AH_SECTION','Root Section');
		define('_AS_AH_CONTENT','Content');
		define('_AS_AH_FUSER','Author');
		define('_AS_AH_DELETECONF','Do you really wish to delete the section <strong>%s</strong>');
		define('_AS_AH_ADV','Note: The contents belonging to this section will be delete. <br />');
		define('_AS_AH_REFFIG','The references and figures only will be available after save the section.');
		define('_AS_AH_SAVENOW','Save');
		define('_AS_AH_SAVERET','Save and Continue Editing');
		define('_AS_AH_SHORTNAME','Short Name');

		//Errores
		define('_AS_AH_ERRTITLE','Already exist another section with the same title');
		define('_AS_AH_NOTRESOURCE','You did not provide a resource');
		define('_AS_AH_NOTEXIST','Publication not existing');
		define('_AS_AH_NOTSECTION','Section not valid');
		define('_AS_AH_NOTEXISTSEC','Section not existing');
		define('_AS_AH_NOTVALID','Section %s not valid <br />');
		define('_AS_AH_NOTEXISTSECT','Section %s not existing <br />');
		define('_AS_AH_NOTSAVEORDER','Error while updating %s <br />');
		
   		break;

	case 'references':
	
		define('_AS_AH_TITLE','Title');
		define('_AS_AH_INSERT','Insert');
		define('_AS_AH_SECTION','Section');
		define('_AS_AH_EXIST','Existing References');
		define('_AS_AH_CONFIRM','Do you really wish to delete the selected references? \n This action will delete the data permanently.');
		define('_AS_AH_RESULT','Results');
		define('_AS_AH_SEARCH','Search');
		define('_AS_AH_REFEREN','References');

		//Formulario
		define('_AS_AH_NEW','New Reference');
		define('_AS_AH_REFERENCE','Reference');
		define('_AS_AH_EDIT','Edit Reference');
		
		
		//Errores
		define('_AS_AH_RESOURCE','Publication not valid, The reference can not be inserted');
		define('_AS_AH_RESNOEXIST','Publication not existing, The reference can not be inserted');
		define('_AS_AH_SECNOVALID','The Section is not valid. The reference can not be inserted');
		define('_AS_AH_SECNOEXIST','The specified section does not exist. The reference can not be inserted');
		define('_AS_AH_TITLEEXIST','Existing Title, the reference can not be inserted');
		define('_AS_AH_REFNOTVALID','Reference %s not valid <br />' );
		define('_AS_AH_REFNOTEXIST','Reference %s not existing <br />');
		define('_AS_AH_REF','You must select at least a reference');
		define('_AS_AH_NOTREF','Reference not valid');
		define('_AS_AH_NOTREFEXIST','Reference not existing');
		
		break;
		
	case 'figures':
		define('_AS_AH_DESC','Description');
		define('_AS_AH_EXIST','Existing Figures');
		define('_AS_AH_INSERT','Insert');
		define('_AS_AH_RESOURCE','Resource not valid, the figure can not be inserted');
		define('_AS_AH_RESNOEXIST','Resource not existing, the figure can not be inserted');
		define('_AS_AH_CONFIRM','Do you really wish to delete the selected figures? \n This action will delete the data permanently.');
		define('_AS_AH_SEARCH','Search');

		//Formulario
		define('_AS_AH_NEWF','Create Figure');
		define('_AS_AH_EDITF','Edit Figure');
		define('_AS_AH_FIG','Figure');	
		define('_AS_AH_ALIGN','Alignment');	
		define('_AS_AH_CLASS','Class');
		define('_AS_AH_WIDTH','Width');
		define('_AS_AH_BORDER','Border');
		define('_AS_AH_BGCOLOR','Background Color');
		define('_AS_AH_FONT','Font');
		define('_AS_AH_FONTSIZE','Font Size');
		define('_AS_AH_MARGIN','Margin');
		define('_AS_AH_STYLE','Style');
		define('_AS_AH_NONE','None');
		define('_AS_AH_LEFT','Left');
		define('_AS_AH_RIGHT','Right');
		define('_AS_AH_LEFTV','Left');
		define('_AS_AH_RIGHTV','Right');

		//Errores
		define('_AS_AH_NOTFIG','Invalid figure');
		define('_AS_AH_NOTFIGEXIST','Figure not existing');
		define('_AS_AH_FIGS','You must select at least a figure');
		define('_AS_AH_NOTVALID','Invalid %s figure <br />');
		define('_AS_AH_NOTEXIST','Figure %s not existing <br />');

		break;
	
	case 'refs':
		define('_AS_AH_REFS','References');
		define('_AS_AH_TITLE','Title');
		define('_AS_AH_TEXT','Reference');
		define('_AS_AH_RESOURCE','Resource');
		define('_AS_AH_EXIST','Existing References');
		define('_AS_AH_SEARCH','Search');
		define('_AS_AH_DELETECONF','Do you really wish to delete the selected references?');
		define('_AS_AH_RESULT','Results');
		
		//Formulario
		define('_AS_AH_EDIT','Edit Reference');
		
		//Errores
		define('_AS_AH_TITLEEXIST','This title already exists, The reference could not be inserted');
		define('_AS_AH_NOTREF','You must provide ate least one reference to delete');
		define('_AS_AH_NOTVALID','Invalid %s reference <br />');
		define('_AS_AH_NOTEXIST','The reference %s does not exists <br />');
		define('_AS_AH_NOTDELETE','The reference %s was not delete<br />');
		
		break;
		
	case 'figs':
		define('_AS_AH_FIGS','Figures');
		define('_AS_AH_EXIST','Existing Figures');
		define('_AS_AH_DESC','Description');
		define('_AS_AH_RESOURCE','Resource');
		define('_AS_AH_SEARCH','Search');
		define('_AS_AH_DELETECONF','Do you really wish to delete the selected figures?');
		define('_AS_AH_RESULT','Results');

		//Formulario
		define('_AS_AH_EDIT','Edit Figure');
		define('_AS_AH_FIG','Figure');	
		define('_AS_AH_ALIGN','Alignment');	
		define('_AS_AH_CLASS','Class');
		define('_AS_AH_WIDTH','Width');
		define('_AS_AH_BORDER','Border');
		define('_AS_AH_BGCOLOR','Background Color');
		define('_AS_AH_FONT','Font');
		define('_AS_AH_FONTSIZE','Font Size');
		define('_AS_AH_MARGIN','Margin');
		define('_AS_AH_STYLE','Style');
		define('_AS_AH_NONE','None');
		define('_AS_AH_LEFT','Left');
		define('_AS_AH_RIGHT','Right');
		define('_AS_AH_LEFTV','Left');
		define('_AS_AH_RIGHTV','Right');
		
		//Errores
		define('_AS_AH_REFNOTVALID','Invalid reference');
		define('_AS_AH_REFNOTEXIST','This reference does not exists');
		define('_AS_AH_NOTFIG','You must provide at least a figure to delete');
		define('_AS_AH_NOTVALID','The Figure %s is not valid<br />');
		define('_AS_AH_NOTEXIST','The Figure %s does not exists<br />');
		define('_AS_AH_NOTDELETE','The Figure %s was not deleted<br />');
		define('_AS_AH_FIGNOTVALID','Figure not valid');
		define('_AS_AH_FIGNOTEXIST','This figure does not exists');

		break;
	
	case 'editions':
		
		define('_AS_AH_EDITLOC','Modified Elements');
		define('_AS_AH_EDITREVLOC','Reviewing %s');
		define('_AS_AH_EDITEDTLOC','Editing %s');
		define('_AS_AH_EDITS','Modifications');
		define('_AS_AH_EDITSTITLE','Existing Modifications');
		define('_AS_AH_CONFIRMMSG','Do you really wish to delete the edited content "%s"?');
		define('_AS_AH_DELCONF','Do you really wish to delete the selected items?');
		
		// Formulario
		define('_AS_AH_RESOURCE','Resource');
		define('_AS_AH_CONTENT','Content');
		define('_AS_AH_SECTION','Root Section');
		define('_AS_AH_ORDER','Order');
		define('_AS_AH_FUSER','Editor');
		define('_AS_AH_EDITSECTIONS','Content Edition');
		define('_AS_AH_SAVENOW','Save Changes');
		
		// Tabla
		define('_AS_AH_EDITEDTITLE','Edited Title');
		define('_AS_AH_ORINGINALTITLE','Original Title');
		define('_AS_AH_MODIFIED','Modified');
		define('_AS_AH_BY','By');
		define('_AS_AH_REVIEW','Review');
		define('_AS_AH_APPROVE','Approve');
		define('_AS_AH_VIEW','See Content');
		
		// Revisión
		define('_AS_AH_ORIGINAL','Oringal Content');
		define('_AS_AH_EDITED','Edited Content');
		define('_AS_AH_TITLE','Title');
		
		// ERRORES
		define('_AS_AH_NOID','You must specify an element to do this action');
		define('_AS_AH_NOTEXISTS','The specified element does not exists');
		define('_AS_AH_NOTEXISTSSEC','The original section does not exists.');
		define('_AS_AH_ERRORSONAPPROVE','Some errores happened while doing this operation');
		define('_AS_AH_ERRTITLE','Already exists a section with the same title');
		
		break;

}
