<?php
	# Determine s_root and h_root
	$this_filename = '/include.php';
	$__FILE__ = str_replace('\\', '/', __FILE__);
	if(substr($__FILE__, -strlen($this_filename)) !== $this_filename)
	{
		echo "Error determining s_root in ".__FILE__." on line ".__LINE__.".\n";
		exit(1);
	}
	define('s_root', substr($__FILE__, 0, -strlen($this_filename)));

	$beginning_cwd = getcwd();
	chdir(s_root);

	# Include all files from include/
	$includes = array();
	if(is_dir('include') && is_readable('include'))
	{
		$dh = opendir('include');
		while(($fname = readdir($dh)) !== false)
		{
			if($fname[0] == '.' || $fname[0] == '#') continue;
			$fname = 'include/'.$fname;
			if(!is_readable($fname)) continue;
			$includes[] = $fname;
		}
		closedir($dh);
		unset($fname);
		unset($dh);
	}
	natcasesort($includes);

	foreach($includes as $include)
	{
		include($include);
	}
	unset($include);

	chdir($beginning_cwd);
?>