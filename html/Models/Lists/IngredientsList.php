<?php

namespace Models\Lists;

use Models\Objects\Ingredient;

class IngredientsList extends Base
{
	public string $elementsClass = "\\Models\\Objects\\Ingredient";
	public string $table = "ingredients";
	
	public function setUnitForView()
	{
		/** @var Ingredient $object */
		foreach ($this->objects as $object) {
			$object->setUnitForView();
		}
	}
}