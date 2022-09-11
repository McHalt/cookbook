<?php

namespace App\Pages;

use Models\Db;

class RandomRecipe extends Base
{
	public function __construct()
	{
		$id = Db::query("SELECT id FROM recipes ORDER BY RAND()")[0]['id'];
		header("Location: ?p=Recipe&id=$id");
		exit;
	}
	
	public function prepareData()
	{
		
	}
}