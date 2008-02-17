<?php
	class Language
	{
		private $languageData = false;
		private $languageFiles = false;
		private $selectedLanguage = false;

		function __construct()
		{
			$recreate = false;
			if(!is_dir('cache') || !is_readable('cache') || !file_exists('cache/lang')) $recreate = true;
			else
			{
				if(!is_readable('cache/lang'))
				{
					notice("/cache/lang not readable.");
					return false;
				}

				$this->languageData = unserialize(file_get_contents('cache/lang'));

				if(!isset($this->languageData['files']) || !isset($this->languageData['languages']))
					$recreate = true;
				else
				{
					$this->getLanguageFiles();
					$language_files = array_flip($this->languageFiles);
					foreach($this->languageData['files'] as $fname=>$info)
					{
						if(!isset($language_files[$fname]) || filemtime('lang/'.$fname) != $info[0] || filesize('lang/'.$fname) != $info[1])
						{
							$recreate = true;
							break;
						}
						unset($language_files[$fname]);
					}
					if(!$recreate && count($language_files) > 0) $recreate = true;
				}
			}

			if($recreate) $this->recreate();
		}

		function selectLanguage($language=false)
		{
			if(!$this->languageData)
			{
				$this->selectedLanguage = false;
				return false;
			}

			if($language === false)
			{ # Automatically figure out language by using HTTP_ACCEPT_LANGUAGE
				if(isset($_ENV['REDIRECT_REDIRECT_SUADEV_LANGUAGE']) && isset($this->languageData['languages'][$_ENV['REDIRECT_REDIRECT_SUADEV_LANGUAGE']]))
					$language = $_ENV['REDIRECT_REDIRECT_SUADEV_LANGUAGE'];
				elseif(isset($_SERVER['REDIRECT_SUADEV_LANGUAGE']) && isset($this->languageData['languages'][$_SERVER['REDIRECT_SUADEV_LANGUAGE']]))
					$language = $_SERVER['REDIRECT_SUADEV_LANGUAGE'];
				else
				{
					$desired_languages = array();
					if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $desired_languages = preg_split("/[^a-zA-Z]+/", $_SERVER['HTTP_ACCEPT_LANGUAGE']);

					foreach($desired_languages as $dlang)
					{
						if(isset($this->languageData['languages'][$dlang]))
						{
							$language = $dlang;
							break;
						}
					}

					if($language === false)
						$language = 'en';
				}
			}

			if(!isset($this->languageData['languages'][$language]))
			{
				$this->selectedLanguage = false;
				return false;
			}

			$this->selectedLanguage = $language;
			return true;
		}

		function getSelectedLanguage()
		{
			return $this->selectedLanguage;
		}

		function getEntry($one, $two)
		{
			if(!$this->languageData) return false;

			$args = func_get_args();
			$category = array_shift($args);
			$entry = array_shift($args);

			if(!isset($this->languageData['languages'][$this->getSelectedLanguage()][$category]) || !isset($this->languageData['languages'][$this->getSelectedLanguage()][$category][$entry]))
				return '{'.$one.'/'.$two.'}';

			return vsprintf($this->languageData['languages'][$this->getSelectedLanguage()][$category][$entry], $args);
		}

		function getLanguageFiles()
		{
			if($this->languageFiles !== false) return true;

			$this->languageFiles = array();

			if(!is_dir('lang')) return true;
			if(!is_readable('lang'))
			{
				notice("/lang/ is not readable.");
				return false;
			}

			$dh = opendir('lang');
			while(($fname = readdir($dh)) !== false)
			{
				if($fname[0] == '.' || $fname[0] == '#') continue;
				if(!is_readable('lang/'.$fname)) continue;
				$this->languageFiles[] = $fname;
			}
			closedir($dh);
			return true;
		}

		function getLanguageList()
		{
			if(!$this->languageData) return false;

			return array_keys($this->languageData['languages']);
		}

		private function recreate()
		{
			$this->getLanguageFiles();
			if(!$this->languageFiles) return false;

			$this->languageData = array('files' => array(), 'languages' => array());

			foreach($this->languageFiles as $file)
			{
				$this->languageData['files'][$file] = array(filemtime('lang/'.$file), filesize('lang/'.$file));
				$this_xml = simplexml_load_file('lang/'.$file);
				if(!$this_xml) continue;

				$attr = $this_xml->attributes();
				if(!isset($attr['language'])) continue;
				$lang = (string) $attr['language'];
				if(!isset($this->languageData['languages'][$lang]))
					$this->languageData['languages'][$lang] = array();

				foreach($this_xml->category as $category)
				{
					$cname = (string) $category['name'];
					if(!isset($this->languageData['languages'][$lang][$cname]))
						$this->languageData['languages'][$lang][$cname] = array();
					foreach($category->entry as $entry)
					{
						$ename = (string) $entry['name'];
						$this->languageData['languages'][$lang][$cname][$ename] = (string) $entry;
					}
				}
			}

			if(!file_exists('cache') && !mkdir('cache', 0770))
			{
				notice('Could not create /cache/');
				return false;
			}
			if((file_exists('cache') && !is_dir('cache')) || (file_exists('cache/lang') && !is_file('cache/lang')) || (!is_file('cache/lang') && !is_writable('cache')) || (is_file('cache/lang') && !is_writable('cache/lang')))
			{
				notice('Couldn\'t write to cache/lang.');
				return false;
			}

			$fh = fopen('cache/lang', 'w');
			flock($fh, LOCK_EX);
			fwrite($fh, serialize($this->languageData));
			flock($fh, LOCK_UN);
			fclose($fh);

			return true;
		}
	}

	$lang = new Language();
	$lang->selectLanguage();

	define('h_root', real_h_root.'/'.$lang->getSelectedLanguage());
?>
