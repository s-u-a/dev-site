<?php
	include('include.php');

	$gui->title = $lang->getEntry('navigation', 'news');
	$gui->htmlHead();
	
	$language = $lang->getSelectedLanguage();
	
	$news = array();
	if(is_dir('newsdata') && is_readable('newsdata') && is_dir('newsdata/'.$language) && is_readable('newsdata/'.$language))
	{
		$dh = opendir('newsdata/'.$language);
		while(($file = readdir($dh)) !== false)
		{
			if(!is_file('newsdata/'.$language.'/'.$file) || !is_readable('newsdata/'.$language.'/'.$file) || 'newsdata/'.$language.'/'.$file[0] == '.' || 'newsdata/'.$language.'/'.$file[0] == '#') continue;
			$news[$file] = filemtime('newsdata/'.$language.'/'.$file);
		}
		closedir($dh);
	}
	arsort($news, SORT_NUMERIC);
	
	if($news)
	{
		foreach($news as $filename=>$filemtime)
		{
			$one_news = parseNewsXML('newsdata/'.$language.'/'.$filename);
			$one_news['content'] = str_replace("\n", "\n\t", $one_news['content']);
?>
<div class="news">
	<h3><?=$one_news['title']?></h3>
	<?=$one_news['content']?>
	<cite><?=$one_news['author'].', '.date('Y-m-d, H:i:s', $filemtime)?></cite>
</div>
<?php
		}
	}
	else
	{
?>
<p class="error"><?=$lang->getEntry('news', 'no-news')?></p>
<?php
	}
	$gui->htmlFoot();
	exit(0);
?>