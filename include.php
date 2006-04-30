<?php
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
?>