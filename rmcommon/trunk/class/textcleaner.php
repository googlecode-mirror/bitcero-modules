<?php
// $Id: textcleaner.php 55 2009-09-18 15:13:05Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Handles many methods for formatting output.
 */
class TextCleaner
{
	/**
	* Stores all the emoticons used on system
	*/
	private $emots = array();
	
	/**
	* Singleton
	*/
	public function getInstance(){
		static $instance;
		if (!isset($instance)) {
			$instance = new TextCleaner();
		}
		return $instance;
	}
	
	/**
	* Get all emotions icons, including plugins with this capability
	* @return array
	*/
	public function get_emotions(){
		
		global $rmc_config;
		
		if (!$rmc_config['dosmileys'])
			return false;
		
		if (!empty($this->emots))
			return $this->emots;
		
		// Only a few icons due to these can be extended by plugins or modules
		$url = RMCURL.'/images/emots';
		$this->emots[] = array('code'=>array(':)',':-)'),'icon'=>$url.'/smile.png');
		$this->emots[] = array('code'=>array(':-S',':S',':s','O.o'),'icon'=>$url.'/confused.png');
		$this->emots[] = array('code'=>array(":'("),'icon'=>$url.'/cry.png');
		$this->emots[] = array('code'=>array(':->',':>'),'icon'=>$url.'/grin.png');
		$this->emots[] = array('code'=>array(':D',':-D'),'icon'=>$url.'/happy.png');
		$this->emots[] = array('code'=>array(':-O',':-o',':O',':o'),'icon'=>$url.'/surprised.png');
		$this->emots[] = array('code'=>array(':p',':-p',':-P',':P'),'icon'=>$url.'/tongue.png');
		$this->emots[] = array('code'=>array(':-(',':('),'icon'=>$url.'/unhappy.png');
		$this->emots[] = array('code'=>array(';)',';-)'),'icon'=>$url.'/wink.png');
		$this->emots[] = array('code'=>array(':|',':-|'),'icon'=>$url.'/neutral.png');
		$this->emots[] = array('code'=>array('8)','8-)','B)','B-)'),'icon'=>$url.'/cool.png');
		$this->emots[] = array('code'=>array('>:(','>:-('),'icon'=>$url.'/mad.png');
		$this->emots[] = array('code'=>array(':oops:'),'icon'=>$url.'/red.png');
		$this->emots[] = array('code'=>array(':roll:'),'icon'=>$url.'/roll.png');
		$this->emots[] = array('code'=>array('>D','XD','xD'),'icon'=>$url.'/yell.png');    
		
		// Get another emoticons from plugins or modules
		$this->emots = RMEvents::get()->run_event('rmcommon.get_emotions', $this->emots);
		
		return $this->emots;
		
	}
    /**
     * Replace emots codes for ther image
     * @param	string  $message
     * @return	string
     */
    function smiley($message)
	{
		$emots = $this->get_emotions();
		$codes = array();
		$icons = array();
		foreach ($emots as $v){
			foreach ($v['code'] as $code){
				$codes[] = $code;
				$icons[] = '<img src="'.$v['icon'].'" alt="" />';
			}
		}
		$message = str_replace($codes,$icons,$message);
		return $message;
	}

	/**
	 * Make links in the text clickable
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	public function make_clickable($string){
		$string = ' ' . $string;
		// in testing, using arrays here was found to be faster
		$string = preg_replace_callback('#(?<=[\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#$%&~/\-=?@\[\](+]|[.,;:](?![\s<])|(?(1)\)(?![\s<])|\)))+)#is', "url_clickable", $string);
		$string = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]+)#is', 'ftp_clickable', $string);
		$string = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', 'mail_clickable', $string);
		// this one is not in an array because we need it to run last, for cleanup of accidental links within links
		$string = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $string);
		$string = trim($string);
		return $string;
	}
	/**
	* Next set of three functions are a set of callbacks to manege url and uri matches
	* 
	* @param array Matches
	* @return string
	*/
	public function url_clickable($matches){
		$url = $matches[2];
		$url = self::clean_url($url);
		if ( empty($url) )
			return $matches[0];
		
		return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>";
	}
	
	public function ftp_clickable($matches){
		$ret = '';
		$dest = $matches[2];
		$dest = 'http://' . $dest;
		$dest = self::clean_url($dest);
		if ( empty($dest) )
			return $matches[0];
		// removed trailing [,;:] from URL
		if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
			$ret = substr($dest, -1);
			$dest = substr($dest, 0, strlen($dest)-1);
		}
		return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
	}
	public function mail_clickable($matches){
		$email = $matches[2] . '@' . $matches[3];
		return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
	}
	
	/**
	* Checks and Clean a URL
	* 
	* Taked from Wordpress
	* 
	* @param string $url The URL to be cleaned.
	* @param array $protocols Optional. An array of acceptable protocols.
	*		Defaults to 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet' if not set.
	* @param string $context Optional. How the URL will be used. Default is 'display'.
	* @return string The cleaned $url after the 'cleaned_url' filter is applied.
	*/
	public function clean_url( $url, $protocols = null, $context = 'display' ) {
		$original_url = $url;

		if ('' == $url) return $url;
		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = _deep_replace($strip, $url);
		$url = str_replace(';//', '://', $url);
		/* If the URL doesn't appear to contain a scheme, we
		 * presume it needs http:// appended (unless a relative
		 * link starting with / or a php file).
		 */
		if ( strpos($url, ':') === false &&
			substr( $url, 0, 1 ) != '/' && substr( $url, 0, 1 ) != '#' && !preg_match('/^[a-z0-9-]+?\.php/i', $url) )
			$url = 'http://' . $url;

		// Replace ampersands and single quotes only when displaying.
		if ( 'display' == $context ) {
			$url = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $url);
			$url = str_replace( "'", '&#039;', $url );
		}

		if ( !is_array($protocols) )
			$protocols = array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet');
		if ( wp_kses_bad_protocol( $url, $protocols ) != $url )
			return '';

		return RMEvents::get()->run_event('rmcommon.clean_url', $url, $original_url, $context);
	}
	
	/**
     * MyTextSanitizer::truncate()
     *
     * @param mixed $text
     * @return
     */
    public function truncate($text, $len){
        $ret = substr(strip_tags($text), 0, $len);
        if (strlen($text)>$len) $ret .= '...';
        return $ret;
    }

	/**
	 * Replace EXMCodes with their equivalent HTML formatting
	 *
	 * @param   string  $text
	 * @param   bool    $allowimage Allow images in the text?
     *                              On FALSE, uses links to images.
	 * @return  string
	 **/
	public function codeDecode($text, $allowimage = 1)
	{
		$patterns = array();
		$replacements = array();
		//$patterns[] = "/\[code](.*)\[\/code\]/esU";
		//$replacements[] = "'<div class=\"exmCode\"><code><pre>'.wordwrap(MyTextSanitizer::htmlSpecialChars('\\1'), 100).'</pre></code></div>'";
		// RMV: added new markup for intrasite url (allows easier site moves)
		// TODO: automatically convert other URLs to this format if ABSURL matches??
		$patterns[] = "/\[siteurl=(['\"]?)([^\"'<>]*)\\1](.*)\[\/siteurl\]/sU";
		$replacements[] = '<a href="'.ABSURL.'/\\2">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(http[s]?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" target="_blank">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(ftp?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" target="_blank">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)([^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="http://\\2" target="_blank">\\3</a>';
		$patterns[] = "/\[color=(['\"]?)([a-zA-Z0-9]*)\\1](.*)\[\/color\]/sU";
		$replacements[] = '<span style="color: #\\2;">\\3</span>';
		$patterns[] = "/\[size=(['\"]?)([a-z0-9-]*)\\1](.*)\[\/size\]/sU";
		$replacements[] = '<span style="font-size: \\2;">\\3</span>';
		$patterns[] = "/\[font=(['\"]?)([^;<>\*\(\)\"']*)\\1](.*)\[\/font\]/sU";
		$replacements[] = '<span style="font-family: \\2;">\\3</span>';
		$patterns[] = "/\[email]([^;<>\*\(\)\"']*)\[\/email\]/sU";
		$replacements[] = '<a href="mailto:\\1">\\1</a>';
		
		$patterns[] = "/\[b](.*)\[\/b\]/sU";
		$replacements[] = '<b>\\1</b>';
		$patterns[] = "/\[i](.*)\[\/i\]/sU";
		$replacements[] = '<i>\\1</i>';
		$patterns[] = "/\[u](.*)\[\/u\]/sU";
		$replacements[] = '<u>\\1</u>';
		$patterns[] = "/\[d](.*)\[\/d\]/sU";
		$replacements[] = '<del>\\1</del>';
		
		$patterns[] = "/\[quote(=*+(.*))]/sU";
		$replacements[] = '<div class="exmQuote"><blockquote>'.TextCleaner::formQuote('$2').'<p>';
		$patterns[] = "/\[\/quote]/sU";
		$replacements[] = '</p></blockquote></div>';
		
		$patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img]([^\"\(\)'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		
		if ($allowimage != 1) {
			$replacements[] = '<a href="\\3" target="_blank">\\3</a>';
			$replacements[] = '<a href="\\1" target="_blank">\\1</a>';
			$replacements[] = '<a href="'.ABSURL.'/image.php?id=\\4" target="_blank">\\4</a>';
			$replacements[] = '<a href="'.ABSURL.'/image.php?id=\\2" target="_blank">\\3</a>';
		} else {
			$replacements[] = '<img src="\\3" align="\\2" alt="" />';
			$replacements[] = '<img src="\\1" alt="" />';
			$replacements[] = '<img src="'.ABSURL.'/image.php?id=\\4" align="\\2" alt="\\4" />';
			$replacements[] = '<img src="'.ABSURL.'/image.php?id=\\2" alt="\\3" />';
		}
		
		$text = str_replace( "\x00", "", $text );
		$c = "[\x01-\x1f]*";
		$patterns[] = "/j{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}:/si";
		$replacements[] = "(script removed)";
		$patterns[] = "/a{$c}b{$c}o{$c}u{$c}t{$c}:/si";
		$replacements[] = "about :";
		
		// More patterns with plugins
		$patterns = RMEvents::get()->run_event('rmcommon.get_replace_patterns', $patterns, $replacements, &$this);
		
		$text = preg_replace($patterns, $replacements, $text);

		return $text;
	}
	
	private function formQuote($uname){
		$uname = str_replace('=', '', $uname);
		
		return $uname!='' ? "<strong>".sprintf(_QUOTEC, $uname)."</strong>" : '';
		
	}

	/**
	 * Convert linebreaks to <br /> tags
     *
     * @param	string  $text
     *
     * @return	string
	 */
	function nl2Br($text)
	{
		return preg_replace("/(\015\012)|(\015)|(\012)/","<br />",$text);
	}

	/**
	 * Add slashes to the text if magic_quotes_gpc is turned off.
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	function addslashes($text)
	{
		if (get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
		return addslashes($text);
	}
	/*
	* if magic_quotes_gpc is on, stirip back slashes
    *
    * @param	string  $text
    *
    * @return	string
	*/
	function stripslashes($text)
	{
		if (get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
		return $text;
	}

	/*
	*  for displaying data in html textbox forms
    *
    * @param	string  $text
    *
    * @return	string
	*/
	function specialchars($string, $quote_style = ENT_NOQUOTES, $charset = 'UTF-8')
	{
		if ( 0 === strlen( $string ) ) {
			return '';
		}
		
		if ( !preg_match( '/[&<>"\']/', $string ) ) {
			return $string;
		}
		
		if ( empty( $quote_style ) ) {
			$quote_style = ENT_NOQUOTES;
		} elseif ( !in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) ) {
			$quote_style = ENT_QUOTES;
		}
		
		if ( in_array( $charset, array( 'utf8', 'utf-8', 'UTF8' ) ) ) {
			$charset = 'UTF-8';
		}
		
		if ( $quote_style === 'double' ) {
			$quote_style = ENT_COMPAT;
			$_quote_style = ENT_COMPAT;
		} elseif ( $quote_style === 'single' ) {
			$quote_style = ENT_NOQUOTES;
		}
		
		$string = @htmlspecialchars( $string, $quote_style, $charset );

		//return preg_replace("/&amp;/i", '&', htmlspecialchars($text, ENT_QUOTES));
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'), htmlspecialchars($string, ENT_QUOTES));
	}

	/**
	 * Reverses {@link htmlSpecialChars()}
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	function specialchars_decode( $string, $quote_style = ENT_NOQUOTES )
	{
		if ( 0 === strlen( $string ) ) {
			return '';
		}

		// Don't bother if there are no entities - saves a lot of processing
		if ( strpos( $string, '&' ) === false ) {
			return $string;
		}
        
        if ( empty( $quote_style ) ) {
            $quote_style = ENT_NOQUOTES;
        } elseif ( !in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) ) {
            $quote_style = ENT_QUOTES;
        }
        
        // More complete than get_html_translation_table( HTML_SPECIALCHARS )
        $single = array( '&#039;'  => '\'', '&#x27;' => '\'' );
        $single_preg = array( '/&#0*39;/'  => '&#039;', '/&#x0*27;/i' => '&#x27;' );
        $double = array( '&quot;' => '"', '&#034;'  => '"', '&#x22;' => '"' );
        $double_preg = array( '/&#0*34;/'  => '&#034;', '/&#x0*22;/i' => '&#x22;' );
        $others = array( '&lt;'   => '<', '&#060;'  => '<', '&gt;'   => '>', '&#062;'  => '>', '&amp;'  => '&', '&#038;'  => '&', '&#x26;' => '&' );
        $others_preg = array( '/&#0*60;/'  => '&#060;', '/&#0*62;/'  => '&#062;', '/&#0*38;/'  => '&#038;', '/&#x0*26;/i' => '&#x26;' );
        
        if ( $quote_style === ENT_QUOTES ) {
            $translation = array_merge( $single, $double, $others );
            $translation_preg = array_merge( $single_preg, $double_preg, $others_preg );
        } elseif ( $quote_style === ENT_COMPAT || $quote_style === 'double' ) {
            $translation = array_merge( $double, $others );
            $translation_preg = array_merge( $double_preg, $others_preg );
        } elseif ( $quote_style === 'single' ) {
            $translation = array_merge( $single, $others );
            $translation_preg = array_merge( $single_preg, $others_preg );
        } elseif ( $quote_style === ENT_NOQUOTES ) {
            $translation = $others;
            $translation_preg = $others_preg;
        }
        
        $string = preg_replace( array_keys( $translation_preg ), array_values( $translation_preg ), $string );
		
		return strtr( $string, $translation );
	}
	
	/**
	* Replaces double line-breaks with paragraph elements.
	*
	* A group of regex replaces used to identify text formatted with newlines and
	* replace double line-breaks with HTML paragraph tags. The remaining
	* line-breaks after conversion become <<br />> tags, unless $br is set to '0'
	* or 'false'.
	*
	* Taked from Wordpress
	* 
	* @license GPL 2
	* @param string $string The text which has to be formatted.
 	* @param int|bool $br Optional. If set, this will convert all remaining line-breaks after paragraphing. Default true.
 	* @return string Text which has been converted into correct paragraph tags.
	*/
	function double_br($string, $br = 1) {
		if ( trim($string) === '' )
			return '';
		$string = $string . "\n"; // just to make things a little easier, pad the end
		$string = preg_replace('|<br />\s*<br />|', "\n\n", $string);
		// Space things out a little
		$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
		$string = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $string);
		$string = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $string);
		$string = str_replace(array("\r\n", "\r"), "\n", $string); // cross-platform newlines
		if ( strpos($string, '<object') !== false ) {
			$string = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $string); // no pee inside object/embed
			$string = preg_replace('|\s*</embed>\s*|', '</embed>', $string);
		}
		$string = preg_replace("/\n\n+/", "\n\n", $string); // take care of duplicates
		// make paragraphs, including one at the end
		$strings = preg_split('/\n\s*\n/', $string, -1, PREG_SPLIT_NO_EMPTY);
		$string = '';
		foreach ( $strings as $tinkle )
			$string .= '<p>' . trim($tinkle, "\n") . "</p>\n";
		$string = preg_replace('|<p>\s*</p>|', '', $string); // under certain strange conditions it could create a P of entirely whitespace
		$string = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $string);
		$string = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $string); // don't pee all over a tag
		$string = preg_replace("|<p>(<li.+?)</p>|", "$1", $string); // problem with nested lists
		$string = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $string);
		$string = str_replace('</blockquote></p>', '</p></blockquote>', $string);
		$string = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $string);
		$string = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $string);
		if ($br) {
			$string = preg_replace_callback('/<(script|style).*?<\/\\1>/s', create_function('$matches', 'return str_replace("\n", "<EXMPreserveNewline />", $matches[0]);'), $string);
			$string = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $string); // optionally make line breaks
			$string = str_replace('<EXMPreserveNewline />', "\n", $string);
		}
		$string = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $string);
		$string = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $string);
		if (strpos($string, '<pre') !== false)
			$string = preg_replace_callback('!(<pre[^>]*>)(.*?)</pre>!is', 'TextCleaner::clean_pre', $string );
		//$string = preg_replace( "|\n</p>$|", '</p>', $string );
		//$string = preg_replace('/<p>\s*?(' . get_shortcode_regex() . ')\s*<\/p>/s', '$1', $string); // don't auto-p wrap shortcodes that stand alone
		
		return $string;
	}

	/**
	 * Filters textarea form data in DB for display
	 * 
	 * based on Xoops displayTarea
	 *
	 * @param   string  $text
	 * @param   bool    $dbr Disbale replace of breaklines when html is enabled
	 * @return  string
	 **/
	function to_display($text, $dbr = true){
		global $rmc_config;
		
		$original_text = $text;
		if ($rmc_config['dohtml'] != 1)
			$text = $this->specialchars($text);
		
		// Convert [code] tag
		$text = $this->codePreConv($text, $rmc_config['doxcode']); // Ryuji_edit(2003-11-18)
		
		if ($rmc_config['dosmileys'])
			$text = $this->smiley($text);

		// Decode exmcode
		if ($rmc_config['doxcode'])
			$text = $this->codeDecode($text, $rmc_config['doimage']);

		// Replace breaklines
		if ($rmc_config['dobr']) 
			$text = $this->nl2Br($text);
		
		$text = $this->make_clickable($text);
		$text = $this->codeConv($text, $rmc_config['doxcode']);	// Ryuji_edit(2003-11-18)
		$text = $this->double_br($text);

		// Before to send the formatted string we send it to interceptor methods
		return RMEvents::get()->run_event('rmcommon.text_todisplay', $text, $original_text);
	}

	/**
	 * Sanitizing of [code] tag
	 * from Xoops
	 */
	function codePreConv($text) {
		$patterns = "/\[code([^\]]*?)\](.*)\[\/code\]/esU";
		$replacements = "'[code$1]'.base64_encode('$2').'[/code]'";
		$text =  preg_replace($patterns, $replacements, $text);
		return $text;
	}
	
	function codeConv($text, $exmcode = 1){
		
		if ($exmcode==0)
			return $text;
		
		$patterns = "/\[code([^\]]*?)\](.*)\[\/code\]/esU";
		$replacements = "'<div class=\"exmCode\"><code>'.\$this->call_code_modifiers(\$this->specialchars(str_replace('\\\"', '\"', base64_decode('$2'))), '$1').'</code></div>'";
		$text =  preg_replace($patterns, $replacements, $text);
		return $text;
	}
	
	/**
	* This function is only a holder for event exmevent_format_code
	* 
	* @param string Code to format
	* @param string Language name
	* @return srtring
	*/
	public function call_code_modifiers($code, $lang){
		// Event to call methods thatr can handle code to display (eg. highlighters)
		return RMEvents::get()->run_event('rmcommon.format_code', $code, $lang);
	}
	
	/**
	* Accepts matches array from preg_replace_callback in wpautop() or a string.
	*
	* Ensures that the contents of a <<pre>>...<</pre>> HTML block are not
	* converted into paragraphs or line-breaks.
	*
	* From Wordpress
	*
	* @param array|string $matches The array or string
	* @return string The pre block without paragraph/line-break conversion.
	*/
	function clean_pre($matches) {
		if ( is_array($matches) )
			$text = $matches[1] . $matches[2] . "</pre>";
		else
			$text = $matches;

		$text = str_replace('<br />', '', $text);
		$text = str_replace('<p>', "\n", $text);
		$text = str_replace('</p>', '', $text);

		return $text;
	}
	
	/**
	* Encrypt a string
	* 
	* @param string Text to crypt
	* @param bool Apply base64_encode?
	* @return string
	*/
	public function encrypt($string, $encode64 = true){
		global $rmc_config;
		$crypt = new Crypt(Crypt::MODE_HEX, $rmc_config['secretkey']);
		$string = $crypt->encrypt($string);
		//if ($encode64) $string = base64_encode($string);
		return $string;
		
	}
	
	/**
	* Decrypt a string
	* 
	* @param strign Text to decrypt
	* @param bool Apply base64_decode? default true
	*/
	public function decrypt($string, $encode64 = true){
		global $rmc_config;
		$crypt = new Crypt(Crypt::MODE_HEX, $rmc_config['secretkey']);
		$string = $crypt->decrypt($string);
		
		return $string;
		
	}
    
    /**
     * Clean an string by deleting all blank spaces and other 
     * chars
     */
    public function sweetstring($value, $lower = true) {
        
        // Tranformamos todo a minusculas        
        $rtn = $lower ? strtolower($value) : $value;
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');        
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');        
        $rtn = str_replace ($find, $repl, $rtn);

        // A?aadimos los guiones        
        $find = array(' ', '&', '\r\n', '\n', '+');
        $rtn = str_replace ($find, '-', $rtn);
        
        // Eliminamos y Reemplazamos dem?s caracteres especiales        
        $find = array('/[^a-zA-Z0-9\-<>_]/', '/[\-]+/', '/<[^>]*>/');        
        $repl = array('', '-', '');        
        $url = preg_replace ($find, $repl, $rtn);
        return $url;
        
    }

}

// For compatibility
function url_clickable($matches){
	return TextCleaner::url_clickable($matches);
}

function ftp_clickable($matches){
	return TextCleaner::ftp_clickable($matches);
}

function mail_clickable($matches){
	return TextCleaner::mail_clickable($matches);
}