/**
* @param string Nombre dle formulario
* @param string c Nombre del campo
**/
function selectGSUsers(f,c,limite,url,width,height,type,all,toeval){
	form = $(f);
	campo = form.elements[c];
	var query = '';
	if (type==1 && campo!=undefined){
		if (campo.length==undefined){
			query = campo.value;
		} else {
			for(var i=0;i<campo.length;i++){
				if (!campo[i].checked) continue;
				var val = campo[i].value;
				query += query=='' ? val : ','+val;
			}
		}
	} else if(campo!=undefined) {
		query = campo.value;
	}
	
	users = openWithSelfMain(url+'/modules/galleries/include/users.php?field='+c+'&limit='+limite+'&s='+query+'&type='+type+'&all='+all+'&eval='+toeval, 'users', width,height, true,'');
	centerWindow(users,600,300);
	
}
