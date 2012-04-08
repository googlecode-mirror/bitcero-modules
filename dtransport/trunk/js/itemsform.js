// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Este archivo contiene las funciones e instrucciones
 * necesarias para que el formulario de creación de
 * descargas funcione adecuadamente.
 */

$(document).ready(function(){
    
    $("#dt-down-opts ul li a").click(function(){
        
        if($($(this).attr("href")).is(":visible")) return false;
        
        $("#dt-down-opts .widgets_forms").slideUp("fast");
        $("#dt-down-opts ul li a").removeClass("selected");
        $($(this).attr("href")).slideDown('fast');
        $(this).addClass('selected');
        return false;
    });
    
    $("#frm-items #name").keyup(function(){
        
        if($(this).val()!=''){
            
            if(!$("#down-commands").is(":visible"))
                $("#down-commands").fadeIn('fast');
            
        } else {
            
            if($("#down-commands").is(":visible"))
                $("#down-commands").fadeOut('fast');
            
        }

    });
    
    $("#frm-items #name").change(function(){
        $("#frm-items #name").keyup();
    });
    
    $("#save-data").click(function(){
        
        var msg = $("#down-loader span");
        msg.removeClass();
        
        $("#down-blocker").fadeIn(200, function(){
            msg.html(jsLang.checkForm);
            $("#down-loader").fadeIn(700, function(){
                
                // Verify form fields
                if(!validate()){
                    dt_message(jsLang.errForm, DT_ERROR);
                    dt_close(4);
                    return false;
                }
                dt_message(jsLang.okForm,DT_OK);                
                
                
                
                return false;
            });
        });        
        
        validate();
        
        return false;
    });
    
});

function dt_close(s){
    
    setTimeout('dt_close_now()', s*1000);
    
}

function dt_close_now(){
    
    $("#down-loader").fadeOut(400, function(){
        $("#down-blocker").fadeOut(200);
    });
    
}

function dt_message(m, e){
    
    $("#down-loader span").fadeOut(300, function(){
        $(this).html(m);
        if(e!='')
            $(this).addClass(e);
        else
            $(this).removeClass();
        
        $(this).fadeIn(300);
    });
    
}

function validate(){
    
    var f = $("#frm-items");
    
    var eles = $("#frm-items .required");
    var err = false;
    for(i=0;i<eles.length;i++){
        if($(eles[i]).val()==''){
            $("label[for='"+$(eles[i]).attr("id")+"']").addClass('error_field');
            err = true;
        }
    }
    
    if(err) return false;
    
    eles = $("ul.dt_categories input:checked");
    if(eles.length<=0){
        $("label.dt_lcats").addClass('error_field');
        return false;
    }
        
    eles = $("ul.dt_licences input:checked");
    if(eles.length<=0){
        $("label.dt_llics").addClass('error_field');
        return false;
    }
        
    eles = $("ul.dt_plats input:checked");
    if(eles.length<=0){
        $("label.dt_lplats").addClass('error_field');
        return false;
    }
        
    eles = $("ul.groups_field_list input:checked");
    if(eles.length<=0){
        $("label.dt_lgroups").addClass('error_field');
        return false;
    }
    
    return true;
    
}

