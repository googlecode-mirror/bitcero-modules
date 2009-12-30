/**
* $Id$
* ExmCode Editor
* @author: Eduardo Cortés <i.bitcero@gmail.com>
* http://exmsystem.net
*/
<?php
	require '../../../../../mainfile.php'; 
	require '../../../loader.php'; 
	XoopsLogger::getInstance()->activated = false;
	XoopsLogger::getInstance()->renderingEnabled = false;
	$lang = is_file(ABSPATH.'/api/editors/exmcode/language/'.EXMLANG.'.js') ? EXMLANG : 'en_US';
    $id = rmc_server_var($_GET, 'id', '');
?>

/*
 * jQuery plugin: fieldSelection - v0.1.0 - last change: 2006-12-16
 * (c) 2006 Alex Brem <alex@0xab.cd> - http://blog.0xab.cd
 */
(function() {

	var fieldSelection = {

		getSelection: function() {

			var e = this.jquery ? this[0] : this;

			return (

				/* mozilla / dom 3.0 */
				('selectionStart' in e && function() {
					var l = e.selectionEnd - e.selectionStart;
					return { start: e.selectionStart, end: e.selectionEnd, length: l, text: e.value.substr(e.selectionStart, l) };
				}) ||

				/* exploder */
				(document.selection && function() {

					e.focus();

					var r = document.selection.createRange();
					if (r == null) {
						return { start: 0, end: e.value.length, length: 0 }
					}

					var re = e.createTextRange();
					var rc = re.duplicate();
					re.moveToBookmark(r.getBookmark());
					rc.setEndPoint('EndToStart', re);

					return { start: rc.text.length, end: rc.text.length + r.text.length, length: r.text.length, text: r.text };
				}) ||

				/* browser not supported */
				function() {
					return { start: 0, end: e.value.length, length: 0 };
				}

			)();

		},

		replaceSelection: function() {

			var e = this.jquery ? this[0] : this;
			var text = arguments[0] || '';

			return (

				/* mozilla / dom 3.0 */
				('selectionStart' in e && function() {
					e.value = e.value.substr(0, e.selectionStart) + text + e.value.substr(e.selectionEnd, e.value.length);
					return this;
				}) ||

				/* exploder */
				(document.selection && function() {
					e.focus();
					document.selection.createRange().text = text;
					return this;
				}) ||

				/* browser not supported */
				function() {
					e.value += text;
					return this;
				}

			)();

		}

	};

	jQuery.each(fieldSelection, function(i) { jQuery.fn[i] = this; });

})();


// Make the editor accesible from any location
var editor_path = '';
var top_buttons = '';
var bottom_buttons = '';

<?php 
// Top buttons
$top_buttons[] = "bold: {
                    icon: 'bold.png',
                    name: 'bold', 
                    alt: ed_lang.bold,
                    text: '',
                    open: 'b',
                    close: '/b',
                    command: 'bold',
                    key: 'B'
            }";
$top_buttons[] = "italic: {
                icon: 'italic.png',
                name: 'italic',
                alt: ed_lang.italic,
                text: '',
                open: 'i',
                close: '/i',
                command: 'italic'
            }";
$top_buttons[] = "underline: {
                icon: 'under.png',
                name: 'underline',
                alt: ed_lang.underline,
                text: '',
                open: 'u',
                close: '/u',
                command: 'underline'
            }";
$top_buttons[] = "strike: {
                icon: 'strike.png',
                name: 'strike',
                alt: ed_lang.strikeout,
                text: '',
                open: 'd',
                close: '/d',
                command: 'strike'
            }";
$top_buttons[] = "left: {
                icon: 'left.png',
                name: 'left',
                alt: ed_lang.left,
                text: '',
                open: 'left',
                close: '/left',
                command: 'left'
            }";
$top_buttons[] = "center: {
                icon: 'center.png',
                name: 'center',
                alt: ed_lang.center,
                text: '',
                open: 'center',
                close: '/center',
                command: 'center'
            }";
$top_buttons[] = "justify: {
                icon: 'justify.png',
                name: 'justify',
                alt: ed_lang.justify,
                text: '',
                open: 'justify',
                close: '/justify',
                command: 'justify'
            }";
$top_buttons[] = "right: {
                icon: 'right.png',
                name: 'right',
                alt: ed_lang.right,
                text: '',
                open: 'right',
                close: '/right',
                command: 'right'
            }";
$top_buttons[] = "size: {
                icon: 'size.png',
                name: 'size',
                alt: ed_lang.fontsize,
                text: '',
                command: 'size',
                type: 'dropdown',
                special: true
            }";
$top_buttons[] = "font: {
                icon: '',
                name: 'font',
                alt: ed_lang.fontfamily,
                text: ed_lang.fontselect,
                command: 'font',
                type: 'dropdown',
                special: true
            }";
$top_buttons[] = "color: {
                icon: 'color.png',
                name: 'color',
                alt: ed_lang.fontcolor,
                text: '',
                command: 'color',
                type: 'dropdown',
                special: true
            }";
$top_buttons[] = "showtool: {
                icon: 'showtoolbar.png',
                name: 'showtool',
                alt: ed_lang.more,
                text: '',
                command: 'showmore',
                special: 1
            }";

$top_buttons = RMEventsApi::get()->run_event('load_bbcode_topplugins', $top_buttons);
            
?>

var exmCode<?php echo ucfirst($id); ?> = {
    // Init
	init: function(path, lang){
        var x = this;
        x.editor_path = '<?php echo RMCURL.'/api/editors/exmcode'; ?>';
        x.ed = '<?php echo $id; ?>';
        x.name = 'exmCode<?php echo ucfirst($id); ?>';
        // Add plugins
        <?php
        // Cargamos los plugins
        $path = RMCPATH.'/api/editors/exmcode/plugins';
        $dir = opendir($path);
        while(FALSE !== ($file = readdir($dir))){
            if ($file=='.' || $file=='..') continue;
            if (!is_dir($path.'/'.$file)) continue;
            if (!is_file($path.'/'.$file.'/plugin.js')) continue;
            
            include $path.'/'.$file.'/plugin.js';
            
        }

        ?>
        
        //x.add_separator('top');
        x.add_button('bottom', {
            name : 'bottom',
            title : 'Show bottom toolbar',
            alt : 'Bottom toolbar',
            icon : x.editor_path+'/images/show.png',
            row : 'top',
            cmd : function(x){
                $("#<?php echo $id; ?>-ec-container .row_bottom").slideDown('fast');
                $("#<?php echo $id; ?>-bottom").hide();
            },
            cmd_type : 'auto'
        });
        x.add_button('top', {
            name : 'top',
            title : 'Hide toolbar',
            alt : 'Hide toolbar',
            icon : x.editor_path+'/images/hide.png',
            row : 'bottom',
            cmd : function(x){
                $("#<?php echo $id; ?>-ec-container .row_bottom").slideUp('fast');
                $("#<?php echo $id; ?>-bottom").show();
            },
            cmd_type : 'auto'
        });
        //x.add_separator('bottom');
        
        var buttons = new Array();
        buttons[0] = 'top';
        buttons = buttons.concat(<?php echo $id; ?>_buttons.split(','),'separator_t','bottom');
        
        for(i=0;i<buttons.length;i++){
            
            buttons[i] = buttons[i].replace(/^\s*|\s*$/g,"");
            
            if (buttons[i]=='separator_t' || buttons[i]=='separator_b'){
                x.add_separator(buttons[i]=='separator_t'?'top':'bottom');
                continue;
            }
            
            if (x.buttons[buttons[i]]==undefined) continue;
            d = x.buttons[buttons[i]];
            
                var b = '<span class="buttons" id="<?php echo $id; ?>-'+d.name+'" accesskey="'+d.key+'" title="'+d.alt+'" onclick="'+x.name+'.button_press(\''+d.name+'\');">';
                b += "<span>";
                if (d.icon!=undefined){
                    b += "<img src='"+d.icon+"' alt='' />";
                }
                if (d.type=='dropdown'){
                    b += "<span class='dropdown'><img src='"+x.editor_path+"/images/down.png' alt='' /></span>";
                }
                b += (d.text!=undefined ? d.text : '')+"</span>";
                b += "</span>";
                
                if (d.row == 'top'){
                    $("#<?php echo $id; ?>-ec-container .row_top").append(b);
                } else {
                    $("#<?php echo $id; ?>-ec-container .row_bottom").append(b);
                }
        
        }
        
        
	},
    add_button : function(n,d){
        
        var x = this;
        x.buttons = x.buttons || {};
        x.buttons[n] = d;
        
    },
    add_separator : function(w){
        if (w == 'top'){
            $("#<?php echo $id; ?>-ec-container .row_top").append('<span class="separator"></span>');
        } else {
            $("#<?php echo $id; ?>-ec-container .row_bottom").append('<span class="separator"></span>');
        }
    },
    add_plugin : function(n,d){
        var x = this;
        if (x[n]!=undefined) return;
        x[n] = d;

        if (x[n].init!=undefined) x[n].init(x);
        
    },
    button_press : function(n){
        var x = this;
        
        if (x.buttons[n]=='undefined') return;
        
        if (x.buttons[n].cmd_type=='auto'){
            x.buttons[n].cmd(x);
            return;
        }
        
        plugin = x.buttons[n].plugin;
        x.command(plugin, x.buttons[n].cmd);
        
    },
    command : function(n, c, p){
        var x = this;
        
        if (x[n]==undefined) return;
        
        eval("x."+n+"."+c+"(x,p)");
    },
    insertText: function(what){
        var x = this;
        var e = document.getElementById(x.ed);
        var scrollTop = e.scrollTop;
        selected = $("#"+x.ed).getSelection();
        if(selected.text==null)
            selected.text = '';
            
        text = what.replace('%replace%', selected.text);
        $("#"+x.ed).replaceSelection(text, true);
            
        var cursorPos = 0;
        if (selected.text==''){
            cursorPos = selected.start + what.indexOf("%replace%");
        } else {
            cursorPos = selected.start + text.length;
        }
        
        cursorPos = cursorPos<0 || cursorPos<=selected.start ? (selected.start + text.length) : cursorPos;
        
        e.selectionEnd = cursorPos;
        e.scrollTop = scrollTop;
        $("#"+x.ed).focus();
        
    },
	/*/ I
	insert: function(ele, editor, where){
		if (where=='top'){
			if (top_buttons[ele].special){
				cmd = top_buttons[ele].command;
				exmCode[cmd](ele, editor, where);
				return;
			}	
		} else {
			if (bottom_buttons[ele].special){
				cmd = bottom_buttons[ele].command;
				exmCode[cmd](ele, editor, where);
				return;
			}	
		}
		
		var e = document.getElementById(editor);
		var scrollTop = e.scrollTop;
		selected = $("#"+editor).getSelection();
		
		if(selected.text==null)
			selected.text = '';
		
		button = top_buttons[ele];
		
		text = "["+button.open+"]" + selected.text + "["+button.close+"]";
		$("#"+editor).replaceSelection(text, true);
		
		var cursorPos = 0;
		if (selected.text==''){
			cursorPos = selected.start + ("["+button.open+"]").length;
		} else {
			cursorPos = selected.start + text.length;
		}
		e.selectionEnd = cursorPos;
		e.scrollTop = scrollTop;
		$("#"+editor).focus();
				
	},
    // Insert text
    
	
	// Tools
	showmore: function(ele, editor, where){
		exmCode.hidePops(editor);
		$("#"+editor+"-ec-container div.row_bottom").slideDown();
		$("#"+editor+"-ec-container span."+ele+"'").hide();		
	},
	// Hide tools
	hide: function(ele, editor, where){
		exmCode.hidePops(editor);
		$("#"+editor+"-ec-container div.row_bottom").hide();
		$("#"+editor+"-ec-container div.row_top").css("border-bottom","");
		$("#"+editor+"-ec-container span.showtool").show();
	},
	// Font Size
	size: function(ele, editor, where){
		exmCode.hidePops(editor);
        var button = $("#"+editor+"-ec-container span.size");
        $("#"+editor+"-ec-container div.pop-fonts").slideUp('fast');
		pop = $("#"+editor+"-ec-container div.pop-size");
		exmCode.press(button, 1);
		pop.slideDown('fast');
		
	},	
	// Font Family
	font: function(ele, editor, where){
		exmCode.hidePops(editor);
		var button = $("#"+editor+"-ec-container span.font");
		$("#"+editor+"-ec-container div.pop-size").slideUp('fast');
		
		pop = $("#"+editor+"-ec-container div.pop-fonts");
		
		exmCode.press(button, 1);
		pop.slideDown('fast');
        
	},
	// Link
	link: function(ele, editor, where){
		exmCode.hidePops(editor);
        // Link pop
        var button = $("#"+editor+"-ec-container span.link");
        var pop = $("#"+editor+"-ec-container div.pop-links");
        var pos = button.position();

        pop.css({'top':(pos.top+28)+'px', 'left':pos.left+'px'}); 
        
        selected = $("#"+editor).getSelection();
        $("#"+editor+"-linkname").val(selected.text);
               
        exmCode.press(button, 1);
        pop.slideDown('fast');
		
	},
	// Code
	code: function(ele, editor, where){
		// Link pop
		exmCode.hidePops(editor);
        var button = $("#"+editor+"-ec-container span.code");
        var pop = $("#"+editor+"-ec-container div.pop-code");
        var pos = button.position();

        pop.css({'top':(pos.top+28)+'px', 'left':pos.left+'px'}); 
        
        selected = $("#"+editor).getSelection();
        $("#"+editor+"-codeblock").val(selected.text);
               
        exmCode.press(button, 1);
        pop.slideDown('fast');
	},
	// Quote
	quote: function(ele, editor, where){
		// Link pop
		exmCode.hidePops(editor);
        var button = $("#"+editor+"-ec-container span.quote");
        var pop = $("#"+editor+"-ec-container div.pop-quote");
        var pos = button.position();

        pop.css({'top':(pos.top+28)+'px', 'left':pos.left+'px'}); 
        
        selected = $("#"+editor).getSelection();
        $("#"+editor+"-quotetext").val(selected.text);
               
        exmCode.press(button, 1);
        pop.slideDown('fast');
        
	},
    // Email
    email: function(ele, editor, where){
    	exmCode.hidePops(editor);
        // Link pop
        var button = $("#"+editor+"-ec-container span.mail");
        var pop = $("#"+editor+"-ec-container div.pop-email");
        var pos = button.position();
        pop.css({'top':(pos.top+28)+'px', 'left':pos.left+'px'});    
        exmCode.press(button, 1);
        pop.slideDown('fast');
    },
	// Press
	press: function(button, ok){
		if (ok){
			button.css('border-style','inset');
		} else {
			button.css('border-style','solid');
		}
	},
	// Hide pops
	hidePops: function(editor){
		$("#"+editor+"-ec-container span.buttons").css('border-style','solid');
		$("#"+editor+"-ec-container div.pops").hide();
	},
	*/
};

$(document).ready(function(){
	exmCode<?php echo ucfirst($id); ?>.init();
	//exmCode.make_buttons('<?php echo rmc_server_var($_GET, 'id'); ?>');
	//$("#<?php echo rmc_server_var($_GET, 'id'); ?>").css('width', ($("#<?php echo rmc_server_var($_GET, 'id'); ?>-ec-container").width()-6)+'px');
});