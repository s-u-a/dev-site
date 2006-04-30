<?php
	include('include.php');
	
	$skins = array();
	$dh = opendir('skins');
	while(($dir = readdir($dh)) !== false)
	{
		if($dir[0] == '#' || $dir[0] == '.') continue;
		if(!is_dir('skins/'.$dir) || !is_file('skins/'.$dir.'/.name')) continue;
		$skins[$dir] = file_get_contents('skins/'.$dir.'/.name');
	}
	closedir($dh);
	
	if(isset($_GET['skin']) && isset($skins[$_GET['skin']]))
	{
		if(!is_file('cache/skins/'.$_GET['skin'].'.tar.bz2') || filemtime('cache/skins/'.$_GET['skin'].'.tar.bz2') < last_filemtime('skins/'.$_GET['skin']))
			exec('tar -cjfh cache/skins/'.$_GET['skin'].'.tar.bz2 skins/'.$_GET['skin']);
		header('Location: '.s_root.'/cache/skins/'.$_GET['skin'].'.tar.bz2', true, 307);
	}

	$gui->title = $lang->getEntry('navigation', 'skins');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('skins', 'introduction')?><p>
<ul id="skins">
<?php
	foreach($skins as $dir=>$skin)
	{
?>
	<li><a href="skins.php?skin=<?=htmlspecialchars(urlencode($dir))?>"><?=htmlspecialchars($skin)?></a></li>
<?php
	}
?>
</ul>
<?php
	$gui->htmlFoot();
	exit(0);
?>