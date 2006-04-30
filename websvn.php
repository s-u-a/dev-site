<?php
	include('include.php');
	
	header('Location: http://websvn.dev.s-u-a.net/', true, 303);

	$gui->title = $lang->getEntry('navigation', 'websvn');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('websvn', 'redirect', 'http://websvn.dev.s-u-a.net/')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>