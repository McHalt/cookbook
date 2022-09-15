<?php

namespace App\Pages;

use Models\Objects\MenuItem;

class Recipe extends Base
{
	public function prepareData()
	{
		if (empty($_GET['id'])) {
			header('Location: ?p=404');
		}
		$this->data->recipe = new \Models\Objects\Recipe(['id' => $_GET['id']]);
		if (empty($this->data->recipe->name)) {
			header('Location: ?p=404');
		}
		$menuItemEdit = new MenuItem(['id' => -1]);
		$menuItemEdit->name = "Edytuj przepis";
		$menuItemEdit->url = '?p=EditRecipe&id=' . $this->data->recipe->id;
		$this->data->menuItems->objects = array_merge([$menuItemEdit], $this->data->menuItems->objects);
	}
}