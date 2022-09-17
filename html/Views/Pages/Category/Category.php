<?php

namespace Views\Pages\Category;

use Models\Data;

class Category extends \Views\Pages\Base
{
	public string $title = "Cookbook :: ";
	
	public function render(Data $data)
	{
		$this->title .= $data->category->name;
		parent::render($data);
	}
}