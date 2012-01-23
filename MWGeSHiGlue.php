<?php

$mwggGeSHiPath = (
		isset($mwggGeSHiPath) 		// Don't want to run is_string
		&& is_string($mwggGeSHiPath)	// on an unset symbol.
	) ? $mwggGeSHiPath : "$IP/extensions/geshi";

// If $mwggGeSHiPath contains a trailing "/", strip it.
if('/' == substr($mwggGeSHiPath, -1))
	$mwggGeSHiPath = substr($mwggGeSHiPath, 0, -1);

require_once("$mwggGeSHiPath/geshi.php");

$languagesPath = "$mwggGeSHiPath/geshi";
 
$wgExtensionFunctions[] = "MWGeSHiGlueInit";

$wgExtensionCredits['parserhook'][] = array( 
        'name' => 'MWGeSHiGlue', 
        'author' => 'Michael Mol', 
        'version' => '1',
        'description' => '',
);

$languages = array();

function MWGeSHiGlueInit() {
        global $wgParser, $languages, $languagesPath;
 
        ReadLanguages();
 
	$wgParser->setHook('lang', 'LangTag');
}
 
function ReadLanguages() {
        global $languages, $languagesPath;

	// TODO: Cache language list, only read directory/
 
        $dirHandle = opendir($languagesPath);
	if(FALSE !== $dirHandle) {
	        $pattern = "^(.*)\.php$";
 
	        while ($file = readdir($dirHandle)) {
			// TODO: Replace eregi with a PCRE call: eregi is
			// deprecated as of PHP 5.3.0
	                if( eregi($pattern, $file) )
	                        $languages[] = eregi_replace(
					$pattern,
					"\\1",
					$file); 
	        }
	        closedir($dirHandle);
	}
	else {
		error_log("MWGeshiGlue: Invalid directory path"
			. "- [$languagesPath]");
	}
}

function LangTag($source, $settings) {
        global $languages, $languagesPath;
        $language = strtolower(array_shift($settings));

	// list all languages supported
        if($language == "list") {
	    sort($languages);
            return "<br>List of supported languages for <b>Geshi "
	    	. GESHI_VERSION  . "</b>:<br><ul><li>"
                . implode("</li><li>", $languages) . '</li></ul>';
        }

	// TODO: Allow some GET parameter to override language mappings here.
	// e.g. "&mwgglm=perl6:text" to override 'perl6' with 'text'

	// TODO: Build a cache key derived from $language and hash(s) of
	// $source. If memcached contains an element 
        if(!in_array($language, $languages)) {
            $language = 'text';
	}
 
        $geshi = new GeSHi($source, $language, $languagesPath); 

	// Yes, we want CSS classes.
	$geshi->enable_classes();

	// A CSS toy for anyone looking for highlighted content.
        $geshi->set_overall_class('highlighted_source');

	// Have GeSHi do its processing.
	$code = $geshi->parse_code();

	// We're done with the GeSHi class, let it go.
	unset($geshi);
	
	// Replace \n with <br />
	$code = str_replace("\n",'<br />', $code);
  
        // I don't remember what, specifically, this was supposed to fix.
	// It's in the village pump, somewhere.
	$code = preg_replace("/(^[ \t]*\n|\n[ \t]*\$)/","",$code);

        return $code;
}

