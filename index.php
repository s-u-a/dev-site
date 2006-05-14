<?php
	include('include.php');

	function mailinglist_action($to, $email)
	{
		return mail($to, '', '', 'From: '.$email);
		
		/*$ph = popen('/usr/bin/mlmmj-recieve', 'w');
		if(!$ph) return false;
		fwrite($ph, "From: ".$email."\r\n");
		fwrite($ph, "To: ".$to."\r\n");
		fwrite($ph, "\r\n");
		pclose($ph);
		return true;*/
	}

	$gui->title = $lang->getEntry('navigation', 'index');
	$gui->htmlHead();
?>
<p><?=$lang->getEntry('index', 'introduction')?></p>

<h3 id="mailinglist"><?=$lang->getEntry('index', 'mailinglist-heading')?></h3>
<?php
	if(isset($_POST['email']) && strlen(trim($_POST['email'])) > 0)
	{
		if(eregi("^[a-z0-9\._-]+".chr(64)."+[a-z0-9\._-]+\.+[a-z]{2,4}$", $_POST['email']) && mailinglist_action('sua-dev+'.((isset($_POST['unsubscribe']) && $_POST['unsubscribe']) ? 'unsubscribe' : 'subscribe').'@s-u-a.net', $_POST['email']))
		{
?>
<p class="successful"><?=$lang->getEntry('index', 'mailinglist-successful')?></p>
<?php
		}
		else
		{
?>
<p class="error"><?=$lang->getEntry('index', 'mailinglist-error')?></p>
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
<form action="<?=h_root?>/" method="post">
	<dl>
		<dt><label for="i-email"><?=$lang->getEntry('index', 'mailinglist-email')?></label></dt>
		<dd><input type="text" name="email" id="i-email" /></dd>
	</dl>
	<ul>
		<li><button type="submit"><?=$lang->getEntry('index', 'mailinglist-subscribe')?></button></li>
		<li><button name="unsubscribe" type="submit" value="1"><?=$lang->getEntry('index', 'mailinglist-unsubscribe')?></button></li>
	</ul>
</form>

<h3 id="chat"><?=$lang->getEntry('index', 'chat-heading')?></h3>
<p><?=$lang->getEntry('index', 'chat-text', 'irc.epd-me.net', 'sua-dev')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>
