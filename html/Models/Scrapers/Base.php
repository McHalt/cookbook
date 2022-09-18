<?php

namespace Models\Scrapers;

use Models\Db;
use Models\Objects\Ingredient;
use Models\Objects\Recipe;

abstract class Base extends \Models\Base
{
	public \DOMDocument $document;
	
	public function __construct(array $inputs = [])
	{
		if (empty($inputs['url'])) {
			trigger_error('Empty $inputs[url]', E_USER_WARNING);
			return;
		}
		$this->document = new \DOMDocument();
		$html = $this->prepareHTML(file_get_contents($inputs['url']));
		@$this->document->loadHTML($html);
		$this->save();
	}
	
	public function prepareHTML($html)
	{
		return $html;
	}
	
	abstract function getIngredients();
	
	abstract function getName();
	
	abstract function getContent();
	
	abstract function getImage();
	
	public function save()
	{
		$recipe = new Recipe();
		$recipe->name = $this->getName();
		$recipe->content = $this->getContent();
		$recipe->save();
		$recipe->saveImgFromUrl($this->getImage());
		foreach ($this->getIngredients() as $ingredient) {
			$ingredientObj = new Ingredient([
				'loadVia' => 'name',
				'name' => $ingredient['name']
			]);
			if (empty($ingredientObj->id)) {
				$variant = Db::query("SELECT ingredient_name FROM ingredients_variants WHERE variant = '" . $ingredient['name'] . "'");
				if (!empty($variant)) {
					$ingredientObj = new Ingredient([
						'loadVia' => 'name',
						'name' => $variant[0]['ingredient_name']
					]);
				}
			}
			$ingredientObj->unit = $ingredient['unit'];
			$ingredientObj->amount = $ingredient['amount'];
			if (empty($ingredientObj->id)) {
				$ingredientObj->save();
			}
			$ingredientObj->save2Recipe($recipe->id);
		}
		header('Location: ?p=Recipe&id=' . $recipe->id);
	}
}