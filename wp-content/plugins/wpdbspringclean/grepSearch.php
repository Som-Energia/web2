<?php
define("SLASH", stristr($_SERVER['SERVER_SOFTWARE'], "win") ? "\\" : "/");


function php_grep($q, $path){
	$ret = '';
	$fp = opendir($path);
	while($f = readdir($fp)){
		if( preg_match("#^\.+$#", $f) ) continue; // ignore symbolic links
		$file_full_path = $path.SLASH.$f;
		if(is_dir($file_full_path)) {
			$ret .= php_grep($q, $file_full_path);
		} else if (!stristr($file_full_path, ".php")) continue; //ignore all files except php files
		else if( stristr(file_get_contents($file_full_path), $q) ) {
			$ret .= "$file_full_path\n";
		}
	}
	return $ret;
}
?>