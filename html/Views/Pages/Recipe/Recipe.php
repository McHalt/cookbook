<?php

namespace Views\Pages\Recipe;

use Models\Data;

class Recipe extends \Views\Pages\Base
{
	public string $title = "Cookbook :: ";
	
	public function render(Data $data)
	{
		$this->title .= $data->recipe->name;
		parent::render($data);
	}
}