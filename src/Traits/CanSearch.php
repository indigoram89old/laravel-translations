<?php

namespace Indigoram89\Laravel\Translations\Traits;

trait CanSearch
{
	public function search() : array
	{
		$locale = $this->getLocale();
		
		$translations = [];

		foreach ($this->getConfig('search') as $path) {
			$translations = array_merge(
				$translations,
				$this->searchTexts($path, $locale)
			);
		}

        return $translations;
	}

	protected function searchTexts(string $path, string $locale) : array
	{
		$texts = [];

		foreach ($this->getFiles($path) as $file) {
        	$content = $this->getFile($file);

        	foreach ($this->searchKeys($content) as $key) {
        		$texts[$key] = [$locale => $this->compareValue($key, __($key))];
        	}
        }

        return $texts;
	}

	protected function searchKeys(string $content) : array
	{
		$keys = [];

		$patterns = [
			"/__\('(.+?)'[,)]/",
			'/__\("(.+?)"[,)]/',
		];

		foreach ($patterns as $pattern) {
			if (preg_match_all($pattern, $content, $matches)) {
	            foreach ($matches[0] as $match) {
	            	$keys[] = preg_replace($pattern, '$1', $match);
	            }
	        }
		}

        return $keys;
	}

	protected function compareValue(string $key, string $value)
	{
		if ($value === $key) return null;

		return $value;
	}
}