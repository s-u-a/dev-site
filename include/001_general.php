<?php
	# Content type
	header('Content-type', 'application/xhtml+xml; charset=UTF-8');

	# Determine s_root and h_root
	$this_filename = '/engine/include.php';
	$__FILE__ = str_replace('\\', '/', __FILE__);
	if(substr($__FILE__, -strlen($this_filename)) !== $this_filename)
	{
		echo "Error determining s_root in ".__FILE__." on line ".__LINE__.".\n";
		exit(1);
	}
	define('s_root', substr($__FILE__, 0, -strlen($this_filename)));
	if(isset($_SERVER['SCRIPT_FILENAME']) && isset($_SERVER['PHP_SELF']) && substr($_SERVER['SCRIPT_FILENAME'], -strlen($_SERVER['PHP_SELF'])) == $_SERVER['PHP_SELF'])
		$document_root = substr(realpath($_SERVER['SCRIPT_FILENAME']), 0, -strlen($_SERVER['PHP_SELF']));
	elseif(isset($_SERVER['DOCUMENT_ROOT']))
		$document_root = $_SERVER['DOCUMENT_ROOT'];
	else $document_root = '/';

	if(substr($document_root, -1) == '/')
		$document_root = substr($document_root, 0, -1);
	define('h_root', substr(s_root, strlen($document_root)));
?>