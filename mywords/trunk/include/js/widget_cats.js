// $Id: widget_cats.js 53 2009-09-18 06:02:06Z i.bitcero $
// --------------------------------------------------------------
// MyWords
// Complete Blogging System
// Author: BitC3R0 <bitc3r0@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$(document).ready(function(){
        
	$("#a-show-new").click(function(){
		$("div#w-catnew-form").slideDown('slow');
	});
	
	$("div#w-catnew-form a").click(function(){
		$("div#w-catnew-form").slideUp('slow');
	});
	
	$("input#create-new-cat").click(function(){
		var name = $("div#w-catnew-form input#w-name").val();
		if (name==''){
			$("label[for='w-name']").slideDown();
			return;
		}
	
		var parent = $("select#w-parent").val();
		
		var params = {
			'XOOPS_TOKEN_REQUEST': $("input#xoops-token").val(),
			'name':name,
			'parent':parent
		};
		
		$.post('ajax/ax-categories.php', params, function(data){
			
			if (data['error']!=undefined){
				alert(data['error']);
				if(data['token'])
					$("input#xoops-token").val(data['token']);
				
				return;
			}
			
			var html = '<label class="cat_label" id="label-'+data['id']+'"><input type="checkbox" name="categories[]" id="categories[]" value="'+data['id']+'" /> '+name+'</label>';
			$("div#w-categos-container").prepend(html);
            $("select#w-parent").prepend('<option value="'+data['id']+'">'+name+'</option>');
			$("label#label-"+data['id']).focus();
			$("label#label-"+data['id']).effect('highlight', {}, 1000);
			$("input#xoops-token").val(data['token']);
			
			$("div#w-catnew-form input#w-name").val('');
			$("select#w-parent option[selected='selected']").removeAttr('selected');
			$("select#w-parent option[value='0']").attr('selected','selected');
			
		},'json');
		
	});
	
});