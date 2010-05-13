/**
 * Funciones básicas para la utilización de
 * Ability Help
 */
function ahShowAll(){
	$('ahMoreLink').style.display = 'none';
    $('ahAllIndex').style.display = 'inline'; 
    $('ahPreIndex').style.display = 'none';
}
    
function ahHideAll(){
	$('ahMoreLink').style.display = 'inline';
    $('ahAllIndex').style.display = 'none'; 
    $('ahPreIndex').style.display = 'inline';
}

function showReference(id, colorin){
	
	eles = $('ahRefsContainer').getElementsByTagName("LI");
	for (var i=0; i<eles.length; i++) {
      eles[i].style.background = ''; // negro
	}
	
	$('ref'+id).style.background = colorin;
	
	window.location = '#ref'+id;
}

function swapStar(star,wo){
	
	for (var i=1; i<=star; i++){
		$('r'+i).src=xurl+'/modules/ahelp/images/star'+wo+'.png';
	}
	
}