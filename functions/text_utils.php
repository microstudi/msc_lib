<?php
/**
* @file functions/text_utils.php
* @author Ivan Vergés
* @brief Text utils functions file\n
*
*/

/**
 * Just a wrapper fot htmlLawed
 * http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/
 * @param $in text to sanitize html
 * @param $options htmlLawed options: http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.2
 * @param $spec htmlLawed options for attributes: http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/htmLawed_README.htm#s2.3
 * */
function m_htmlawed($in, $options=array('safe'=>1, 'elements'=>'a, b, strong, i, em, li, ol, ul'),$spec=array()) {
	 return htmLawed::hl($in,$options,$spec);
}

/**
 * Stripslashes recursively from strings, arrays or object (at every key)
 * @param $var var or array to clean
 * */
function m_stripslashes($var) {
	$v = $var;
	if($var) {
		if(is_object($var)) {
			foreach($var as $k => $i) {
				$v->$k = m_stripslashes($i);
			}
		}
		elseif(is_array($var)) {
			foreach($var as $k => $i) {
				$v[$k] = m_stripslashes($i);
			}
		}
		elseif(is_string($var)) $v = stripslashes($var);

	}
	return $v;
}

/**
 * Stripslashes recursively from strings, arrays or object (at every key)
 * @param $var var or array to trim
 * */
function m_trim($var) {
	$v = $var;
	if($var) {
		if(is_object($var)) {
			foreach($var as $k => $i) {
				$v->$k = m_trim($i);
			}
		}
		elseif(is_array($var)) {
			foreach($var as $k => $i) {
				$v[$k] = m_trim($i);
			}
		}
		elseif(is_string($var)) $v = trim($var);

	}
	return $v;
}
/**
 * Displays a UNIX timestamp in a friendly way (eg "less than a minute ago")
 *
 * @param $time A UNIX epoch timestamp
 * @param $titles array with custom text definitions by default:
 * - 'seconds'=> "just now"
 * - 'minute' => "a minute ago"
 * - 'minutes' => "%s minutes ago"
 * - 'hour' => "an hour ago"
 * - 'hours' => "%s hours ago"
 * - 'day' => "yesterday"
 * - 'days' => "%s days ago"
 * - 'month' => "over a month"
 * - 'months' => "%s months ago"
 * - 'year' => "over a year"
 * - 'years' => "%s years ago"
 * @return The friendly time
 */
function m_friendly_time($time,$titles=array(
	'seconds'=> "just now",
	'minute' => "a minute ago",
	'minutes' => "%s minutes ago",
	'hour' => "an hour ago",
	'hours' => "%s hours ago",
	'day' => "yesterday",
	'days' => "%s days ago",
	'month' => "over a month",
	'months' => "%s months ago",
	'year' => "over a year",
	'years' => "%s years ago")) {

	$diff = time() - ((int) $time);
	$string = '';
	if ($diff < 60) $string = $titles['seconds'];
	elseif ($diff < 120) $string = $titles['minute'];
	elseif ($diff < 3600) $string = sprintf($titles['minutes'], round($diff/60));
	elseif ($diff < 7200) $string = $titles['hour'];
	elseif ($diff < 86400) $string = sprintf($titles['hours'], round($diff/3600));
	elseif ($diff < 172800) $string = $titles['day'];
	elseif ($diff < 2678400) $string = sprintf($titles['days'], round($diff/86400));
	elseif ($diff < 5356800) $string = $titles['month'];
	elseif ($diff < 31536000) $string = sprintf($titles['months'], round($diff/2678400));
	elseif ($diff < 63072000) $string = $titles['year'];
	else $string = sprintf($titles['years'], round($diff/31536000));

	return $string;

}

/**
 *
 *
 * */
function m_encrypt($str, $key) {
	if(!function_exists("mcrypt_encrypt")) return $str;
	$key = substr($key,0,8);

    # Add PKCS7 padding.
    $block = mcrypt_get_block_size('des', 'ecb');
    if (($pad = $block - (strlen($str) % $block)) < $block) {
      $str .= str_repeat(chr($pad), $pad);
    }

    return @mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
}

/**
 *
 *
 * */
function m_decrypt($str, $key) {
	if(!function_exists("mcrypt_decrypt")) return $str;
	$key = substr($key,0,8);
    $str = @mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);

    # Strip padding out.
    $block = mcrypt_get_block_size('des', 'ecb');
    $pad = ord($str[($len = strlen($str)) - 1]);
    if ($pad && $pad < $block && preg_match(
          '/' . chr($pad) . '{' . $pad . '}$/', $str
                                            )
       ) {
      return substr($str, 0, strlen($str) - $pad);
    }
    return $str;
}
/**
 * Returns a text width the links inside parse as html anchors <a href="">link</a>
 * @param $text the string to parse
 * @param $add = to add something in the link tag (per exemple:  onclick="window.open(this.href);return false;")
 * @param $add_not_tohost = in this host the add property will not be applied
 */
function m_txt_parse_links($text,$add='',$add_not_tohost='') {
	global $CONFIG;

	//$text = " $text";
	$t = preg_replace(
		array(
			'!([[:space:]()[{}])([-a-z0-9@:%_\+\.~#?&/=,]+)\.([a-z]{2,3})([-a-zA-Z0-9:%_\+\.~#?&/=,]+)!i',
			'!((f|ht){1}tp://)([-a-z0-9@:%_\+\.~#?&/=,]+)!i'

		),
		array(
			//'\\1http://\\2.\\3\\4', //?????? revisar!!
			'\\1\\2.\\3\\4',
			'<a href="\\1\\3" '.$add.'>\\3</a>',

		),
	 $text);


	//$t = preg_replace('!([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)!i','\\1<a href="http://\\2"'.$add.'>\\2</a>', $t);

	//parsing emails
	$t = preg_replace('!"http://([-a-z0-9\._]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})"!i','mailto:\\1', $t);

	if(!empty($add_not_tohost))
		$t = preg_replace('!((\"(f|ht){1}tp://'.$add_not_tohost.')[-a-zA-Z0-9@:%_\+\.~#?&/=]+\")('.quotemeta($add).')!i','\\1', $t);

	//return substr($t,1);
	return $t;

}

/**
* Converts any text to utf-8
* convert to Utf8 if $str is not equals to 'UTF-8'
* @param $str string to convert
*/
function m_convert_to_utf8($str) {
	$first2 = substr($str, 0, 2);
    $first3 = substr($str, 0, 3);
    $first4 = substr($str, 0, 3);
	//UTF32_BIG_ENDIAN_BOM
	if($first4 == chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF)) {
		$encoding = "UTF-32BE";
	}
	//UTF32_LITTLE_ENDIAN_BOM
	elseif($first4 == chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00)) {
        $encoding = "UTF-32LE";
    }
	//UTF16_BIG_ENDIAN_BOM
	elseif($first2 == chr(0xFE) . chr(0xFF)) {
		$encoding = "UTF-16BE";
	}
	//UTF16_LITTLE_ENDIAN_BOM
	elseif($first2 == chr(0xFF) . chr(0xFE) ) {
        $encoding = "UTF-16LE";
    }
    //UTF8_BOM
	elseif($first3 == chr(0xEF) . chr(0xBB) . chr(0xBF)) {
        $encoding = "UTF-8";
    }
	else $encoding = mb_detect_encoding($str,"ASCII,JIS,UTF-8,EUC-JP,SJIS,ISO-8859-1,GBK");

	//return $encoding;
	//return $str;
	if( $encoding != "UTF-8" ) {
		return  mb_convert_encoding($str,"UTF-8",$encoding);
	}
	else {
		return $str;
	}
}

/**
* Codes a string to his numeric html equivalent, example: a = &#97;
* @param $value string to transform
*/
function m_compute_char($value) {
	$out="";
	for($i=0; $i<strlen($value); $i++) {
		$out.="&#".ord($value{$i}).";";
	}
	return $out;
}

/**
 * Passed date will be reversed\n
 * y/m/d H:i:s  => d/m/y H:i:s
 * @param $date date to process
 * */
function m_reverse_date($date) {
	$d=$c1=$m=$c2=$y=$c3=$hora='';

	preg_match("/([0-9]+)([^0-9]{1,1})([0-9]+)([^0-9]{1,1})([0-9]+)(.*)/i",$date,$matches);
	$d = $matches[1];
	$c1 = $matches[2];
	$m = $matches[3];
	$c2 = $matches[4];
	$y = $matches[5];
	$c3 = $matches[6]{0};
	$hora = substr($matches[6],1);

	$ret = sprintf("%d%s%d%s%d",$y,$c1,$m,$c2,$d);
	if($hora) $ret .= "$c3$hora";
	return $ret;
}

/**
 * UTF-8 Normalization of ASCII extended characters (strip accents)
 * @param $string text to normalize
 */
function m_normalize($string) {
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
  return str_replace($a, $b, $string);
}

/**
 * limits a text to the specified size (adds ... at the end if text has to be cut)\n
 * is utf-8 & html secure\n
 * @param $string text to limit
 * @param $size number of chars allowed
 * @param $mode:
 * - \b normal : cut by right
 * - \b reverse : cut by left
 * - \b middle : cut by middle
 * @return the cropped text
 * */
function m_txt_limit($string="", $size=10, $mode='normal') {
	$size = abs($size);
	$encoding = mb_detect_encoding($string);
	mb_internal_encoding($encoding);

	//if($encoding == 'UTF-8') $string = utf8_decode($string);
/*
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    $string = strtr($string, $trans_tbl);
*/
	//si no fa la llargada, kaka!
	if(mb_strlen($string) < $size) {
		return $string;
	}

	//mode pot ser 'reverse', es comença desde el final
	if($mode=='middle' && mb_strlen($string) > $size) {
		$t1 = limitLength($string,intval($size/2+2));
		$t2 = limitLength($string,intval($size/2+1),'reverse');
		$out = mb_str_replace("......","...",$t1.$t2);

		//if($encoding == 'UTF-8') $out = utf8_encode($out);
		return $out;
	}
	if($mode=='reverse') $string = mb_strrev ($string);


	$out = "";
	$tag = false;
	$closetag = false;
	$ultim = "";
	$l = mb_strlen($string);
	for($i=0,$j=0; $i<$l ; $i++) {
		$c = mb_substr($string,$i,1);
		if($mode != 'reverse') {
			if($c == '<' ) $tag = true;
			if($tag) {
				if(mb_substr($string,$i-1,1) == '<' && $c == '/') {
					$closetag = true;
					$ultim = "<";
				}
				if($closetag) $ultim .= $c;
				if($c == '>') {
					$tag = false;
					$closetag = false;
				}
			}
			else	$j++;
		}
		else {
			if($c == '>') {
				$tag = true;
				$ultim = "";
			}
			if($tag) {
				$ultim .= $c;
				if($c == '<') {
					$tag = false;
				}
			}
			else $j++;
		}
		if($j < $size-2) $out .= $c;
	}
	$out .= $ultim;

	if(mb_strlen($string) > mb_strlen($out)) $out .= '...';
	if($mode=='reverse') $out = mb_strrev($out);

	//if($encoding == 'UTF-8') $out = utf8_encode($out);
	return $out;
}


/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string  $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
function m_truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag (f.e. </b>)
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
						unset($open_tags[$pos]);
					}
				// if tag is an opening tag (f.e. <b>)
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}



if(!function_exists("mb_str_replace")) {
	function mb_str_replace($needle, $replacement, $haystack) {
		return implode($replacement, mb_split($needle, $haystack));
	}
}

if(!function_exists("mb_strrev")) {
	function mb_strrev($text) {
		return join('', array_reverse(
			preg_split('~~u', $text, -1, PREG_SPLIT_NO_EMPTY)
		));
	}
}
?>
