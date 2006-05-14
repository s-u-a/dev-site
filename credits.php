<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'credits');
	$gui->htmlHead();
?>
<dl>
	<dt><a href="mailto:webmaster@s-u-a.net">Candid Dauth</a> (<a href="http://cdauth.de/">http://cdauth.de/</a>)</dt>
	<dd><?=$lang->getEntry('credits', 'cdauth-work')?></dd>

	<dt><a href="mailto:rmueller@s-u-a.net">rmueller</a> (<a href="http://rmueller.info/">http://rmueller.info/</a>)</dt>
	<dd><?=$lang->getEntry('credits', 'rmueller-work')?></dd>

	<dt><a href="mailto:soltari@s-u-a.net">Soltari</a></dt>
	<dd><?=$lang->getEntry('credits', 'soltari-work')?></dd>

	<dt>Geki</dt>
	<dd><?=$lang->getEntry('credits', 'geki-work')?></dd>

	<dt><a href="mailto:barade@s-u-a.net">Barade</a></dt>
	<dd><?=$lang->getEntry('credits', 'barade-work')?></dd>
</dl>
<?php
	$gui->htmlFoot();
	exit(0);
?>