<?php

namespace Models\Objects;

use Models\Db;
use Models\Lists\CategoriesList;
use Models\Lists\IngredientsList;

class Recipe extends Base
{
	public int $id;
	public string $name;
	public ?string $content;
	public string $table = "recipes";
	public IngredientsList $ingredients;
	public CategoriesList $categories;
	public array $properties2Save = ['id', 'name'];
	
	public function __construct(array $inputs = [])
	{
		parent::__construct($inputs);
		$this->ingredients = new IngredientsList(['additionalSql' => "
			i INNER JOIN recipes_to_ingredients rti ON i.id = rti.ingredient_id AND rti.recipe_id = " . ($this->id ?? -1)
		]);
		$this->ingredients->setUnitForView();
		if (!empty($this->id)) {
			$this->categories = new CategoriesList(['load4Recipe' => $this->id]);
		}
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
				VALUES (" . $this->id . ", '" . htmlspecialchars($this->content) . "')
				ON DUPLICATE KEY UPDATE content = '" . htmlspecialchars($this->content) . "'
				";
		Db::exec($qry);
	}
	
	public function saveImgFromUrl($url)
	{
		$imageFileType = strtolower(pathinfo($url,PATHINFO_EXTENSION));
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT'] . '/Files/Imgs/recipes/' . $this->id . '.' . $imageFileType,
			file_get_contents($url)
		);
	}
	
	public function saveImg($postFile)
	{
		$imageFileType = strtolower(pathinfo($postFile['name'],PATHINFO_EXTENSION));
		rename(
			$postFile['tmp_name'], 
			$_SERVER['DOCUMENT_ROOT'] . '/Files/Imgs/recipes/' . $this->id . '.' . $imageFileType
		);
	}
	
	public function saveCategories($categories)
	{
		Db::exec("DELETE FROM recipes_to_categories WHERE recipe_id = " . $this->id);
		$qry = "INSERT INTO recipes_to_categories (recipe_id, category_id) VALUES ";
		$values = [];
		foreach ($categories as $category) {
			if (isset($category[1]) && $category[1] > 0) {
				$catId = $category[1];
			} elseif (isset($category[0]) && $category[0] > 0) {
				$catId = $category[0];
			} else {
				continue;
			}
			$values[] = "(" . $this->id . ", $catId)";
		} 
		$qry .= implode(',', $values);
		Db::exec($qry);
	}
}