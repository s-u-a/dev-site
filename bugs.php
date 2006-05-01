<?php
	include('include.php');
	
	header('Location: https://dev.s-u-a.net/bugs/', true, 303);

	$gui->title = $lang->getEntry('navigation', 'bugs');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('bugs', 'redirect', 'https://dev.s-u-a.net/bugs/')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>