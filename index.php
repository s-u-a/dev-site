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

<h3 id="chat"><?=$lang->getEntry('index', 'chat-heading')?></h3>
<p><?=$lang->getEntry('index', 'chat-text', 'irc.epd-me.net', 'sua-dev')?></p>
<?php
	$gui->htmlFoot();
	exit(0);
?>
