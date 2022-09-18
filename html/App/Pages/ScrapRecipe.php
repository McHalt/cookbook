<?php

namespace App\Pages;

use Models\Scrapers\KwestiaSmaku;

class ScrapRecipe extends Base
{
	public array $url2Scraper = [
		'kwestiasmaku.com' => KwestiaSmaku::class
	];
	
	public function prepareData()
	{
		if (empty($_POST['url'])) {
			return;
		}
		
		foreach ($this->url2Scraper as $url => $scraper) {
			if (str_contains($_POST['url'], $url)) {
				new $scraper(['url' => $_POST['url']]);
				return;
			}
		}
		
		$this->data->error = 'Niestety, ta strona nie jest obsługiwana : (';
	}
}