$(document).ready(function(){
	$("a.show").click(function(){
		
		var id = $(this).attr("id").replace("show-",'');
		
		position = $("#module-"+id).position();
		$("#data-display").hide();
		$("#data-display").css({'top':position.top+'px','left':position.left+'px'});
		
		// Fill data
		var data = "#data-display";
		var source = "#module-"+id;
		
		$(data+" .data_head .mod_image").html($(source+" .mod_image").html());
		$(data+" .data_head .name").html($(source+" .data_storage .realname").html());
		$(data+" .data_description").html($(source+" .data_storage .description").html());
		$(data+" table td.version").html($(source+" .data_storage .version").html());
		
		var author = '';

		if ($(source+" .data_storage .authormail").html()!='')
			author = '<a href="mailto:'+$(source+" .data_storage .authormail").html()+'">'+$(source+" .data_storage .author").html()+'</a>';
		else
			author = $(source+" .data_storage .author").html();
		
		var web = '';

		if ($(source+" .data_storage .authorurl").html()!='')
			web = '<a href="mailto:'+$(source+" .data_storage .authorurl").html()+'">'+$(source+" .data_storage .authorweb").html()+'</a>';
		else
			web = $(source+" .data_storage .authorweb").html();
		
		$(data+" table td.author").html(author);
		$(data+" table td.web").html(web);
		$(data+" table td.license").html($(source+" .data_storage .license").html());
		$(data+" table td.name").html($(source+" .data_storage .name").html());
		
		$("#data-display").slideDown(200);
		
	});
    
    $(".mod_preinstall_container .th a").click(function(){
        var id = $(this).attr("id").replace("down-",'');
        $("#"+id+"-container").slideToggle(600);
    });
    
    $("#install-ok").click(function(){
		$("#install-form").submit();
    })
    
});

function show_module_info(id){
	$("#mod-"+id).slideDown('fast');
}