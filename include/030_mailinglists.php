<?php
	define('mailing_list_spool_directory', '/var/spool/mlmmj/sua-dev/archive/');

	function mail_update_database()
	{
		$mail_db = s_root.'/cache/mail.sqlite';
		$db = sqlite_popen($mail_db);
		if(!$db) return false;

		if(sqlite_num_rows(sqlite_query($db, "SELECT name FROM sqlite_master WHERE type='table' AND name='mails';")) <= 0 && !sqlite_query($db, "CREATE TABLE mails ( fname PRIMARY KEY, message_id, parent, sender, subject, time INT, content, headers );"))
			return false;

		$used_fnames_query = sqlite_query($db, "SELECT fname FROM mails;");
		$used_fnames = array();
		while($r = sqlite_fetch_array($used_fnames_query, SQLITE_ASSOC))
			$used_fnames[] = $r['fname'];

		$dname = mailing_list_spool_directory;
		$dh = opendir($dname);
		if(!$dh) return false;
		$fnames = array();
		while($fname = readdir($dh))
		{
			if(!is_file($dname.$fname) || !is_readable($dname.$fname)) continue;
			if(in_array($fname, $used_fnames)) continue;
			$fnames[] = $fname;
		}
		closedir($dh);

		if(count($fnames) <= 0) return true;

		natcasesort($fnames);

		global $i,$content;

		foreach($fnames as $fname)
		{
			$path = $dname.$fname;
			$file = file_get_contents($path);
			$mailh = mailparse_msg_parse_file($path);
			$structure = mail_filter_structure(mailparse_msg_get_structure($mailh));
			$mail_info = mailparse_msg_get_part_data($mailh);

			$headers = $mail_info['headers'];
			$charset = false;
			if(isset($headers['content-type']) && (preg_match('/;\s*charset="(.*?)"\s*$/', $headers['content-type'], $match) || preg_match('/;\s*charset=(.*?)\s*$/', $headers['content-type'], $match)))
				$charset = $match[1];
			foreach($headers as $k=>$v)
				$headers[$k] = mail_strip_encoded($v);
			$from = (isset($headers['from']) ? $headers['from'] : false);
			$subject = ((isset($headers['subject']) && trim($headers['subject'])) ? $headers['subject'] : "[No subject]");
			$message_id = (isset($headers['message-id']) ? $headers['message-id'] : false);
			$parent = (isset($headers['in-reply-to']) ? $headers['in-reply-to'] : false);
			if(!isset($headers['date']) || !($time = strtotime($headers['date'])))
				$time = filemtime($dname.$fname);
			$content = array();

			$parts = array();
			foreach($structure as $i=>$s)
			{
				$content[$i] = array();
				$part = mailparse_msg_get_part($mailh, $s);
				$part_info = mailparse_msg_get_part_data($part);
				$content[$i]['headers'] = $part_info['headers'];
				foreach($content[$i]['headers'] as $k=>$v)
					$content[$i]['headers'][$k] = mail_strip_encoded($v);
				mailparse_msg_extract_part($part, $file, "mail_part_callback");
				if((isset($content[$i]['headers']['content-type']) && (preg_match('/;\s*charset="(.*?)"\s*$/', $content[$i]['headers']['content-type'], $match) || preg_match('/;\s*charset=(.*?)\s*$/', $content[$i]['headers']['content-type'], $match))) || ($charset && $match = array(null, $charset)))
				{
					if(!in_array(strtolower($match[1]), array('utf-8', 'utf8')))
						$content[$i]['content'] = mb_convert_encoding($content[$i]['content'], 'UTF-8', $match[1]);
				}
			}

			mailparse_msg_free($mailh);

			$query = sprintf("INSERT INTO mails ( fname, message_id, parent, sender, subject, time, content, headers ) VALUES ( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );", sqlite_escape_string($fname), sqlite_escape_string($message_id), sqlite_escape_string($parent), sqlite_escape_string($from), sqlite_escape_string($subject), sqlite_escape_string($time), sqlite_escape_string(serialize($content)), sqlite_escape_string(serialize($headers)));
			sqlite_query($db, $query);
		}
		unset($GLOBALS['content']);
		unset($GLOBALS['i']);

		mail_make_tree(true);

		return true;
	}

	function mail_filter_structure($structure)
	{
		$structure_tree = array();
		foreach($structure as $s)
		{
			$s = explode('.', $s);
			$e = &$structure_tree;
			foreach($s as $s1)
			{
				if(!isset($e[$s1])) $e[$s1] = array();
				$e = &$e[$s1];
			}
			unset($e);
		}
		$filtered_structure = mail_filter_structure_callback($structure_tree);
		return $filtered_structure;
		
	}

	function mail_filter_structure_callback(&$tree, $prefix="")
	{
		$filtered = array();
		foreach($tree as $i=>$t)
		{
			if(count($t) > 0)
				$filtered = array_merge($filtered, mail_filter_structure_callback($t, $prefix.$i."."));
			else
				$filtered[] = $prefix.$i;
		}
		return $filtered;
	}

	function mail_part_callback($part)
	{
		global $content,$i;
		$content[$i]['content'] = "".$part;
	}

	function mail_strip_encoded($string)
	{
		global $charset;

		if($charset)
			$string = mb_convert_encoding($string, 'UTF-8', $charset);
		$string = preg_replace("/=([a-f0-9]{2})/ei", "chr(hexdec('$1'));", $string);
		$string = preg_replace("/=\?([-a-z0-9]+)\?q\?(.*?)\?=/ei", "mb_convert_encoding(str_replace('_', ' ', '$2'), 'UTF-8', '$1');", $string);
		return $string;
	}

	function mail_make_tree($force_reload=false)
	{
		$cache_fname = s_root.'/cache/mail_tree.ser';
		if(!$force_reload && is_file($cache_fname) && is_readable($cache_fname))
			return unserialize(file_get_contents($cache_fname));

		$db = sqlite_popen(s_root."/cache/mail.sqlite");
		if(!$db) return false;
		$query = sqlite_query($db, "SELECT fname, message_id, parent, sender, subject, time FROM mails ORDER BY time ASC;");

		$message_ids = array();
		$fnames = array();
		$root = array('sub');

		while($mail = sqlite_fetch_array($query, SQLITE_ASSOC))
		{
			if($mail['parent'] && isset($message_ids[$mail['parent']]))
				$parent_mail = &$message_ids[$mail['parent']];
			else
				$parent_mail = &$root;
			$this_mail = &$parent_mail['sub'][$mail['fname']];
			if($mail['message_id'])
				$message_ids[$mail['message_id']] = &$this_mail;
			$fnames[$mail['fname']] = &$this_mail;

			$this_mail = $mail;
			$this_mail['sub'] = array();

			unset($this_mail);
			unset($parent_mail);
		}

		mail_reverse_tree($root);
		$tree = array(&$root, &$message_ids, &$fnames);
		if((!file_exists($cache_fname) && is_writable(dirname($cache_fname))) || (is_file($cache_fname) && is_writable($cache_fname)))
			file_put_contents($cache_fname, serialize($tree));
		return $tree;
	}

	function mail_reverse_tree(&$tree)
	{
		foreach($tree['sub'] as $k=>$v)
			mail_reverse_tree($tree['sub'][$k]);
		$tree['sub'] = array_reverse($tree['sub']);
	}

	function mail_make_links($string)
	{
		return preg_replace("/[-0-9a-z._+]+@[-0-9a-z]+(\.[-0-9a-z]+)*/i", "<a href=\"mailto:$0\">$0</a>", $string);
	}

	function mail_get_message_info($fname)
	{
		$db = sqlite_popen(s_root."/cache/mail.sqlite");
		if(!$db) return false;
		$result = sqlite_array_query($db, "SELECT headers, content FROM mails WHERE fname = '".sqlite_escape_string($_GET['fname'])."' LIMIT 1;", SQLITE_ASSOC);
		if(!$result) return false;

		$message = array_shift($result);
		$message['headers'] = unserialize($message['headers']);
		$message['content'] = unserialize($message['content']);
		return $message;
	}
?>
