x.add_plugin('fontsize',{
    init: function(x){
        x.dropdown.add_dropdown(x,'contenido','fontsize');
    }
});

x.add_button('fontsize',{
   name : 'fontsize', 
   title : 'Font Size',
   alt : 'Font Size',
   cmd : 'show',
   plugin : 'fontsize',
   row: 'top',
   type : 'dropdown',
   icon : x.editor_path+'/images/size.png'
});
