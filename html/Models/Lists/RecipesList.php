<?php

namespace Models\Lists;

class RecipesList extends Base
{
	public string $elementsClass = "\\Models\\Objects\\Recipe";
	public string $table = "recipes";
}