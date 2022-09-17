<?php

namespace App\Pages;

use Models\Lists\RecipesList;

class Szukaj extends Base
{
	
	public function prepareData()
	{
		$searchStr = htmlspecialchars($_GET['searchString']);
		$this->data->recipes = new RecipesList([
			'additionalSql' => "WHERE name LIKE '%$searchStr%'"
		]);
	}
}