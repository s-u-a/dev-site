<?php
	include('include.php');
	
	header('Location: https://bugs.dev.s-u-a.net/', true, 303);

	$gui->title = $lang->getEntry('navigation', 'bugs');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('bugs', 'redirect', 'https://bugs.s-u-a.net/')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>