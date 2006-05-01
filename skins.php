<?php
	include('include.php');

	$skins = array();
	if(is_dir('skins.unpacked') && is_readable('skins.unpacked'))
	{
		$dh = opendir('skins.unpacked');
		while(($dir = readdir($dh)) !== false)
		{
			if($dir[0] == '#' || $dir[0] == '.') continue;
			if(!is_dir('skins.unpacked/'.$dir) || !is_readable('skins.unpacked/'.$dir) || !is_file('skins.unpacked/'.$dir.'/.name') || !is_readable('skins.unpacked/'.$dir.'/.name')) continue;
			$skins[$dir] = file_get_contents('skins.unpacked/'.$dir.'/.name');
		}
		closedir($dh);
	}

	if(isset($_GET['skin']) && isset($skins[$_GET['skin']]))
	{
		if(!is_dir('cache') && (file_exists('cache') || !is_writeable('.') || !mkdir('cache', 0770)))
			notice("Could not create /cache/.");
		elseif(!is_dir('cache/skins') && (file_exists('cache/skins') || !is_writeable('cache') || !mkdir('cache/skins', 0770)))
			notice("Could not create /cache/skins/");
		elseif((is_file('cache/skins/'.$_GET['skin'].'.tar.bz2') && !is_writeable('cache/skins')) || ((!is_file('cache/skins/'.$_GET['skin'].'.tar.bz2') || filemtime('cache/skins/'.$_GET['skin'].'.tar.bz2') < last_filemtime('skins.unpacked/'.$_GET['skin'])) && !execute('tar -cjhf cache/skins/'.$_GET['skin'].'.tar.bz2 skins.unpacked/'.$_GET['skin'])))
			notice("Could not create /cache/skins/".$_GET['skin'].".tar.bz2");
		else
		{
			header('Location: '.h_root.'/cache/skins/'.$_GET['skin'].'.tar.bz2', true, 307);
			die();
		}
	}

	$gui->title = $lang->getEntry('navigation', 'skins');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('skins', 'introduction')?></p>
<ul id="skins">
<?php
	foreach($skins as $dir=>$skin)
	{
?>
	<li><a href="skins.php?skin=<?=htmlspecialchars(urlencode($dir))?>"><?=htmlspecialchars(trim($skin))?></a></li>
<?php
	}
?>
</ul>
<?php
	$gui->htmlFoot();
	exit(0);
?>