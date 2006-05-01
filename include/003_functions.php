<?php
	function last_filemtime($dir)
	{
		if(!is_dir($dir) || !is_readable($dir)) return false;

		$last_mtime = filemtime($dir);

		$dh = opendir($dir);
		while(($fname = readdir($dh)) !== false)
		{
			if($fname == '.' || $fname == '..') continue;
			if(!is_dir($dir.'/'.$fname)) $mtime = filemtime($dir.'/'.$fname);
			else $mtime = last_filemtime($dir.'/'.$fname);
			if($mtime > $last_mtime) $last_mtime = $mtime;
		}
		closedir($dh);

		return $last_mtime;
	}

	function execute($command)
	{
		exec($command, $output, $return);
		return ($return == "0");
	}

	function parseNewsXML($fname)
	{
		return array('title' => 'test news', 'author' => 'it wasn\'t me!', 'content' => '<p>Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla</p><p>Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla Blablablabla</p>');
	}
?>