<?php
	error_reporting(2047);

	# Content type
	header('Content-type: application/xhtml+xml; charset=UTF-8');

	# GZip compression
	ob_start('ob_gzhandler');

	# Determine h_root
	if(isset($_SERVER['PATH_INFO']) && isset($_SERVER['PHP_SELF']) && substr($_SERVER['PHP_SELF'], -strlen($_SERVER['PATH_INFO'])) == $_SERVER['PATH_INFO'])
		$_SERVER['PHP_SELF'] = substr($_SERVER['PHP_SELF'], 0, -strlen($_SERVER['PATH_INFO']));

	if(isset($_SERVER['SCRIPT_FILENAME']) && isset($_SERVER['PHP_SELF']) && substr($_SERVER['SCRIPT_FILENAME'], -strlen($_SERVER['PHP_SELF'])) == $_SERVER['PHP_SELF'])
		$document_root = substr(realpath($_SERVER['SCRIPT_FILENAME']), 0, -strlen($_SERVER['PHP_SELF']));
	elseif(isset($_SERVER['DOCUMENT_ROOT']))
		$document_root = $_SERVER['DOCUMENT_ROOT'];
	else $document_root = '/';

	if(substr($document_root, -1) == '/')
		$document_root = substr($document_root, 0, -1);
	define('real_h_root', substr(s_root, strlen($document_root)));
?>