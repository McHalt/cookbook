<?php

namespace Models\Lists;

class MenuItemsList extends Base
{
	public string $elementsClass = "MenuItem";
	public string $table = "menu_items";
	
	public function __construct(array $inputs = [])
	{
		if (isset($inputs['parentId'])) {
			$this->additionalSql = "WHERE parent_id = " . intval($inputs['parentId']);
		}
		parent::__construct($inputs);
	}
}