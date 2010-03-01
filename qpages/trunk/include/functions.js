function qpagesInsertImageTiny(img, titulo, width, height, desc, href){
	var str = '';

	if (href!='')
		str += '<a href="' + href + '" target="_blank">';	
		
	str += '<img src="' + img + '" alt="' + titulo + '" title="' + titulo + '" width="'+width+'" height="'+height+'"';
	if (desc!='')
		str += ' longdesc="' + desc + '"';
		
	str += ' />';
	
	if (href!='')
		str += '</a>';
	
	tinyMCE.execCommand("mceInsertContent", false, str);
}

function qpagesInsertImage(img, titulo, width, height, desc, href){
	var campo = xoopsGetElementById('texto');
	var  str = '';
	if (href!='')
		str += '<a href="' + href + '">';	
		
	str += '<img src="' + img + '" alt="' + titulo + '" title="' + titulo + '" width="'+width+'" height="'+height+'"';
	if (desc!='')
		str += ' longdesc="' + desc + '"';
		
	str += ' />';
	
	if (href!='')
		str += '</a>';
	xoopsInsertText(campo, str);	
}