<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'index');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('index', 'introduction')?></p>

<?php
	if(is_file('mlmmj.php') && is_readable('mlmmj.php'))
	{
?>
<h3 id="mailinglist"><?=$lang->getEntry('index', 'mailinglist-heading')?></h3>
<?php
		if(isset($_GET['mlsuccess']))
		{
			if($_GET['mlsuccess'])
			{
?>
<p class="successful"><?=$lang->getEntry('mailinglist-successful')?></p>
<?php
			}
			else
			{
?>
<p class="error"><?=$lang->getEntry('mailinglist-error')?></p>
<?php
			}
		}
		else
		{
?>
<p><?=$lang->getEntry('index', 'mailinglist-text')?></p>
<?php
		}
?>
<form action="mlmmj.php" method="post">
	<dl>
		<dt><label for="i-email"><?=$lang->getEntry('index', 'mailinglist-email')?></label></dt>
		<dd><input type="text" name="email" id="i-email" /></dd>
	</dl>
	<ul>
		<li><button type="submit" value="subscribe"><?=$lang->getEntry('index', 'mailinglist-subscribe')?></button><input name="mailinglist" type="hidden" value="sua-dev@s-u-a.net" /><input name="redirect_failure" type="hidden" value="http://<?=htmlspecialchars($_SERVER['HTTP_HOST'].h_root.'/?mlsuccess=0')?>#mailinglist" /><input name="redirect_success" type="hidden" value="http://<?=htmlspecialchars($_SERVER['HTTP_HOST'].h_root.'/?mlsuccess=0')?>#mailinglist" /></li>
		<li><button type="submit" value="unsubcribe"><?=$lang->getEntry('index', 'mailinglist-unsubscribe')?></button></li>
	</ul>
</form>
<?php
	}
?>

<h3 id="chat"><?=$lang->getEntry('index', 'chat-heading')?></h3>
<p><?=$lang->getEntry('index', 'chat-text', 'irc.epd-me.net', 'sua-dev')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>