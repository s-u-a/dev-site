<?php
	include('include.php');

	$skins = array();
	if(is_dir('skins.raw') && is_readable('skins.raw'))
	{
		$dh = opendir('skins.raw');
		while(($dir = readdir($dh)) !== false)
		{
			if($dir[0] == '#' || $dir[0] == '.') continue;
			if(!is_dir('skins.raw/'.$dir) || !is_readable('skins.raw/'.$dir)) continue;
			$name = null;
			if(is_file('skins.raw/'.$dir.'/types') && is_readable('skins.raw/'.$dir.'/types'))
			{
				$fh_types = fopen("skins.raw/".$dir."/types", "r");
				$name = trim(fgets($fh_types));
				fclose($fh_types);
			}
			$skins[$dir] = ($name ? $name : $dir);
		}
		closedir($dh);
	}

	if(isset($_GET['skin']) && isset($skins[$_GET['skin']]))
	{
		if(!is_dir('cache') && (file_exists('cache') || !is_writeable('.') || !mkdir('cache', 0770)))
			notice("Could not create /cache/.");
		elseif(!is_dir('cache/skins') && (file_exists('cache/skins') || !is_writeable('cache') || !mkdir('cache/skins', 0770)))
			notice("Could not create /cache/skins/");
		else
		{
			$old_cwd = getcwd();
			chdir('skins.raw');
			if((is_file('../cache/skins/sua_skin_'.$_GET['skin'].'.7z') && !is_writeable('../cache/skins')) || ((!is_file('../cache/skins/sua_skin_'.$_GET['skin'].'.7z') || filemtime('../cache/skins/sua_skin_'.$_GET['skin'].'.7z') < last_filemtime($_GET['skin'])) && !z7("../cache/skins/sua_skin_".$_GET['skin'].".7z", $_GET['skin'])))
				notice("Could not create /cache/skins/sua_skin_".$_GET['skin'].".7z");
			else
			{
				header('Location: '.h_root.'/cache/skins/sua_skin_'.$_GET['skin'].'.7z', true, 307);
				die();
			}
			chdir($old_cwd);
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
	<li><a href="?skin=<?=htmlspecialchars(urlencode($dir))?>"><?=htmlspecialchars(trim($skin))?></a></li>
<?php
	}
?>
</ul>
<?php
	$gui->htmlFoot();
	exit(0);
?>