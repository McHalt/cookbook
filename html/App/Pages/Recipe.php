<?php

namespace App\Pages;

class Recipe extends Base
{
	public function prepareData()
	{
		if (empty($_GET['id'])) {
			header('Location: ?p=404');
		}
		$this->data->recipe = new \Models\Objects\Recipe(['id' => $_GET['id']]);
		if (empty($this->data->recipe->name)) {
//			header('Location: ?p=404');
		}
	}
}