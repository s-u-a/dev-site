<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'index');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('index', 'introduction')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>