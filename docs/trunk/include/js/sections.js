$(document).ready(function(){
       
    $("ol#root-0").nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			maxLevels: 10,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		});
    
    $("#start-sortable").click(function(){
        $(this).fadeOut('fast');
        $("#table-sections").fadeOut('fast', function(){
            $("#sections-sortable").fadeIn('fast');
        });
    });
    
    $(".cancel-sortable").click(function(){
        $("#sections-sortable").fadeOut('fast', function(){
            $("#table-sections").fadeIn('fast');
        });
        $("#start-sortable").fadeIn('fast');
    });
    
    $(".save-sortable").click(function(){
        
        s = $('ol#root-0').nestedSortable('serialize');
        alert(s);
        
    });
    
});