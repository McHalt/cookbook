<?php

namespace Models\Objects;

use Models\Lists\IngredientsList;

class Recipe extends Base
{
	public int $id;
	public string $name;
	public ?string $content;
	public string $table = "recipes";
	public IngredientsList $ingredients;
	
	public function __construct(array $inputs = [])
	{
		parent::__construct($inputs);
		$this->ingredients = new IngredientsList(['additionalSql' => "
			i INNER JOIN recipes_to_ingredients rti ON i.id = rti.ingredient_id AND rti.recipe_id = $this->id 
		"]);
	}
	
	public function getLoadQry(){
		return "
			SELECT * 
			FROM $this->table r 
			LEFT JOIN recipes_contents rc ON r.id = rc.recipe_id
			WHERE $this->loadVia = '" . $this->{$this->loadVia} . "'";
	}
}