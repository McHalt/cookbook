<?php

namespace App\Pages;

use Models\Lists\RecipesList;
use Models\Objects\Recipe;

class Main extends Base
{
	
	public function prepareData()
	{
		$this->data->recipes = new RecipesList([
			'additionalSql' => "ORDER BY RAND()*10000 LIMIT 15" 
		]);
	}
}