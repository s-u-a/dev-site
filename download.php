<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'download');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('download', 'introduction')?></p>

<h3 id="svn"><?=$lang->getEntry('download', 'svn-heading')?></h3>
<p><?=$lang->getEntry('download', 'svn-text', 'svn://s-u-a.net/home/srv/svn/sua')?></p>

<h3 id="websvn"><?=$lang->getEntry('download', 'websvn-heading')?></h3>
<p><?=$lang->getEntry('download', 'websvn-text', 'http://websvn.dev.s-u-a.net/Stars%20Under%20Attack/')?></p>

<?php
	$archives = array();
	if(is_dir('archives') && is_readable('archives'))
	{
		$dh = opendir('archives');
		while(($fname = readdir($dh)) !== false)
		{
			if($fname[0] == '.' || $fname[0] == '#') continue;
			if(!is_file('archives/'.$fname) || !is_readable('archives/'.$fname)) continue;
			$archives[$fname] = filemtime('archives/'.$fname);
		}
		closedir($dh);
	}
	arsort($archives, SORT_NUMERIC);
	$archives = array_keys($archives);
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
	<li><a href="releases/<?=htmlspecialchars($current)?>"><?=htmlspecialchars($current)?></a></li>
</ul>
<?php
	}
	else
	{
?>
<p><?=$lang->getEntry('download', 'no-archive')?></p>
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
	<li><a href="releases/<?=htmlspecialchars($current)?>"><?=htmlspecialchars($current)?></a></li>
<?php
		}
?>
</ol>
<?php
	}

	$gui->htmlFoot();
	exit(0);
?>