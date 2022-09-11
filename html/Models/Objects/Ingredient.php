<?php

namespace Models\Objects;

class Ingredient extends Base
{
	public string $table = "ingredients";
	public int $id;
	public string $name;
	public int $amount;
	public string $unit;
	public array $baseUnits = ['ml', 'g', 'szt'];
	private array $units2MultiplierNBaseUnit = [
		'L' => [1000, 'ml'],
		'kg' => [1000, 'g'],
		'dag' => [10, 'g']
	];
	
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
}