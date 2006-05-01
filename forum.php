<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'forum');

	# Set environment to simulate CGI
	$env = array();
	foreach($_SERVER as $key=>$val)
		$env[] = $key.'='.escapeshellarg($val);
	$env = implode(' ', $env);

	$call = 'fo_view';
	if(isset($_SERVER['SUADEV_FORUM_CALL']))
	{
		switch($_SERVER['SUADEV_FORUM_CALL'])
		{
			case 'fo_arcview': $call = 'fo_arcview'; break;
			case 'fo_post': $call = 'fo_post'; break;
			case 'fo_userconf': $call = 'fo_userconf'; break;
			case 'fo_usermanagement': $call = 'fo_usermanagement'; break;
			case 'fo_vote': $call = 'fo_vote'; break;
		}
	}

	if(!is_dir('forum_links') || !is_readable('forum_links') || !is_file('forum_links/'.$call) || !is_readable('forum_links/'.$call) || !is_executable('forum_links/'.$call))
		$error = true;
	else
	{
		$selected_lang = $lang->getSelectedLanguage();
		if(is_file('forum_links/.conf.'.$selected_lang) && is_readable('forum_links/.conf.'.$selected_language) && count($conf_file = preg_split('/(\r)|(\n)|(\r\n)/', file_get_contents('forum_links/.conf.'.$selected_language))))
			$env = 'CF_FORUM_NAME='.escapeshellarg($conf_file[0]).' CF_CONF_DIR='.escapeshellarg($conf_file[1]).' '.$env;
		$command = $env.' forum_links/'.$call;
		exec($command, $output, $return);
		$error = ($return != 0);
	}

	if($error)
	{
		$gui->htmlHead();
?>
<p class="error"><?=$lang->getEntry('forum', 'error')?></p>
<?php
		$gui->htmlFoot();
		exit(0);
	}
	else
	{
		$headers_sent = false;
		$should_send_headers = false;
		foreach($output as $line)
		{
			$line = str_replace(array("\r", "\n"), "", $line);

			if($line == "" && !$headers_sent)
				$should_send_headers = true;
			else
			{
				if($should_send_headers)
				{
					$gui->htmlHead();
					$should_send_headers = false;
					$headers_sent = true;
				}

				if($headers_sent) print($line."\n");
				else header($line);
			}
		}

		if($headers_sent) $gui->htmlFoot();
		exit(0);
	}
?>