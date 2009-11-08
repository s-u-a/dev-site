<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'download');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('download', 'introduction')?></p>

<h3 id="svn"><?=$lang->getEntry('download', 'svn-heading')?></h3>
<p><?=$lang->getEntry('download', 'svn-text', 'svn://s-u-a.net/home/srv/svn/sua')?></p>

<h3 id="websvn"><?=$lang->getEntry('download', 'websvn-heading')?></h3>
<p><?=$lang->getEntry('download', 'websvn-text', "http://svn.s-u-a.net/")?></p>

<?php
	$archives = array();
	if(is_dir('download.raw') && is_readable('download.raw'))
	{
		$dh = opendir('download.raw');
		while(($dir = readdir($dh)) !== false)
		{
			if($dir[0] == '#' || $dir[0] == '.') continue;
			if(!is_dir('download.raw/'.$dir) || !is_readable('download.raw/'.$dir)) continue;
			$archives[] = $dir;
		}
		closedir($dh);
	}

	if(isset($_GET['version']) && in_array($_GET["version"], $archives))
	{
		if(!is_dir('cache') && (file_exists('cache') || !is_writeable('.') || !mkdir('cache', 0770)))
			notice("Could not create /cache/.");
		elseif(!is_dir('cache/download') && (file_exists('cache/download') || !is_writeable('cache') || !mkdir('cache/download', 0770)))
			notice("Could not create /cache/download/");
		else
		{
			$old_cwd = getcwd();
			chdir('download.raw');
			if((is_file('../cache/download/sua_'.$_GET['version'].'.7z') && !is_writeable('../cache/download')) || ((!is_file('../cache/download/sua_'.$_GET['version'].'.7z') || filemtime('../cache/download/sua_'.$_GET['version'].'.7z') < last_filemtime($_GET['version'])) && !z7("../cache/download/sua_".$_GET['version'].".7z", $_GET['version'])))
				notice("Could not create /cache/download/sua_".$_GET['version'].".7z");
			else
			{
				header('Location: '.h_root.'/cache/download/sua_'.$_GET['version'].'.7z', true, 307);
				die();
			}
			chdir($old_cwd);
		}
	}

	natcasesort($archives);
	$archives = array_reverse($archives);
	$current = array_shift($archives);
?>
<h3 id="archives"><?=$lang->getEntry('download', 'archives-heading')?></h3>
<p><?=$lang->getEntry('download', 'archives-text')?></p>
<h4 id="current-archive"><?=$lang->getEntry('download', 'current-archive')?></h4>
<?php
	if($current)
	{
?>
<ul>
	<li><a href="?version=<?=htmlspecialchars(urlencode($current))?>"><?=htmlspecialchars($current)?></a></li>
</ul>
<?php
	}
	else
	{
?>
<p class="nothing-to-do"><?=$lang->getEntry('download', 'no-archive')?></p>
<?php
	}

	if(count($archives) > 0)
	{
?>
<h4 id="old-archives"><?=$lang->getEntry('download', 'old-archives')?></h4>
<ol>
<?php
		foreach($archives as $archive)
		{
?>
	<li><a href="?version=<?=htmlspecialchars(urlencode($archive))?>"><?=htmlspecialchars($archive)?></a></li>
<?php
		}
?>
</ol>
<?php
	}

	$gui->htmlFoot();
	exit(0);
?>
