<?php

namespace Models\Objects;

use Models\Lists\MenuItemsList;

class MenuItem extends Base
{
	public string $table = "menu_items";
	public int $id;
	public string $name;
	public int $parentId;
	public MenuItemsList $children;
	
	public function __construct(array $inputs = [])
	{
		parent::__construct($inputs);
		$this->children = new MenuItemsList(["parentId" => $this->id]);
	}
}