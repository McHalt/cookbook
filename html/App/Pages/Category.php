<?php

namespace App\Pages;

use Models\Objects\MenuItem;

class Category extends Base
{
	
	public function prepareData()
	{
		if (!is_numeric($_GET['id'] ?? false)) {
			header('Location: ?p=404');
		}
		$this->data->category = new \Models\Objects\Category(['id' => $_GET['id']]);
		if ($_GET['id'] == 0) {
			$this->data->category->id = 0;
			$this->data->category->name = "Wszystko";
		}
		if (empty($this->data->category->name)) {
			header('Location: ?p=404');
		}
		$this->data->category->loadRecipes();
		$menuItemEdit = new MenuItem(['id' => -1]);
		$menuItemEdit->name = "Edytuj kategorię";
		$menuItemEdit->url = '?p=EditCategory&id=' . $this->data->category->id;
		$this->data->menuItems->objects = array_merge([$menuItemEdit], $this->data->menuItems->objects);
	}
}