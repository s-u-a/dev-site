<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'index');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('index', 'introduction')?></p>

<h3 id="forum"><?=$lang->getEntry('index', 'forum-heading')?></h3>
<p><?=$lang->getEntry('index', 'forum-text', h_root.'/forum')?></p>

<h3 id="chat"><?=$lang->getEntry('index', 'chat-heading')?></h3>
<p><?=$lang->getEntry('index', 'chat-text', 'irc.epd-me.net', 'sua-dev')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>