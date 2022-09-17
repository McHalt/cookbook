<?php

namespace Views\Pages\EditCategory;

use Models\Data;

class EditCategory extends \Views\Pages\Base
{
	public string $title = "Cookbook :: ";
	
	public function render(Data $data)
	{
		if (!empty($data->recipe)) {
			$this->title .= 'Edytuj kategorię';
		} else {
			$this->title .= "Dodaj kategorię";
		}
		parent::render($data);
	}
}