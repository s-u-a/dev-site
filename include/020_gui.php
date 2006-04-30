<?php
	class GUI
	{
		public $title = '';
		function htmlHead()
		{
			global $lang;
?>
<?='<?xml version="1.0" encoding="UTF-8"?>'."\n"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
	<head>
		<title><?=(strlen(trim($this->title)) > 0) ? htmlspecialchars(trim($this->title)).' – ' : ''?><?=$lang->getEntry('general', 'title')?></title>
		<style type="text/css">
			@import url(<?=h_root?>/style.css);
		</style>
	</head>
	<body>
		<h1><?=$lang->getEntry('general', 'title')?></h1>
		<ol id="navigation">
			<li><a href="<?=h_root?>/"><?=$lang->getEntry('navigation', 'index')?></a></li>
			<li><a href="<?=h_root?>/news"><?=$lang->getEntry('navigation', 'news')?></a></li>
			<li><a href="<?=h_root?>/credits"><?=$lang->getEntry('navigation', 'credits')?></a></li>
			<li><a href="<?=h_root?>/skins"><?=$lang->getEntry('navigation', 'skins')?></a></li>
			<li><a href="<?=h_root?>/download"><?=$lang->getEntry('navigation', 'download')?></a></li>
			<li><a href="<?=h_root?>/forum"><?=$lang->getEntry('navigation', 'forum')?></a></li>
			<li><a href="<?=h_root?>/bugs"><?=$lang->getEntry('navigation', 'bugs')?></a></li>
			<li><a href="<?=h_root?>/websvn"><?=$lang->getEntry('navigation', 'websvn')?></a></li>
			<li><a href="<?=h_root?>/faq"><?=$lang->getEntry('navigation', 'faq')?></a></li>
		</ol>
<?php
			if(strlen(trim($this->title)) > 0)
			{
?>
		<h2><?=trim($this->title)?></h2>
<?php
			}
?>
		<div id="content">
<?php
			ob_start();
			return true;
		}

		function htmlFoot()
		{
			global $lang;

			$content = ob_get_contents();
			ob_end_clean();

			print "\t\t\t".str_replace("\n", "\n\t\t\t", $content);
?>
		</div>
	</body>
</html>
<?php
			return true;
		}
	}

	$gui = new GUI;
?>