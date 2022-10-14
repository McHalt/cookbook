<?php

namespace Models\Objects;

use Models\Db;

class Ingredient extends Base
{
	public string $table = "ingredients";
	public int $id;
	public string $name;
	public float $amount;
	public string $unit;
	public array $baseUnits = [];
	public array $units2MultiplierNBaseUnit = [];
	public array $properties2Save = ['id', 'name'];
	public string $unitForView;
	
	public function __construct(array $inputs = [])
	{
		parent::__construct($inputs);
		$qry = "
			SELECT *
			FROM ingredients_base_units
		";
		foreach (Db::query($qry) as $row) {
			$this->baseUnits[$row['id']] = $row['name'];
		}
		
		$qry = "
			SELECT *
			FROM ingredients_units_2_base_units
		";
		foreach (Db::query($qry) as $row) {
			$this->units2MultiplierNBaseUnit[$row['name']] = [
				$row['multiplier'], 
				$this->baseUnits[$row['base_unit_id']]
			];
		}
	}
	
	public function save(array $additionalData = [])
	{
		if (!in_array($this->unit, $this->baseUnits)) {
			if (!in_array($this->unit, array_keys($this->units2MultiplierNBaseUnit))) {
				return; 
			}
			$this->amount = $this->amount * $this->units2MultiplierNBaseUnit[$this->unit][0];
			$this->unit = $this->units2MultiplierNBaseUnit[$this->unit][1];
		}
		parent::save($additionalData);
	}
	
	public function save2Recipe($recipeId)
	{
		$orgTable = $this->table;
		$orgProperties2Save = $this->properties2Save;
		$this->table = 'recipes_to_ingredients';
		$this->properties2Save = ['ingredient_id', 'unit', 'amount', 'recipe_id'];
		$this->recipe_id = $recipeId;
		$this->ingredient_id = $this->id;
		$this->save(['forceInsert' => true, 'onDuplicateUpdate' => true]);
		$this->table = $orgTable;
		$this->properties2Save = $orgProperties2Save;
	}
	
	public function setUnitForView()
	{
		if (!in_array($this->unit, ['łyżka', 'łyżeczka', 'szklanka']) || $this->amount == 1) {
			$this->unitForView = $this->unit;
			return;
		}
		
		if ($this->amount < 5 || $this->amount != intval($this->amount)) {
			$this->unitForView = str_replace('ka', 'ki', $this->unit);
			return;
		}
		
		$this->unitForView = str_replace('ka', 'ek', $this->unit);
	}
}