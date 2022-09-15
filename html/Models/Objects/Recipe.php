<?php

namespace Models\Objects;

use Models\Db;
use Models\Lists\IngredientsList;

class Recipe extends Base
{
	public int $id;
	public string $name;
	public ?string $content;
	public string $table = "recipes";
	public IngredientsList $ingredients;
	public array $properties2Save = ['id', 'name'];
	
	public function __construct(array $inputs = [])
	{
		parent::__construct($inputs);
		$this->ingredients = new IngredientsList(['additionalSql' => "
			i INNER JOIN recipes_to_ingredients rti ON i.id = rti.ingredient_id AND rti.recipe_id = " . ($this->id ?? -1)
		]);
	}
	
	public function getLoadQry()
	{
		return "
			SELECT * 
			FROM $this->table r 
			LEFT JOIN recipes_contents rc ON r.id = rc.recipe_id
			WHERE $this->loadVia = '" . $this->{$this->loadVia} . "'";
	}
	
	public function save(array $additionalData = [])
	{
		parent::save($additionalData);
		$qry = "INSERT INTO recipes_contents (recipe_id, content) 
				VALUES (" . $this->id . ", '" . $this->content . "')
				ON DUPLICATE KEY UPDATE content = '" . $this->content . "'
				";
		Db::exec($qry);
	}
	
	public function saveImg($postFile)
	{
		$imageFileType = strtolower(pathinfo($postFile['name'],PATHINFO_EXTENSION));
		rename(
			$postFile['tmp_name'], 
			$_SERVER['DOCUMENT_ROOT'] . '/Files/Imgs/recipes/' . $this->id . '.' . $imageFileType
		);
	}
}