<?php

namespace Models\Objects;

use Models\Lists\MenuItemsList;

class MenuItem extends Base
{
	public string $table = "menu_items";
	public int $id;
	public string $name;
	public int $parentId;
	public string $url;
	public MenuItemsList $children;
	
	public function __construct(array $inputs = [])
	{
		if ($inputs['empty'] ?? false) {
			return;
		}
		parent::__construct($inputs);
		$this->children = new MenuItemsList(["parentId" => $this->id]);
	}
}