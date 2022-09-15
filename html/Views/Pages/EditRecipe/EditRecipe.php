<?php

namespace Views\Pages\EditRecipe;

use Models\Data;

class EditRecipe extends \Views\Pages\Base
{
	public string $title = "Cookbook :: ";
	
	public function render(Data $data)
	{
		if (!empty($data->recipe)) {
			$this->title .= 'Edytuj produkt';
		} else {
			$this->title .= "Dodaj produkt";
		}
		parent::render($data);
	}
}