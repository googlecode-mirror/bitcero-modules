$(document).ready(function(){
	
	$("div.it_config_tabs li").click(function(){
		id = $(this).attr('id');
		if (id=='' || id==undefined) return;
		
		id = id.replace("li-","");
		
		it_show_tab(id);
		
	});
	
	var eles = $("div.it_config_tabs li");
	id = eles.attr("id").replace("li-",'');
	it_show_tab(id);
	
	
});

function it_show_tab(tab){
	if(tab=='') return;
	
	$("div.it_config_container").hide();
	$("div.it_config_tabs li").removeClass("current");
	
	$("#"+tab).show();
	$("#li-"+tab).addClass("current");
	
}