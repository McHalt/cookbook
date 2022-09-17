<?php

namespace App\Pages;

use Models\Lists\CategoriesList;
use Models\Objects\Category;

class EditCategory extends Base
{
	
	public function prepareData()
	{
		if (!empty($_GET['id'])) {
			$this->data->category = new Category(['id' => $_GET['id']]);
		} else {
			$this->data->category = new Category(['id' => 0]);
		}
		$this->data->mainCategories = new CategoriesList(['additionalSql' => 'WHERE parent_id = 0']);
			
		if (!empty($_POST['save'])) {
			$this->data->category->name = $_POST['name'];
			$this->data->category->parentId = $_POST['parent_id'];
			$this->data->category->save();
		}
	}
}