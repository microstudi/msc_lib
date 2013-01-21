<?php
/**
* @file functions/langs.php
* @author Ivan Vergés
* @brief Language related functions file\n
*
* @section usage Usage
* m_lang_set("$dir/languages",array("en","es"));\n
* m_lang_set("$dir/languages2",array("en","es"));\n
* m_lang_echo("hello_world");

*/

/**
 * return a language translation
 * @param $code string to search the language equivalent
 * @return the $code itsef if no correspondence are found in the language files or the correspondence if found\n
 *
 * */
function m_lang_echo($code,$return_false=false) {
	global $CONFIG;

	if($CONFIG->locale[$code])	$txt = $CONFIG->locale[$code];
	elseif($return_false) return false;
	else $txt = "{"."$code}";
	$lang = $CONFIG->locale_lang[$code];

	if($lang != $CONFIG->lang && ($CONFIG->lang_error_prefix || $CONFIG->lang_error_sufix)) {
		eval('$txt = "' . $CONFIG->lang_error_prefix . '".$txt."' . $CONFIG->lang_error_sufix .'";');
	}
	return $txt;
}
/**
 * Sets languages dirs and precedence
 * @param $dir directory where to search language files
 * @param $langs langs to search in $dir (and order of precedence)
 * */
function m_lang_set($dir=null,$langs=array()) {
	global $CONFIG;

	if(!is_array($CONFIG->lang_available)) $CONFIG->lang_available = array();
	if(!is_array($CONFIG->lang_files)) $CONFIG->lang_files = array();
	if(is_dir($dir)) {
		foreach($langs as $lang) {
			if(empty($CONFIG->lang)) $CONFIG->lang = $lang;
			if(!in_array($lang,$CONFIG->lang_available)) $CONFIG->lang_available[] = $lang;
			if(is_file("$dir/$lang.php")) {
				if(!is_array($CONFIG->lang_files[$lang])) $CONFIG->lang_files[$lang] = array();
				$CONFIG->lang_files[$lang][] = "$dir/$lang.php";
			}
		}
	}
	return $CONFIG->lang_available;
}
/**
 * Select the current lang
 * @param $language language to use
 * @param $error_prefix or @param $error_sufix will be evaluated with eval()\n
 * EX: m_lang_select("es",true,'<span style=\"color:red\">[$lang]</span>');
 * @param $follow if \b true searches for alternative languages files correspondences if the original code is not found in the current lang file
 * */
function m_lang_select($language='', $follow=true, $error_prefix='', $error_sufix = '') {
	global $CONFIG;

	if(in_array($language,$CONFIG->lang_available)) {
		if( !$follow && !array_key_exists($language,$CONFIG->lang_files) ) {
			return $CONFIG->lang;
		}


		$CONFIG->lang = $language;
		$CONFIG->lang_error_prefix = $error_prefix;
		$CONFIG->lang_error_sufix = $error_sufix;
		$processed = array();

		foreach((array($language => $CONFIG->lang_files[$language]) + $CONFIG->lang_files) as $l => $arr) {
			if(!is_array($arr)) continue;
			foreach($arr as $f) {
				if(in_array($f,$processed)) continue;
				$processed[] = $f;
				ob_start();
				if(include($f)) {
					if(is_array($lang)) {
						foreach($lang as $k => $v) {
							if(empty($CONFIG->locale[$k])) {
								$CONFIG->locale[$k] = $v;
								$CONFIG->locale_lang[$k] = $l;
							}
						}
					}
				}
				ob_end_clean();
				//echo "[$l]: ";print_r($CONFIG->locale_lang);
			}
		}
	}
	return $CONFIG->lang;
}

/**
 * Returns the appropiate text for passed lang in a object with langs\n
 * Example:\n
 * $ob->text_en = 'This is english';\n
 * $ob->text_es = 'Esto es castellano';\n
 * echo m_lang_object($ob,'text','en');\n
 * @param $ob the object to search
 * @param $field prefix of the object parameter to search
 * @param $lang lang to search (if empty default defined will be used)
 * */
function m_lang_object($ob,$field='text',$lang='') {

	global $CONFIG;

	if(empty($lang)) $lang = $CONFIG->lang;
	$langs_precedence = $CONFIG->lang_available;

	$ret = $ob->{$field.'_'.$lang};
	if(empty($ret)){
		//$Global['error_lang']=$lang;
		foreach($langs_precedence as $l) {
			if(trim($ob->{$field.'_'.$l}) != '') {
				$ret = $ob->{$field.'_'.$l};
				break;
			}
		}
	}

	return trim($ret);
}

/**
 * NOT USED - Returns a array with all lang texts from a files
 * */
function m_lang_sql_texts($id=1,$fallback=true,$table = 'minitexts',$field='text') {
	global $CONFIG;
/*
	$debug = false;
	if($fallback) $debug = $Global['debug'];
	$LANG = new LangSet(true,$debug);

	//fallback
	if($fallback) {
		$sql="SELECT * FROM $table WHERE journal_id='".$Global['fallback_journal']."'";
		if($r=getSQL($sql)) {
			foreach($r as $ob) {
				$LANG->{$ob->part} = $ob->text;
			}
		}
	}
	$sql="SELECT * FROM $table WHERE journal_id='$id'";
	if($r=getSQL($sql)) {
		foreach($r as $ob) {
			$LANG->{$ob->part} = $ob->text;
		}
	}
	$LANG->index = $LANG->children;
	return $LANG;
	* */
}

/**
 * ISO 639-1 languages
 * @return a array of all ISO 639 languages
 */
function m_lang_iso639() {
$ISO639 = array(
"aa" => "Afar",
"ab" => "Abkhazian",
"af" => "Afrikaans",
"ak" => "Akan",
"sq" => "Albanian",
"am" => "Amharic",
"ar" => "Arabic",
"an" => "Aragonese",
"hy" => "Armenian",
"as" => "Assamese",
"av" => "Avaric",
"ae" => "Avestan",
"ay" => "Aymara",
"az" => "Azerbaijani",
"ba" => "Bashkir",
"bm" => "Bambara",
"eu" => "Basque",
"be" => "Belarusian",
"bn" => "Bengali",
"bh" => "Bihari",
"bi" => "Bislama",
"bs" => "Bosnian",
"br" => "Breton",
"bg" => "Bulgarian",
"my" => "Burmese",
"ca" => "Catalan; Valencian",
"ch" => "Chamorro",
"ce" => "Chechen",
"zh" => "Chinese",
"cu" => "Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic",
"cv" => "Chuvash",
"kw" => "Cornish",
"co" => "Corsican",
"cr" => "Cree",
"cs" => "Czech",
"da" => "Danish",
"dv" => "Divehi; Dhivehi; Maldivian",
"nl" => "Dutch; Flemish",
"dz" => "Dzongkha",
"en" => "English",
"eo" => "Esperanto",
"et" => "Estonian",
"ee" => "Ewe",
"fo" => "Faroese",
"fj" => "Fijian",
"fi" => "Finnish",
"fr" => "French",
"fy" => "Western Frisian",
"ff" => "Fulah",
"ka" => "Georgian",
"de" => "German",
"gd" => "Gaelic; Scottish Gaelic",
"ga" => "Irish",
"gl" => "Galician",
"gv" => "Manx",
"el" => "Greek, Modern (1453-)",
"gn" => "Guarani",
"gu" => "Gujarati",
"ht" => "Haitian; Haitian Creole",
"ha" => "Hausa",
"he" => "Hebrew",
"hz" => "Herero",
"hi" => "Hindi",
"ho" => "Hiri Motu",
"hr" => "Croatian",
"hu" => "Hungarian",
"ig" => "Igbo",
"is" => "Icelandic",
"io" => "Ido",
"ii" => "Sichuan Yi; Nuosu",
"iu" => "Inuktitut",
"ie" => "Interlingue; Occidental",
"ia" => "Interlingua (International Auxiliary Language Association)",
"id" => "Indonesian",
"ik" => "Inupiaq",
"it" => "Italian",
"jv" => "Javanese",
"ja" => "Japanese",
"kl" => "Kalaallisut; Greenlandic",
"kn" => "Kannada",
"ks" => "Kashmiri",
"kr" => "Kanuri",
"kk" => "Kazakh",
"km" => "Central Khmer",
"ki" => "Kikuyu; Gikuyu",
"rw" => "Kinyarwanda",
"ky" => "Kirghiz; Kyrgyz",
"kv" => "Komi",
"kg" => "Kongo",
"ko" => "Korean",
"kj" => "Kuanyama; Kwanyama",
"ku" => "Kurdish",
"lo" => "Lao",
"la" => "Latin",
"lv" => "Latvian",
"li" => "Limburgan; Limburger; Limburgish",
"ln" => "Lingala",
"lt" => "Lithuanian",
"lb" => "Luxembourgish; Letzeburgesch",
"lu" => "Luba-Katanga",
"lg" => "Ganda",
"mk" => "Macedonian",
"mh" => "Marshallese",
"ml" => "Malayalam",
"mi" => "Maori",
"mr" => "Marathi",
"ms" => "Malay",
"mg" => "Malagasy",
"mt" => "Maltese",
"mn" => "Mongolian",
"na" => "Nauru",
"nv" => "Navajo; Navaho",
"nr" => "Ndebele, South; South Ndebele",
"nd" => "Ndebele, North; North Ndebele",
"ng" => "Ndonga",
"ne" => "Nepali",
"nn" => "Norwegian Nynorsk; Nynorsk, Norwegian",
"nb" => "Bokmål, Norwegian; Norwegian Bokmål",
"no" => "Norwegian",
"ny" => "Chichewa; Chewa; Nyanja",
"oc" => "Occitan (post 1500); Provençal",
"oj" => "Ojibwa",
"or" => "Oriya",
"om" => "Oromo",
"os" => "Ossetian; Ossetic",
"pa" => "Panjabi; Punjabi",
"fa" => "Persian",
"pi" => "Pali",
"pl" => "Polish",
"pt" => "Portuguese",
"ps" => "Pushto; Pashto",
"qu" => "Quechua",
"rm" => "Romansh",
"ro" => "Romanian; Moldavian; Moldovan",
"rn" => "Rundi",
"ru" => "Russian",
"sg" => "Sango",
"sa" => "Sanskrit",
"si" => "Sinhala; Sinhalese",
"sk" => "Slovak",
"sl" => "Slovenian",
"se" => "Northern Sami",
"sm" => "Samoan",
"sn" => "Shona",
"sd" => "Sindhi",
"so" => "Somali",
"st" => "Sotho, Southern",
"es" => "Spanish; Castilian",
"sc" => "Sardinian",
"sr" => "Serbian",
"ss" => "Swati",
"su" => "Sundanese",
"sw" => "Swahili",
"sv" => "Swedish",
"ty" => "Tahitian",
"ta" => "Tamil",
"tt" => "Tatar",
"te" => "Telugu",
"tg" => "Tajik",
"tl" => "Tagalog",
"th" => "Thai",
"bo" => "Tibetan",
"ti" => "Tigrinya",
"to" => "Tonga (Tonga Islands)",
"tn" => "Tswana",
"ts" => "Tsonga",
"tk" => "Turkmen",
"tr" => "Turkish",
"tw" => "Twi",
"ug" => "Uighur; Uyghur",
"uk" => "Ukrainian",
"ur" => "Urdu",
"uz" => "Uzbek",
"ve" => "Venda",
"vi" => "Vietnamese",
"vo" => "Volapük",
"cy" => "Welsh",
"wa" => "Walloon",
"wo" => "Wolof",
"xh" => "Xhosa",
"yi" => "Yiddish",
"yo" => "Yoruba",
"za" => "Zhuang; Chuang",
"zu" => "Zulu",
);
	return $ISO639;
}
?>
