<?php
	include('include.php');

	function print_mails(&$mails, $prefix="", $active_fname=false)
	{
		global $lang;

		echo $prefix."<ol class=\"mails\">\n";
		foreach($mails as $mail)
		{
			echo $prefix."\t<li";
			if($active_fname !== false && $mail['fname'] == $active_fname) echo " class=\"active\"";
			echo "><a href=\"mailinglist.php?fname=".htmlspecialchars(urlencode($mail['fname']))."\" class=\"subject\">".htmlspecialchars($mail['subject'])."</a> ".$lang->getEntry('mailinglist', 'by')." <span class=\"mail-sender\">".mail_make_links(htmlspecialchars($mail['sender']))."</span>, <span class=\"mail-time\">".htmlspecialchars(date('Y-m-d, H:i:s', $mail['time']))."</span>";
			if(count($mail['sub']) > 0)
			{
				echo "\n";
				print_mails($mail['sub'], "\t\t".$prefix, $active_fname);
				echo $prefix."\t</li>\n";
			}
			else echo "</li>\n";
		}
		echo $prefix."</ol>\n";
	}

	$gui->title = $lang->getEntry('navigation', 'mailinglist');

	mail_update_database();
	$tree = mail_make_tree();

	if(isset($_GET['fname']))
	{
		# Show specific message
		if(!$message = mail_get_message_info($_GET['fname']))
		{
			$gui->htmlHead();
?>
<p class="error"><?=$lang->getEntry('mailinglist', 'no-message')?></p>
<?php
			$gui->htmlFoot();
			exit(1);
		}

		if(isset($_GET['mimepart']))
		{
			if(!isset($message['content'][$_GET['mimepart']]))
			{
				$gui->htmlHead();
?>
<p class="error"><?=$lang->getEntry('mailinglist', 'no-mimepart')?></p>
<?php
				$gui->htmlFoot();
				exit(1);
			}

			$cinfo = &$message['content'][$_GET['mimepart']];
			if(isset($cinfo['headers']['content-type']))
				header("Content-type: ".$cinfo['headers']['content-type']);
			else
				header("Content-type: text/plain; charset=UTF-8");
			if(isset($cinfo['headers']['content-disposition']))
				header("Content-disposition: ".$cinfo['headers']['content-disposition']);

			echo $cinfo['content'];
			exit(0);
		}

		$gui->htmlHead();
?>
<h3><span class="message-subject"><?=htmlspecialchars($tree[2][$_GET['fname']]['subject'])?></span> <?=$lang->getEntry('mailinglist', 'by')?> <span class="message-sender"><?=mail_make_links(htmlspecialchars($tree[2][$_GET['fname']]['sender']))?></span></h3>
<div id="message-headers">
	<dl>
<?php
		foreach($message['headers'] as $k=>$v)
		{
			$k = preg_replace("/(^|-)([a-z])/e", "'$1'.strtoupper('$2');", $k);
?>
		<dt><?=htmlspecialchars($k)?></dt>
		<dd><?=str_replace(array("\t", "   "), "<br />", mail_make_links(htmlspecialchars($v)))?></dd>
<?php
		}
?>
	</dl>
</div>
<script type="text/javascript">
	var hide_caption = '<?=$lang->getEntry('mailinglist', 'hide-headers')?>';
	var show_caption = '<?=$lang->getEntry('mailinglist', 'show-headers')?>';

	var headers_container = document.getElementById('message-headers');
	var headers_list = headers_container.getElementsByTagName('dl')[0];

	var toggle_div = document.createElement('div');
	toggle_div.appendChild(toggle_link = document.createElement('a'));
	toggle_link.href = 'javascript:toggleHeadersVisibility();';
	toggle_link.appendChild(document.createTextNode(show_caption));
	headers_container.insertBefore(toggle_div, headers_list);

	headers_list.style.display = 'none';
	var headers_visible = false;

	function toggleHeadersVisibility()
	{
		if(headers_visible)
		{
			headers_list.style.display = 'none';
			toggle_link.firstChild.data = show_caption;
			headers_visible = false;
		}
		else
		{
			headers_list.style.display = 'block';
			toggle_link.firstChild.data = hide_caption;
			headers_visible = true;
		}
	}
</script>
<hr />
<?php
		$i = 1;
		foreach($message['content'] as $k=>$cinfo)
		{
			if(!isset($cinfo['headers']['content-type']))
				$mimetype = 'text/plain';
			else
				list($mimetype) = explode(';', $cinfo['headers']['content-type']);
			$mimetype = trim($mimetype);
			$filename = 'attachment_'.$i;

			if(isset($cinfo['headers']['content-disposition']))
			{
				$dp = explode(';', $cinfo['headers']['content-disposition']);
				$dp[0] = trim($dp[0]);
				if(isset($dp[1]))
				{
					$dp[1] = trim($dp[1]);
					if(preg_match("/^filename=(.*)$/i", $dp[1], $match))
						$filename = $match[1];
				}
				$inline = ($dp[0] == 'inline');
			}
?>
<div class="mail-part <?=($mimetype=='text/plain') ? 'text' : 'attachment'?>">
	<h4><?=$lang->getEntry('mailinglist', 'mail-part')?>&nbsp;<?=htmlspecialchars($i)?>: <?=htmlspecialchars($mimetype)?></h3>
<?php
			if($mimetype == 'text/plain')
			{
				# Show content
?>
<pre>
<?php
				echo $cinfo['content']."\n";
?>
</pre>
<?php
			}
			else
			{
?>
	<p><a href="mailinglist.php?fname=<?=htmlspecialchars(urlencode($_GET['fname']))?>&amp;mimepart=<?=htmlspecialchars(urlencode($k))?>">
<?php
				if($inline && in_array($mimetype, array("image/png", "image/gif", "image/jpeg")))
				{
?>
		<img src="mailinglist.php?fname=<?=htmlspecialchars(urlencode($_GET['fname']))?>&amp;mimepart=<?=htmlspecialchars(urlencode($k))?>" alt="<?=$lang->getEntry('mailinglist', 'mail-part')?>&nbsp;<?=htmlspecialchars($i)?>" />
<?php
				}
?>
		<?=htmlspecialchars($filename)?></a></p>
<?php
			}
?>
</div>
<?php
			$i++;
		}

		$top = $tree[2][$_GET['fname']];
		while(isset($top['parent']) && isset($tree[1][$top['parent']]))
			$top = &$tree[1][$top['parent']];
?>
<h3><?=$lang->getEntry('mailinglist', 'thread')?></h3>
<ol class="mails">
	<li<?=($top['fname'] == $_GET['fname']) ? ' class="active"' : ''?>><a href="mailinglist.php?fname=<?=htmlspecialchars(urlencode($top['fname']))?>" class="subject"><?=htmlspecialchars($top['subject'])?></a> <?=$lang->getEntry('mailinglist', 'by')?> <span class="mail-sender"><?=mail_make_links(htmlspecialchars($top['sender']))?></span>, <span class="mail-time"><?=htmlspecialchars(date('Y-m-d, H:i:s', $top['time']))?></span>
<?php
		if(count($top['sub']) > 0)
			print_mails($top['sub'], "\t\t", $_GET['fname']);
?>
	</li>
</ol>
<?php
		$gui->htmlFoot();
		exit(0);
	}
	else
	{
		# Show message list
		$gui->htmlHead();
?>
<p class="mailinglist-intro"><?=$lang->getEntry('mailinglist', 'intro', 'sua-dev+subscribe@s-u-a.net', 'sua-dev+help@s-u-a.net')?></p>
<?php
		print_mails($tree[0]['sub']);
		$gui->htmlFoot();
		exit(0);
	}
?>
