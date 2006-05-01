<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'news');
	$gui->htmlHead();

	$language = $lang->getSelectedLanguage();

	$news = array();
	if(is_dir('newsdata') && is_readable('newsdata') && is_dir('newsdata/'.$language) && is_readable('newsdata/'.$language))
	{
		$dh = opendir('newsdata/'.$language);
		while(($fname = readdir($dh)) !== false)
		{
			if($fname[0] == '.' || $fname[0] == '#') continue;
			if(!is_file('newsdata/'.$language.'/'.$fname) || !is_readable('newsdata/'.$language.'/'.$fname)) continue;
			$news[$fname] = filemtime('newsdata/'.$language.'/'.$fname);
		}
		closedir($dh);
	}
	arsort($news, SORT_NUMERIC);

	if(count($news) <= 0)
	{
?>
<p class="error"><?=$lang->getEntry('news', 'no-news')?></p>
<?php
	}
	else
	{
		foreach($news as $filename=>$filemtime)
		{
			$one_news = parseNewsXML('newsdata/'.$language.'/'.$filename);
			if(!$one_news) continue;
?>
<div class="news">
	<h3><?=(isset($one_news['title']) && strlen($one_news['title']) > 0) ? $one_news['title'] : $lang->getEntry('news', 'no-title')?></h3>
	<?=$one_news['content']."\n"?>
	<cite><?=(isset($one_news['author']) && strlen($one_news['author']) > 0) ? $one_news['author'].', ' : ''?><?=date('Y-m-d, H:i:s', $filemtime)?></cite>
</div>
<?php
		}
	}

	$gui->htmlFoot();
	exit(0);
?>