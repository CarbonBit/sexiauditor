<?php

$achievementFile = "/var/www/admin/achievements.txt";
$credstoreFile = "/var/www/.vmware/credstore/vicredentials.xml";
$xmlStartPath = "/opt/vcron/data/";

function humanFileSize($size,$unit="") {
        if( (!$unit && $size >= 1<<30) || $unit == "GB")
                return number_format($size/(1<<30),2)."GB";
        if( (!$unit && $size >= 1<<20) || $unit == "MB")
                return number_format($size/(1<<20),2)."MB";
        if( (!$unit && $size >= 1<<10) || $unit == "KB")
                return number_format($size/(1<<10),2)."KB";
        return number_format($size)." bytes";
}

function human_filesize($size, $precision = 2, $unity = 'B') {
    for($i = 0; ($size / 1024) > 0.9; $i++, $size /= 1024) {}
    return round($size, $precision).' '.[$unity,"k$unity","M$unity","G$unity","T$unity","P$unity","E$unity","Z$unity","Y$unity"][$i];
}

function unlinkRecursive($dir) {
	if(!$dh = @opendir($dir))
        	return;
        while (false !== ($obj = readdir($dh))) {
        	if($obj == '.' || $obj == '..')
                	continue;
                if (!@unlink($dir . '/' . $obj))
                        unlinkRecursive($dir.'/'.$obj);
        }
        closedir($dh);
        @rmdir($dir);
        return;
}

function rcopy($src, $dest){
	if(!is_dir($src)) return false;
        if(!is_dir($dest))
        	if(!mkdir($dest))
                	return false;
        $i = new DirectoryIterator($src);
        foreach($i as $f) {
             	if($f->isFile())
                      	copy($f->getRealPath(), "$dest/" . $f->getFilename());
                else if(!$f->isDot() && $f->isDir())
                     	rcopy($f->getRealPath(), "$dest/$f");
        }
}

function php_file_tree_dir($directory, $first_call = true) {
	$file = scandir($directory);
	natcasesort($file);
	$files = $dirs = array();
	foreach($file as $this_file) {
		if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
	}
	$file = array_merge($dirs, $files);

	if( count($file) > 2 ) {
		$php_file_tree = "<ul";
		if( $first_call ) { $php_file_tree .= " class=\"php-file-tree\""; $first_call = false; }
		$php_file_tree .= ">";
		foreach( $file as $this_file ) {
			if( $this_file != "." && $this_file != ".." ) {
				if( is_dir("$directory/$this_file") ) {
					$php_file_tree .= "<li class=\"pft-directory\"><input type=\"checkbox\" name=\"pathChecked[]\" value=\"$directory/$this_file\"> <a href=\"#\"><strong>" . htmlspecialchars($this_file) . "</strong></a>";
					$php_file_tree .= php_file_tree_dir("$directory/$this_file" , false);
					$php_file_tree .= "</li>";
				} else {
					$php_file_tree .= "<li class=\"pft-file\"><input type=\"checkbox\" name=\"pathChecked[]\" value=\"$directory/$this_file\"> <strong>" . htmlspecialchars($this_file) . "</strong> (". humanFileSize(filesize("$directory/$this_file")) . ")</li>";
				}
			}
		}
		$php_file_tree .= "</ul>";
	}
	return $php_file_tree;
}

function rand_line($fileName, $maxLineLength = 4096) {
    $handle = @fopen($fileName, "r");
    if ($handle) {
        $random_line = null;
        $line = null;
        $count = 0;
        while (($line = fgets($handle, $maxLineLength)) !== false) {
            $count++;
            // P(1/$count) probability of picking current line as random line
            if(rand() % $count == 0) {
              $random_line = $line;
            }
        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
            fclose($handle);
            return null;
        } else {
            fclose($handle);
        }
        return $random_line;
    }
}

function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
		switch ($num % 10) {
			// Handle 1st, 2nd, 3rd
			case 1:  return $num.'st';
			case 2:  return $num.'nd';
			case 3:  return $num.'rd';
		}
    }
    return $num.'th';
}

function secureInput($data) { return htmlspecialchars(stripslashes(trim($data))); }

function sendMailNewUser($username, $displayname, $role, $acess) {
  $bodyTextContent    = 'A new user have been created with your email to access audit platform on ' . gethostname() . '<br/>Please find information below:<br />';
  $bodyTextContent   .= 'Username: ' . $username . '<br />';
  $bodyTextContent   .= 'Display Name: ' . $displayname . '<br />';
  $bodyTextContent   .= 'Role: ' . $role . '<br />';
  $bodyTextContent   .= 'Access: '. $acess . '<br />';
  $bodyContent = preg_replace('/\%SEXIAUDITOR_TEXT\%/s', $bodyTextContent, file_get_contents('mail-template/newuser.html'));

  return $bodyContent;
}
?>
