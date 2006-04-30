<?php
	include('include.php');
	
	$skins = array();
	$dh = opendir('skins.unpacked');
	while(($dir = readdir($dh)) !== false)
	{
		if($dir[0] == '#' || $dir[0] == '.') continue;
		if(!is_dir('skins.unpacked/'.$dir) || !is_file('skins.unpacked/'.$dir.'/.name')) continue;
		$skins[$dir] = file_get_contents('skins.unpacked/'.$dir.'/.name');
	}
	closedir($dh);
	
	if(isset($_GET['skin']) && isset($skins[$_GET['skin']]))
	{
		if(!is_file('cache/skins/'.$_GET['skin'].'.tar.bz2') || filemtime('cache/skins/'.$_GET['skin'].'.tar.bz2') < last_filemtime('skins.unpacked/'.$_GET['skin']))
			exec('tar -cjfh cache/skins/'.$_GET['skin'].'.tar.bz2 skins.unpacked/'.$_GET['skin']);
		header('Location: '.s_root.'/cache/skins/'.$_GET['skin'].'.tar.bz2', true, 307);
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