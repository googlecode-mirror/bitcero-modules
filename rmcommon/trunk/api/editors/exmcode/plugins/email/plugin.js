x.add_plugin('email', {
    show: function(){
        x.popup({
            width: 300,
            height: 200,
            title: 'Insert Email',
            url: x.url+'/plugins/email/mail.html'
        });
    },
    insert: function(){
        
    }
});

x.add_button('email',{
   name : 'email', 
   title : 'Insert email',
   alt : 'Insert email',
   cmd : 'show',
   plugin : 'email',
   row: 'bottom',
   icon: x.editor_path+'/images/mail.png'
});