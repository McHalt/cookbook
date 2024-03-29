<?php

namespace Views\Pages\EditRecipe;

use Models\Data;

class EditRecipe extends \Views\Pages\Base
{
	public string $title = "Cookbook :: ";
	
	public function render(Data $data)
	{
		if (!empty($data->recipe)) {
			$this->title .= 'Edytuj przepis';
		} else {
			$this->title .= "Dodaj przepis";
		}
		parent::render($data);
	}
}