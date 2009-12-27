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
    $editor_id = rmc_server_var($_GET, 'id', '');
    $editor_url = RMCURL.'/api/editors/exmcode';
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

// Carga de plugins
$editor_path = RMCPATH.'/api/editors/exmcode';
$dir = opendir($editor_path.'/plugins');
while (false !== ($file = readdir($dir))) {
        if ($file=='.' || $file=='..' || !is_dir($editor_path.'/plugins/'.$file)) continue;
        if (!is_file($editor_path.'/plugins/'.$file.'/plugin.js')) continue;
        include_once $editor_path."/plugins/$file/plugin.js";
}

// Bold plugin
$top_buttons["bold"] = array(
    "icon"=>$editor_url.'/images/bold.png',
    "alt"=>__('Bold','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[b]%replace%[/b]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.bold();",
    "key"=>'B'
);
// Italic plugin
$top_buttons["italic"] = array(
    "icon"=>$editor_url.'/images/italic.png',
    "alt"=>__('Italic','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[i]%replace%[/i]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.italic();",
    "key"=>'I'
);
// Underline plugin
$top_buttons["underline"] = array(
    "icon"=>$editor_url.'/images/under.png',
    "alt"=>__('Underline','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[u]%replace%[/u]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.underline();",
    "key"=>'U'
);
// Strikeout plugin
$top_buttons["strike"] = array(
    "icon"=>$editor_url.'/images/strike.png',
    "alt"=>__('Strikeout','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[d]%replace%[/d]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.strike();",
    "key"=>'S'
);
// Align left plugin
$top_buttons["alignLeft"] = array(
    "icon"=>$editor_url.'/images/left.png',
    "alt"=>__('Left alignment','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[left]%replace%[/left]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.alignLeft();",
    "key"=>''
);
// Align center plugin
$top_buttons["alignCenter"] = array(
    "icon"=>$editor_url.'/images/center.png',
    "alt"=>__('Center alignment','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[center]%replace%[/center]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.alignCenter();",
    "key"=>''
);
// Align justify plugin
$top_buttons["alignJustify"] = array(
    "icon"=>$editor_url.'/images/justify.png',
    "alt"=>__('Justify','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[justify]%replace%[/justify]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.alignJustify();",
    "key"=>''
);
// Align right plugin
$top_buttons["alignRight"] = array(
    "icon"=>$editor_url.'/images/right.png',
    "alt"=>__('Right alignment','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[right]%replace%[/right]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.alignRight();",
    "key"=>''
);
// Align right plugin
$top_buttons["size"] = array(
    "icon"=>$editor_url.'/images/size.png',
    "alt"=>__('Font size','rmcommon'),
    "text"=>'',
    'action'=>'function(){
        exmCode.insertText("[right]%replace%[/right]","'.$editor_id.'");
    }',
    "onclick"=>"exmCode.size();",
    'type'=>'dropdown',
    "key"=>''
);
/*
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
                show: function(id){
                    alert('Hola');
                    \$(\"#\"+id+\"-ec-container span.color\").ColorPicker({
                        onSubmit: function(hsb, hex, rgb, el){
                            exmCode.insertText(\"[color=\"+hex+\"]%replace%[/color]\", id);
                            \$(el).ColorPickerHide();
                        }
                    });
                },
                type: 'dropdown',
                onclick: \"exmCode.color.show(\'".$editor_id."\');\"
            }";
$top_buttons[] = "showtool: {
                icon: 'showtoolbar.png',
                name: 'showtool',
                alt: ed_lang.more,
                text: '',
                command: 'showmore',
                special: 1
            }";
*/
$top_buttons = RMEventsApi::get()->run_event('load_xceditor_topplugins', $top_buttons);

// Bottom row buttons
$bottom_buttons[] = "showless: {
                icon: 'hide.png',
                name: 'showless',
                alt: ed_lang.less,
                text: '',
                command: 'hide',
                special: 1
            }";
$bottom_buttons[] = "link: {
                icon: 'link.png',
                name: 'link',
                alt: ed_lang.link,
                text: '',
                command: 'link',
                special: true
            }";
$bottom_buttons[] = "mail: {
                icon: 'mail.png',
                name: 'mail',
                alt: ed_lang.email,
                text: '',
                command: 'email',
                special: true
            }";
$bottom_buttons[] = "code: {
                icon: 'code.png',
                name: 'code',
                alt: ed_lang.code,
                text: '',
                command: 'code',
                special: true
            }";
$bottom_buttons[] = "quote: {
                icon: 'quote.png',
                name: 'quote',
                alt: ed_lang.quote,
                text: '',
                command: 'quote',
                special: true
            }";
$bottom_buttons[] = "smiley: {
                icon: 'smiley.png',
                name: ed_lang.smile,
                alt: 'Insert smiley',
                text: '',
                command: 'smiley',
                special: true
            }";
$bottom_buttons[] = "image: {
                icon: 'image.png',
                name: ed_lang.image,
                alt: 'Insert image',
                text: '',
                command: 'image',
                special: true
            }";
$bottom_buttons[] = "imageman: {
                icon: 'images.png',
                name: 'imageman',
                alt: ed_lang.imageman,
                text: '',
                command: 'imageman',
                special: true
            }";
$bottom_buttons = RMEventsApi::get()->run_event('load_xceditor_bottomplugins', $bottom_buttons);
            
?>

var exmCode = {
    // Init
	init: function(){
		editor_path = '<?php echo $file_url; ?>';
        
        // Buttons second row
        /*bottom_buttons = {
            <?php
                /*foreach($bottom_buttons as $i => $button){
                    echo $i<count($bottom_buttons) ? $button.',' : $button;
                }*/
            ?>
        };*/
        
	},
    <?php
        foreach($top_buttons as $name => $button){
            if($button['action']=='') continue;
            echo $name.": ".$button['action'].",\n";
        }
    ?>
    // Make buttons
	make_buttons: function(id){
		
		var container = $("#"+id+"-ec-container");
		// Top buttons
		var first_row = '<div class="row_top">';
		
        <?php
            foreach($top_buttons as $name => $button){
                $b = '<span class="buttons '.$name.'" accesskey="'.$button['key'].'" title="'.$button['alt'].'" onclick="'.$button['onclick'].'">';
                $b .= "<span>";
                if (trim($button['icon'])!=''){
                    $b .= "<img src='".$button['icon']."' alt='".$button['alt']."' />";
                }
                if ($button['type']=='dropdown'){
                    $b .= "<span class='dropdown'><img src='".$editor_url."/images/down.png' alt='' /></span>";
                }
                $b .= $button['text']!='' ? $button['text'] : '';
                $b .= "</span></span>";
                echo "first_row += '".str_replace("'", "\\'", $b)."';\n";
            }
        ?>
		
		first_row += "</div>"
		container.append(first_row);
		// Bottom buttons
		var second_row = '<div class="row_bottom">';
		
		for(i in bottom_buttons){
			var b = "<span class='buttons "+bottom_buttons[i].name+"' accesskey='"+bottom_buttons[i].key+"' title='"+bottom_buttons[i].alt+"' onclick=\"exmCode.insert('"+bottom_buttons[i].name+"','"+id+"', 'bottom');\">";
			b += "<span>";
			if (bottom_buttons[i].icon!='' && bottom_buttons[i].icon!=undefined){
				b += "<img src='"+editor_path+"/images/"+bottom_buttons[i].icon+"' alt='' />";
			}
			if (bottom_buttons[i].type=='dropdown'){
				b += "<span class='dropdown'><img src='"+editor_path+"/images/down.png' alt='' /></span>";
			}
			b += (bottom_buttons[i].text!='' ? bottom_buttons[i].text : '')+"</span>";
			b += "</span>";
			second_row += b;
		};
		
		second_row += "</div>";
		container.append(second_row);
		
		second_row = '';
		first_row = '';
		
		container.append('<div id="'+id+'-popscont" class="pops_container"></div>');
		
		
        
        button = $("#"+id+"-popscont");
        
        // Add size dropdown        
        pos = $("#"+id+"-ec-container span.size").position();
        height = $("#"+id+"-ec-container span.size").height();
        anex = "<div class='pops pop-size' style='top: "+(pos.top+28)+"px; left: "+pos.left+"px;'>";
        anex += "<ul><li style='font-size: xx-small;'>xx-Small</li><li style='font-size: x-small;'>x-Small</li><li style='font-size: small;'>Small</li><li style='font-size: medium;'>Medium</li><li style='font-size: large;'>Large</li><li style='font-size: x-large;'>x-Large</li><li style='font-size: xx-large;'>xx-Large</li></ul>";
        anex += "</div>";
        
        button.append(anex);
        
        $("#"+id+"-popscont div.pop-size").hover(
            function() {}, 
            function(){
                $("#"+id+"-popscont div.pop-size").slideUp('fast');
                $("div.ed_buttons span.buttons").css("border-style",'solid');
                return false;
            });
        
        $("#"+id+"-ec-container div.pop-size").find("li").click(function(){
            exmCode.insertText("[size="+$(this).css('font-size')+"]%replace%[/size]", id);
            $("#"+id+"-ec-container div.pop-size").hide();
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        
        // Add font family dropdown
        pos = $("#"+id+"-ec-container span.font").position();
        height = $("#"+id+"-ec-container span.font").height();
        anex = "<div class='pops pop-fonts' style='top: "+(pos.top+28)+"px; left: "+(pos.left+3)+"px;'>";
        anex += "<ul><li style='font-family: Arial;'>Arial</li><li style='font-family: Courier;'>Courier</li><li style='font-family: Georgia;'>Georgia</li><li style='font-family: Helvetica;'>Helvetica</li><li style='font-family: Impact;'>Impact</li><li style='font-family: Verdana;'>Verdana</li><li style='font-family: Haettenschweiler;'>Haettenschweiler</li><li style='font-family:Verdana, Geneva, sans-serif;'>Verdana, Geneva, sans-serif</li><li style='font-family: Georgia, \"Times New Roman\", Times, serif;'>Georgia, Times New Roman, Times, serif</li><li style='font-family: \"Courier New\", Courier, monospace;'>Courier New, Courier, monospace</li><li style='font-family: Arial, Helvetica, sans-serif;'>Arial, Helvetica, sans-serif</li><li style='font-family: Tahoma, Geneva, sans-serif;'>Tahoma, Geneva, sans-serif</li><li style='font-family: \"Trebuchet MS\", Arial, Helvetica, sans-serif;'>Trebuchet MS, Arial, Helvetica, sans-serif</li><li style='font-family: \"Arial Black\", Gadget, sans-serif;'>Arial Black, Gadget, sans-serif</li><li style='font-family: \"Palatino Linotype\", \"Book Antiqua\", Palatino, serif;'>Palatino Linotype, Book Antiqua, Palatino, serif</li><li style='font-family: \"Lucida Sans Unicode\", \"Lucida Grande\", sans-serif;'>Lucida Sans Unicode, Lucida Grande, sans-serif</li><li style='font-family: \"MS Serif\", \"New York\", serif;'>MS Serif, New York, serif</li><li style='font-family: \"Lucida Console\", Monaco, monospace;'>Lucida Console, Monaco, monospace</li><li style='font-family: \"Comic Sans MS\", cursive;'>Comic Sans MS, cursive</li></ul>";
        anex += "</div>";
        button.append(anex);
        
        $("#"+id+"-popscont div.pop-fonts").hover(
            function() {}, 
            function(){
                $("#"+id+"-ec-container div.pop-fonts").slideUp('fast');
                $("div.ed_buttons span.buttons").css("border-style",'solid');
                return false;
            });
        
         $("#"+id+"-ec-container div.pop-fonts").find("li").click(function(){
            exmCode.insertText("[font="+$(this).css("font-family")+"]%replace%[/font]", id);
            $("#"+id+"-ec-container div.pop-fonts").hide();
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        
        // Links pop
        anex = "<div class='pops pop-links bottom'>";
        anex += "<label>"+ed_lang.linkaddr+"</label><input type='text' id='"+id+"-link' name='link' value='http://' size='50' />";
        anex += "<label>"+ed_lang.linkname+"</label><input type='text' id='"+id+"-linkname' name='name' value='' size='50' /><br />";
        anex += "<input type='button' id='"+id+"-linkinsert' value='"+ed_lang.insert+"' />";
        anex += "<input type='button' id='"+id+"-linkcancel' value='"+ed_lang.cancel+"' />";
        anex += "</div>";
        button.append(anex);
        
        $("#"+id+"-linkcancel").click(function(){
            $("#"+id+"-ec-container div.pop-links").slideUp('fast');
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-linkinsert").click(function(){
            url = $("#"+id+"-link").val();
            name = $("#"+id+"-linkname").val();
            if (url=='' || name==''){
                alert(ed_lang.requiredmsg);
                return false;
            }
            exmCode.insertText("[url="+url+"]"+name+"[/url]", id);
            $("#"+id+"-ec-container div.pop-links").hide();
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-popscont div.pop-links").keyup(function(k){
			if(k.which==27){
				$("#"+id+"-linkcancel").click();
				return false;
			}
        });
        
        // Email pop
        anex = "<div class='pops pop-email bottom'>";
        anex += "<label>"+ed_lang.emaillabel+"</label><input type='text' id='"+id+"-email' name='email' value='' size='50' /><br />";
        anex += "<input type='button' id='"+id+"-emailinsert' value='"+ed_lang.insert+"' />";
        anex += "<input type='button' id='"+id+"-emailcancel' value='"+ed_lang.cancel+"' />";
        anex += "</div>";
        button.append(anex);
        
        $("#"+id+"-emailcancel").click(function(){
            $("#"+id+"-ec-container div.pop-email").slideUp('fast');
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-emailinsert").click(function(){
            email = $("#"+id+"-email").val();
            if (email==''){
                alert(ed_lang.requiredmail);
                return false;
            }
            exmCode.insertText("[email]"+email+"[/email]", id);
            $("#"+id+"-ec-container div.pop-email").hide();
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-popscont div.pop-email").keyup(function(k){
			if(k.which==27){
				$("#"+id+"-emailcancel").click();
				return false;
			}
        });
        
        // Code pop
        anex = "<div class='pops pop-code bottom'>";
        anex += "<label>"+ed_lang.codename+"</label><input type='text' id='"+id+"-codename' name='codename' value='' size='30' /><br />";
        anex += "<label>"+ed_lang.codeblock+"</label><textarea id='"+id+"-codeblock' name='codeblock' rows='6' cols='40'></textarea><br />";
        anex += "<input type='button' id='"+id+"-codeinsert' value='"+ed_lang.insert+"' />";
        anex += "<input type='button' id='"+id+"-codecancel' value='"+ed_lang.cancel+"' />";
        anex += "</div>";
        button.append(anex);
        
        $("#"+id+"-codecancel").click(function(){
            $("#"+id+"-ec-container div.pop-code").slideUp('fast');
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-codeinsert").click(function(){
            code = $("#"+id+"-codeblock").val();
            name = $("#"+id+"-codename").val()!='' ? "="+$("#"+id+"-codename").val() : '';
            if (code==''){
                alert(ed_lang.requiredcode);
                return false;
            }
            exmCode.insertText("[code"+name+"]"+code+"[/code]", id);
            $("#"+id+"-ec-container div.pop-code").hide();
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-popscont div.pop-code").keyup(function(k){
			if(k.which==27){
				$("#"+id+"-codecancel").click();
			}
        });
        
        // Quote Pop
        anex = "<div class='pops pop-quote bottom'>";
        anex += "<label>"+ed_lang.quotename+"</label><input type='text' id='"+id+"-quotename' name='quotename' value='' size='30' /><br />";
        anex += "<label>"+ed_lang.quotetext+"</label><textarea id='"+id+"-quotetext' name='quotetext' rows='6' cols='40'></textarea><br />";
        anex += "<input type='button' id='"+id+"-quoteinsert' value='"+ed_lang.insert+"' />";
        anex += "<input type='button' id='"+id+"-quotecancel' value='"+ed_lang.cancel+"' />";
        anex += "</div>";
        button.append(anex);
        
        $("#"+id+"-quotecancel").click(function(){
            $("#"+id+"-ec-container div.pop-quote").slideUp('fast');
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        $("#"+id+"-quoteinsert").click(function(){
            quote = $("#"+id+"-quotetext").val();
            name = $("#"+id+"-quotename").val()!='' ? "="+$("#"+id+"-quotename").val() : '';
            if (quote==''){
                alert(ed_lang.requiredquote);
                return false;
            }
            exmCode.insertText("[quote"+name+"]"+quote+"[/quote]", id);
            $("#"+id+"-ec-container div.pop-quote").hide();
            $("div.ed_buttons span.buttons").css("border-style",'solid');
            return false;
        });
        
        $("#"+id+"-popscont div.pop-quote").keyup(function(k){
			if(k.which==27){
				$("#"+id+"-quotecancel").click();
			}
        });
		
	},
	// INsert
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
    insertText: function(what, editor){
        var e = document.getElementById(editor);
        var scrollTop = e.scrollTop;
        selected = $("#"+editor).getSelection();
        if(selected.text==null)
            selected.text = '';
            
        text = what.replace('%replace%', selected.text);
        $("#"+editor).replaceSelection(text, true);
            
        var cursorPos = 0;
        if (selected.text==''){
            cursorPos = selected.start + what.indexOf("%replace%");
        } else {
            cursorPos = selected.start + text.length;
        }
        
        cursorPos = cursorPos<0 || cursorPos<=selected.start ? (selected.start + text.length) : cursorPos;
        
        e.selectionEnd = cursorPos;
        e.scrollTop = scrollTop;
        $("#"+editor).focus();
        
    },
	
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
	
};

$(document).ready(function(){
	exmCode.init('<?php echo RMCURL; ?>/api/editors/exmcode','<?php echo $lang; ?>');
	exmCode.make_buttons('<?php echo rmc_server_var($_GET, 'id'); ?>');
	$("#<?php echo rmc_server_var($_GET, 'id'); ?>").css('width', ($("#<?php echo rmc_server_var($_GET, 'id'); ?>-ec-container").width()-6)+'px');
});