x.add_plugin('dropdown',{
    init : function(x){
        $("head").append('<link rel="stylesheet" type="text/css" href="'+x.editor_path+'/plugins/dropdown/css/dropdowns.css" />');
    },
    add_dropdown: function(x, content, n){
        
        var d = '<div class="dropdown '+n+'">'+content+'</div>';
        
        $("#"+x.ed+'-ed-container').append(d);
        
    }
});
