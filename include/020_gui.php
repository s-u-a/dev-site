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
		<title><?=(strlen(trim($this->title)) > 0) ? htmlspecialchars(strip_tags(trim($this->title))).' – ' : ''?><?=strip_tags($lang->getEntry('general', 'title'))?></title>
		<style type="text/css">
			@import url(<?=h_root?>/style.css);
		</style>
	</head>
	<body>
		<h1><span><?=$lang->getEntry('general', 'title')?></span></h1>
		<ol id="navigation">
			<li<?=_GUI_checkActive('index')?' class="active"':''?>><a href="<?=h_root?>/"><?=$lang->getEntry('navigation', 'index')?></a></li>
			<li<?=_GUI_checkActive('news')?' class="active"':''?>><a href="<?=h_root?>/news"><?=$lang->getEntry('navigation', 'news')?></a></li>
			<li<?=_GUI_checkActive('credits')?' class="active"':''?>><a href="<?=h_root?>/credits"><?=$lang->getEntry('navigation', 'credits')?></a></li>
			<li<?=_GUI_checkActive('skins')?' class="active"':''?>><a href="<?=h_root?>/skins"><?=$lang->getEntry('navigation', 'skins')?></a></li>
			<li<?=_GUI_checkActive('download')?' class="active"':''?>><a href="<?=h_root?>/download"><?=$lang->getEntry('navigation', 'download')?></a></li>
			<li<?=_GUI_checkActive('forum')?' class="active"':''?>><a href="<?=h_root?>/forum"><?=$lang->getEntry('navigation', 'forum')?></a></li>
			<li<?=_GUI_checkActive('bugs')?' class="active"':''?>><a href="<?=h_root?>/bugs"><?=$lang->getEntry('navigation', 'bugs')?></a></li>
			<li<?=_GUI_checkActive('websvn')?' class="active"':''?>><a href="<?=h_root?>/websvn"><?=$lang->getEntry('navigation', 'websvn')?></a></li>
			<li<?=_GUI_checkActive('faq')?' class="active"':''?>><a href="<?=h_root?>/faq"><?=$lang->getEntry('navigation', 'faq')?></a></li>
		</ol>
		<div id="content">
<?php
			if(strlen(trim($this->title)) > 0)
			{
?>
		<h2><?=trim($this->title)?></h2>
<?php
			}
?>
<?php
			ob_start();
			return true;
		}

		function htmlFoot()
		{
			global $lang;

			$content = ob_get_contents();
			ob_end_clean();

			print "\t\t\t".str_replace("\n", "\n\t\t\t", $content)."\n";
?>
		</div>
	</body>
</html>
<?php
			return true;
		}
	}

	function _GUI_checkActive($cat)
	{
		return ($_SERVER['PHP_SELF'] == h_root.'/'.$cat.'.php');
	}

	$gui = new GUI;
?>