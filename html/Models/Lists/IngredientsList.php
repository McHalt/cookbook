<?php

namespace Models\Lists;

class IngredientsList extends Base
{
	public string $elementsClass = "\\Models\\Objects\\Ingredient";
	public string $table = "ingredients";
	
}