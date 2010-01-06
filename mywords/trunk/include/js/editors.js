$(document).ready(function(){
    
    $("#form-new-editor").submit(function(){
        
        if($("#new-name").val()==''){
            $("#form-new-editor label[for='new-name']").css("color",'#f00');
            $("#form-new-editor #new-name").css("border-color",'#f00');
            $("#form-new-editor #new-name").focus();
            return false;
        }
        
        if($("#new_user-users-list li").length<=0){
            $("#form-new-editor label[for='new-user']").css("color",'#f00');
            return false;
        }
        
    });
    
});