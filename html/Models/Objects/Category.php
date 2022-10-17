<?php

namespace Models\Objects;

use Models\Lists\RecipesList;

class Category extends Base
{
	public string $table = "categories";  
	public int $id;
	public string $name;
	public int $parentId;
	public RecipesList $recipes;
	public array $properties2Save = ['name', 'parent_id'];
	
	public function loadRecipes()
	{
		$additionalSql = "ORDER BY r.name";
		
		if (!empty($this->id)) {
			$additionalSql = "r
				 INNER JOIN recipes_to_categories rtc ON r.id = rtc.recipe_id
				 WHERE category_id IN (
				 	SELECT id
				 	FROM categories
				 	WHERE id = " . $this->id . " OR parent_id = " . $this->id . " 
				 )
				 $additionalSql
			 ";
		} else {
			$additionalSql = "r";
		}
		
		$this->recipes = new RecipesList(['additionalSql' => $additionalSql]);
	}
	
	public function save(array $additionalData = [])
	{
		$this->parent_id = $this->parentId;
		parent::save($additionalData);
	}
}