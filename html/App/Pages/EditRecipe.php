<?php

namespace App\Pages;

use Models\Lists\IngredientsList;
use Models\Objects\Recipe;
use Models\Objects\Ingredient;

class EditRecipe extends Base
{
	
	public function save()
	{
		if (empty($_GET['id'])) {
			$recipe = new Recipe();
		} else {
			$recipe = new Recipe(['id' => $_GET['id']]);
		}
		$recipe->name = $_POST['name'] ?? '';
		$recipe->content = $_POST['content'] ?? '';
		$recipe->save();
		if (!empty($_FILES['image']['name'])) {
			$recipe->saveImg($_FILES['image']);
		}
		foreach ($this->getMergedIngredients($_POST['ingredients']) as $name => $ingredient) {
			$ingredientObj = new Ingredient([
				'loadVia' => 'name',
				'name' => $name
			]);
			$ingredientObj->unit = $ingredient['unit'];
			$ingredientObj->amount = $ingredient['amount'];
			if (empty($ingredientObj->id)) {
				$ingredientObj->save();
			}
			$ingredientObj->save2Recipe($recipe->id);
		}
	}
	
	public function getMergedIngredients(array $ingredients)
	{
		$finalIngredients = [];
		foreach ($ingredients as $ingredient) {
			$index = strtolower($ingredient['name']);
			if (isset($finalIngredients[$index])) {
				if ($finalIngredients[$index]['unit'] = strtolower($ingredient['unit'])) {
					$finalIngredients[$index]['amount'] += (float) $ingredient['amount'];
					continue;
				}
			}
			$finalIngredients[$index] = [
				'unit' => strtolower($ingredient['unit']),
				'amount' => (float) $ingredient['amount']
			];
		}
		return $finalIngredients;
	}
	
	public function prepareData()
	{
		if ($_POST['save'] ?? false) {
			$this->save();
		}
		if (!empty($_GET['id'])) {
			$this->data->recipe = new Recipe(['id' => $_GET['id']]);
		}
		$this->data->possiblyIngredients = new IngredientsList();
	}
}