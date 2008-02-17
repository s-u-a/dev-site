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

	function z7($filename, $dir)
	{
		return execute("find ".escapeshellarg($dir)." -type f | grep -v '/\.svn/' | xargs 7zr a ".escapeshellarg($filename));
	}

	function parseNewsXML($fname)
	{
		if(!is_file($fname) || !is_readable($fname)) return false;

		$this_xml = simplexml_load_file($fname);
		if(!$this_xml) return false;

		$return = array();
		if(!$this_xml->content) return false;
		$return['content'] = (string) $this_xml->content[0];

		if($this_xml->title) $return['title'] = trim((string) $this_xml->title[0]);
		if($this_xml->author) $return['author'] = trim((string) $this_xml->author[0]);

		return $return;
	}
?>
